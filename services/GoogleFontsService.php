<?php

/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.app](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\services;

use humhub\libs\HttpClient;
use humhub\modules\cleanTheme\models\Configuration;
use League\Flysystem\Visibility;
use Throwable;
use Yii;
use yii\base\Exception;

/**
 * Serves the Google Fonts of the configuration from the HumHub server, so that browsers don't contact Google.
 *
 * - `download()` (on configuration save and on module update) fetches the Google Fonts stylesheet and its font files,
 *   and stores them in the data mount (`uploads/clean-theme/fonts`), which is persistent.
 * - `publish()` (on page render) copies them to the assets mount (`assets/clean-theme-fonts/<key>`), which is public
 *   but wiped on each cache flush and update, when they are missing there.
 * - `getFontFaceCss()` returns the Google Fonts stylesheet pointing to these public files, added to the generated
 *   CSS (see `Configuration::getCss()`).
 *
 * If the fonts cannot be downloaded, the setting holding the key is empty and the layout loads the fonts from Google.
 *
 * @since 2.6.0
 */
class GoogleFontsService
{
    /**
     * Module setting holding the key of the downloaded fonts (a short hash of the Google Fonts stylesheet URL)
     */
    public const KEY_SETTING = 'googleFontsLocalKey';
    /**
     * Directory in the data mount (persistent)
     */
    public const DATA_DIR = 'clean-theme/fonts';
    /**
     * Directory in the assets mount (public, wiped on each cache flush and update)
     */
    public const ASSETS_DIR = 'clean-theme-fonts';
    /**
     * Original Google Fonts stylesheet; in the assets mount, written last, it marks a complete copy
     */
    public const CSS_FILE = 'fonts.css';
    /**
     * Google Fonts chooses the font format from the user agent: a recent desktop Chrome gets `woff2` files
     */
    public const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36';
    /**
     * Only font files matching this pattern are accepted
     */
    public const FONT_URL_PATTERN = '#^https://fonts\.gstatic\.com/[A-Za-z0-9_\-./]+\.woff2$#';
    public const TIMEOUT = 30;
    public const CONNECT_TIMEOUT = 10;

    /**
     * Whether `publish()` already ran in this request (it is called by the layout and by `Events::onViewBeginBody()`)
     */
    private static bool $published = false;

    public function __construct(private readonly Configuration $configuration)
    {
    }

    /**
     * Downloads the Google Fonts of the configuration to the data mount.
     * Never throws: on failure, the downloaded fonts are removed and the fonts are loaded from Google.
     *
     * @return bool false if the fonts could not be downloaded
     */
    public function download(): bool
    {
        try {
            $cssUrl = $this->configuration->getGoogleFontsCss2Url();
            if ($cssUrl === null) {
                $this->deleteFiles();
                $this->configuration->settingsManager->delete(self::KEY_SETTING);
                return true;
            }

            $key = static::getKeyFromUrl($cssUrl);
            if ($this->configuration->settingsManager->get(self::KEY_SETTING) === $key) {
                return true;
            }

            if (function_exists('set_time_limit')) {
                @set_time_limit(120);
            }

            $client = static::createHttpClient();

            $response = $client->get($cssUrl, null, ['User-Agent' => self::USER_AGENT])->send();
            if (!$response->isOk) {
                throw new Exception('HTTP ' . $response->getStatusCode() . ' for ' . $cssUrl);
            }
            $css = (string)$response->getContent();
            $fontUrls = static::extractFontUrls($css);
            if ($fontUrls === null) {
                throw new Exception('Unexpected content of ' . $cssUrl);
            }

            // Keep everything in memory until all downloads succeeded
            $requests = [];
            foreach ($fontUrls as $fontUrl) {
                $requests[$fontUrl] = $client->get($fontUrl, null, ['User-Agent' => self::USER_AGENT]);
            }
            $fontFiles = [];
            foreach ($client->batchSend($requests) as $fontUrl => $fontResponse) {
                $content = (string)$fontResponse->getContent();
                if (!$fontResponse->isOk || !str_starts_with($content, 'wOF2')) {
                    throw new Exception('Invalid font file ' . $fontUrl);
                }
                $fontFiles[static::getFontFileName($fontUrl)] = $content;
            }

            $this->deleteFiles();
            $dataFs = Yii::$app->fs->getDataMount();
            foreach ($fontFiles as $fileName => $content) {
                $dataFs->write(self::DATA_DIR . '/' . $fileName, $content);
            }
            $dataFs->write(self::DATA_DIR . '/' . self::CSS_FILE, $css);

            $this->configuration->settingsManager->set(self::KEY_SETTING, $key);
            return true;
        } catch (Throwable $e) {
            Yii::warning('Could not download the Google Fonts, they are loaded from Google: ' . $e->getMessage(), 'clean-theme');
            try {
                $this->deleteFiles();
                $this->configuration->settingsManager->delete(self::KEY_SETTING);
            } catch (Throwable $e) {
                Yii::warning('Could not delete the downloaded Google Fonts: ' . $e->getMessage(), 'clean-theme');
            }
            return false;
        }
    }

    /**
     * Copies the downloaded fonts to the assets mount if they are missing there.
     * On a normal request, a single existence check in the assets mount; only the first call of a request runs.
     */
    public function publish(): void
    {
        if (static::$published) {
            return;
        }
        static::$published = true;

        $key = $this->getKey();
        if ($key === null) {
            return;
        }

        $assetsFs = Yii::$app->fs->getAssetsMount();
        $assetsDir = self::ASSETS_DIR . '/' . $key;
        try {
            if ($assetsFs->fileExists($assetsDir . '/' . self::CSS_FILE)) {
                return;
            }

            $dataFs = Yii::$app->fs->getDataMount();
            $css = $dataFs->read(self::DATA_DIR . '/' . self::CSS_FILE);
            $fontUrls = static::extractFontUrls($css);
            if ($fontUrls === null) {
                throw new Exception('Invalid ' . self::DATA_DIR . '/' . self::CSS_FILE);
            }
            $options = ['visibility' => Visibility::PUBLIC, 'directory_visibility' => Visibility::PUBLIC];
            foreach ($fontUrls as $fontUrl) {
                $fileName = static::getFontFileName($fontUrl);
                $assetsFs->write($assetsDir . '/' . $fileName, $dataFs->read(self::DATA_DIR . '/' . $fileName), $options);
            }
            // Written last: marks a complete copy
            $assetsFs->write($assetsDir . '/' . self::CSS_FILE, $css, $options);
        } catch (Throwable $e) {
            Yii::warning('Could not publish the downloaded Google Fonts, they are loaded from Google: ' . $e->getMessage(), 'clean-theme');
            $this->configuration->settingsManager->delete(self::KEY_SETTING);
        }
    }

    /**
     * `@font-face` rules of the downloaded fonts, pointing to their public URL (see `publish()`)
     *
     * @return string empty if the fonts are not downloaded
     */
    public function getFontFaceCss(): string
    {
        $key = $this->getKey();
        if ($key === null) {
            return '';
        }

        try {
            $css = Yii::$app->fs->getDataMount()->read(self::DATA_DIR . '/' . self::CSS_FILE);
        } catch (Throwable $e) {
            Yii::warning('Could not read the downloaded Google Fonts stylesheet: ' . $e->getMessage(), 'clean-theme');
            return '';
        }

        return static::rewriteFontUrls($css, Yii::$app->assetManager->baseUrl . '/' . self::ASSETS_DIR . '/' . $key);
    }

    /**
     * Key of the downloaded fonts, if they match the current configuration.
     * A stored key of other fonts (e.g. font settings changed without saving the configuration) is ignored,
     * so that these fonts are loaded from Google.
     */
    public function getKey(): ?string
    {
        $cssUrl = $this->configuration->getGoogleFontsCss2Url();
        if ($cssUrl === null) {
            return null;
        }
        $key = static::getKeyFromUrl($cssUrl);

        return $this->configuration->settingsManager->get(self::KEY_SETTING) === $key ? $key : null;
    }

    /**
     * Deletes the downloaded fonts from the data and assets mounts
     */
    public function deleteFiles(): void
    {
        Yii::$app->fs->getDataMount()->deleteDirectory(self::DATA_DIR);
        Yii::$app->fs->getAssetsMount()->deleteDirectory(self::ASSETS_DIR);
    }

    public static function getKeyFromUrl(string $cssUrl): string
    {
        return substr(md5($cssUrl), 0, 12);
    }

    public static function getFontFileName(string $fontUrl): string
    {
        return md5($fontUrl) . '.woff2';
    }

    /**
     * URLs of the font files of a Google Fonts stylesheet
     *
     * @return string[]|null unique URLs, or null if the stylesheet has no font file or a URL not matching `FONT_URL_PATTERN`
     */
    public static function extractFontUrls(string $css): ?array
    {
        if (!preg_match_all('/url\(\s*([\'"]?)(.*?)\1\s*\)/i', $css, $matches) || empty($matches[2])) {
            return null;
        }

        $fontUrls = array_values(array_unique($matches[2]));
        foreach ($fontUrls as $fontUrl) {
            if (!preg_match(self::FONT_URL_PATTERN, $fontUrl)) {
                return null;
            }
        }

        return $fontUrls;
    }

    /**
     * Replaces the font file URLs of a Google Fonts stylesheet with their public URL
     *
     * @param string $baseUrl public URL of the directory holding the font files
     */
    public static function rewriteFontUrls(string $css, string $baseUrl): string
    {
        return preg_replace_callback(
            '/url\(\s*([\'"]?)(.*?)\1\s*\)/i',
            static fn(array $matches): string => preg_match(self::FONT_URL_PATTERN, $matches[2])
                ? 'url(' . $baseUrl . '/' . static::getFontFileName($matches[2]) . ')'
                : $matches[0],
            $css,
        );
    }

    private static function createHttpClient(): HttpClient
    {
        // Core's client replaces the request's cURL options with its own (SSL, proxy) when sending,
        // so the timeouts are added after them
        return new class extends HttpClient {
            public function beforeSend($request)
            {
                parent::beforeSend($request);
                $request->addOptions([
                    'timeout' => GoogleFontsService::TIMEOUT,
                    'connectTimeout' => GoogleFontsService::CONNECT_TIMEOUT,
                ]);
            }
        };
    }
}
