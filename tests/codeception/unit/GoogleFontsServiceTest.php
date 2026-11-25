<?php

/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.app](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\tests\codeception\unit;

use humhub\modules\cleanTheme\models\Configuration;
use humhub\modules\cleanTheme\services\GoogleFontsService;
use ReflectionProperty;
use Yii;

/**
 * No test downloads from Google: the downloaded files are written to the test data mount (`uploads/tests`) by hand.
 */
class GoogleFontsServiceTest extends CleanThemeTestCase
{
    private const FONT_URL_1 = 'https://fonts.gstatic.com/s/roboto/v48/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBA.woff2';
    private const FONT_URL_2 = 'https://fonts.gstatic.com/s/roboto/v48/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3GUBGEe.woff2';

    public static function stylesheetProvider(): array
    {
        $fontFace = "@font-face {\n  font-family: 'Roboto';\n  src: url(%s) format('woff2');\n}\n";
        $url = 'https://fonts.gstatic.com/s/roboto/v48/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBA.woff2';

        return [
            'Google Fonts stylesheet, duplicates once' => [sprintf($fontFace, $url) . sprintf($fontFace, $url), [$url]],
            'quoted URL' => [sprintf($fontFace, '"' . $url . '"'), [$url]],
            'no font file' => ['body { color: red; }', null],
            'another host' => [sprintf($fontFace, $url) . sprintf($fontFace, 'https://example.com/font.woff2'), null],
            'not https' => [sprintf($fontFace, 'http://fonts.gstatic.com/s/roboto/v48/a.woff2'), null],
            'not woff2' => [sprintf($fontFace, 'https://fonts.gstatic.com/s/roboto/v48/a.ttf'), null],
            'query string' => [sprintf($fontFace, 'https://fonts.gstatic.com/s/roboto/v48/a.php?x.woff2'), null],
        ];
    }

    /**
     * @dataProvider stylesheetProvider
     */
    public function testOnlyGoogleFontFilesAreAccepted(string $css, ?array $expected): void
    {
        $this->assertSame($expected, GoogleFontsService::extractFontUrls($css));
    }

    public function testDownloadWithoutGoogleFontsRemovesTheDownloadedOnes(): void
    {
        $configuration = $this->newConfigurationWithFont('Open Sans');
        $this->writeDownloadedFonts('stale');

        $this->assertTrue((new GoogleFontsService($configuration))->download());
        $this->assertNull($configuration->settingsManager->get(GoogleFontsService::KEY_SETTING));
        $this->assertFalse(Yii::$app->fs->getDataMount()->fileExists(GoogleFontsService::DATA_DIR . '/' . GoogleFontsService::CSS_FILE));
    }

    public function testFontsAlreadyDownloadedAreNotDownloadedAgain(): void
    {
        $configuration = $this->newConfigurationWithFont('Roboto');
        $key = GoogleFontsService::getKeyFromUrl($configuration->getGoogleFontsCss2Url());
        $this->writeDownloadedFonts($key);

        $this->assertTrue((new GoogleFontsService($configuration))->download());
        $this->assertSame($key, $configuration->settingsManager->get(GoogleFontsService::KEY_SETTING));
        $this->assertTrue(Yii::$app->fs->getDataMount()->fileExists(GoogleFontsService::DATA_DIR . '/' . GoogleFontsService::CSS_FILE));
    }

    public function testDownloadedFontsArePublishedAndServed(): void
    {
        $configuration = $this->newConfigurationWithFont('Roboto');
        $key = GoogleFontsService::getKeyFromUrl($configuration->getGoogleFontsCss2Url());
        $this->writeDownloadedFonts($key);

        $this->publish($configuration);

        $assetsFs = Yii::$app->fs->getAssetsMount();
        $assetsDir = GoogleFontsService::ASSETS_DIR . '/' . $key . '/';
        $this->assertTrue($assetsFs->fileExists($assetsDir . GoogleFontsService::CSS_FILE));
        foreach ([self::FONT_URL_1, self::FONT_URL_2] as $fontUrl) {
            $this->assertSame('wOF2' . $fontUrl, $assetsFs->read($assetsDir . GoogleFontsService::getFontFileName($fontUrl)));
        }

        $css = $configuration->getCss();
        $this->assertStringContainsString('@font-face', $css);
        $this->assertStringContainsString('url(' . Yii::$app->assetManager->baseUrl . '/' . $assetsDir . GoogleFontsService::getFontFileName(self::FONT_URL_1) . ')', $css);
        $this->assertStringNotContainsString('fonts.gstatic.com', $css, 'browsers do not contact Google');
    }

    public function testFontsOfAnotherConfigurationAreNotServed(): void
    {
        $configuration = $this->newConfigurationWithFont('Roboto');
        $this->writeDownloadedFonts(GoogleFontsService::getKeyFromUrl($configuration->getGoogleFontsCss2Url()));

        $configuration->fontFamily = 'Lato'; // changed without saving the configuration, e.g. by another module
        $this->assertNull((new GoogleFontsService($configuration))->getKey());
        $this->assertStringNotContainsString('@font-face', $configuration->getCss(), 'Lato is loaded from Google');
    }

    public function testIncompleteDownloadedFontsAreLoadedFromGoogle(): void
    {
        $configuration = $this->newConfigurationWithFont('Roboto');
        $this->writeDownloadedFonts(GoogleFontsService::getKeyFromUrl($configuration->getGoogleFontsCss2Url()));
        Yii::$app->fs->getDataMount()->delete(GoogleFontsService::DATA_DIR . '/' . GoogleFontsService::getFontFileName(self::FONT_URL_2));

        $this->publish($configuration);

        $this->assertNull($configuration->settingsManager->get(GoogleFontsService::KEY_SETTING), 'the key is removed: the layout loads the fonts from Google');
        $this->assertStringNotContainsString('@font-face', $configuration->getCss());
    }

    private function newConfigurationWithFont(string $font): Configuration
    {
        $configuration = new Configuration(['settingsManager' => $this->getCleanThemeModule()->settings]);
        $configuration->fontFamily = $font;
        $configuration->headingFontFamily = $font;
        return $configuration;
    }

    /**
     * Writes what `GoogleFontsService::download()` writes, without contacting Google
     */
    private function writeDownloadedFonts(string $key): void
    {
        $dataFs = Yii::$app->fs->getDataMount();
        $css = '';
        foreach ([self::FONT_URL_1, self::FONT_URL_2] as $fontUrl) {
            $css .= "@font-face {\n  font-family: 'Roboto';\n  src: url(" . $fontUrl . ") format('woff2');\n}\n";
            $dataFs->write(GoogleFontsService::DATA_DIR . '/' . GoogleFontsService::getFontFileName($fontUrl), 'wOF2' . $fontUrl);
        }
        $dataFs->write(GoogleFontsService::DATA_DIR . '/' . GoogleFontsService::CSS_FILE, $css);
        $this->getCleanThemeModule()->settings->set(GoogleFontsService::KEY_SETTING, $key);
    }

    /**
     * `publish()` runs once per request
     */
    private function publish(Configuration $configuration): void
    {
        $published = new ReflectionProperty(GoogleFontsService::class, 'published');
        $published->setValue(null, false);
        try {
            (new GoogleFontsService($configuration))->publish();
        } finally {
            $published->setValue(null, false);
        }
    }
}
