<?php

/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.app](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\tests\codeception\unit;

use humhub\modules\cleanTheme\controllers\ConfigController;
use humhub\modules\cleanTheme\models\Configuration;
use humhub\modules\cleanTheme\models\UploadConfiguration;
use humhub\modules\cleanTheme\Module;
use Yii;
use yii\web\UploadedFile;

class ConfigurationTest extends CleanThemeTestCase
{
    public function testEveryAttributeIsDeclaredAndLoadable(): void
    {
        $this->assertEqualsCanonicalizing(
            array_keys(Configuration::CSS_ATTRIBUTE_UNITS),
            array_keys(Configuration::CSS_ATTRIBUTE_PREFIXES),
            'a CSS attribute has both a unit and a prefix',
        );

        $configuration = $this->newConfiguration();
        foreach (Configuration::getAllAttributeNames() as $name) {
            $this->assertTrue(property_exists($configuration, $name), $name . ' is a property');
            $this->assertTrue($configuration->isAttributeSafe($name), $name . ' can be loaded from the form and the JSON import');
            if (Configuration::isDarkColorAttribute($name)) {
                $this->assertContains(substr($name, 0, -4), Configuration::getCssAttributeNames(), $name . ' is the dark variant of a light attribute');
            }
        }
    }

    public function testBooleanSettingsAreBooleansAfterLoading(): void
    {
        $settings = $this->getCleanThemeModule()->settings;
        $settings->set('hideTopMenuOnScrollDown', '1');
        $settings->set('hideBottomMenuOnScrollDown', '0');
        $settings->set('linkColor', '#123456');

        $configuration = $this->newConfiguration();
        $this->assertTrue($configuration->hideTopMenuOnScrollDown);
        $this->assertFalse($configuration->hideBottomMenuOnScrollDown);
        $this->assertSame('#123456', $configuration->linkColor);
    }

    public function testSaveNormalizesAndPersistsTheValues(): void
    {
        $configuration = $this->newConfiguration();
        $this->assertTrue($configuration->load(['Configuration' => [
            'linkColor' => '',
            'fontFamily' => 'Open+Sans',
            'headingFontFamily' => '',
            'panelBorderStyle' => 'none',
            'panelBorderWidth' => '3',
            'topBarHeight' => '64',
            'menuStyle' => Configuration::MENU_STYLE_SOFT,
            'hideTopMenuOnScrollDown' => '1',
            'hideBottomMenuOnScrollDown' => '0',
        ]]));
        $this->assertTrue($configuration->save(), json_encode($configuration->getErrors()));
        $this->assertFalse($configuration->hasGoogleFontsDownloadFailed(), 'Open Sans is hosted locally: nothing to download');

        $default = new Configuration();
        $reloaded = $this->newConfiguration();
        $this->assertSame($default->linkColor, $reloaded->linkColor, 'an empty value is reset to the default');
        $this->assertSame('Open Sans', $reloaded->fontFamily, 'the "+" of a font name copied from a Google Fonts URL is a space');
        $this->assertSame($default->headingFontFamily, $reloaded->headingFontFamily);
        $this->assertSame('0', $reloaded->panelBorderWidth, 'no border: no width');
        $this->assertSame('64', $reloaded->topBarHeight);
        $this->assertSame(Configuration::MENU_STYLE_SOFT, $reloaded->menuStyle);
        $this->assertTrue($reloaded->hideTopMenuOnScrollDown);
        $this->assertFalse($reloaded->hideBottomMenuOnScrollDown);

        $cssVersion = $this->getCleanThemeModule()->settings->get(Configuration::CSS_VERSION_SETTING);
        $this->assertNotEmpty($cssVersion);
        $this->assertStringContainsString(
            urlencode($this->getCleanThemeModule()->getVersion() . '-' . $cssVersion),
            $reloaded->getCssUrl(),
            'the CSS URL changes on each save and module update (cache buster)',
        );
        $this->assertStringContainsString('--hh-fixed-header-height: 94px;', $reloaded->getCss(), 'top bar height + bottom spacing');
    }

    public function testInvalidValuesAreNotSaved(): void
    {
        $configuration = $this->newConfiguration();
        $linkColor = $configuration->linkColor;
        $configuration->linkColor = '#000001';
        $configuration->hideTopMenuOnScrollDown = 'yes';

        $this->assertFalse($configuration->save());
        $this->assertArrayHasKey('hideTopMenuOnScrollDown', $configuration->getErrors());
        $this->assertSame($linkColor, $this->newConfiguration()->linkColor, 'nothing is saved');
    }

    public function testCssVariables(): void
    {
        $configuration = new Configuration(['settingsManager' => $this->getCleanThemeModule()->settings]);
        $configuration->linkColor = '#abc';
        $configuration->linkColorDark = '#88c5e8';
        $configuration->topBarHeight = '60';
        $css = $configuration->getCss();

        [$root, $dark] = $this->getCssBlocks($css);

        $this->assertStringContainsString('    --hh-ct-container-max-width: 1600px;' . PHP_EOL, $root);
        $this->assertStringContainsString('    --hh-ct-h1-font-size: 1.7em;' . PHP_EOL, $root);
        $this->assertStringContainsString('    --hh-text-color-soft2: #838383;' . PHP_EOL, $root);
        $this->assertStringContainsString('    --hh-ct-font-family: "Open Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;' . PHP_EOL, $root);
        $this->assertStringContainsString('    --hh-fixed-header-height: 90px;' . PHP_EOL, $root);

        // Bootstrap 5.3 colors the links from the `-rgb` variables, and swaps them on hover
        $this->assertStringContainsString('    --bs-link-color: #abc;' . PHP_EOL, $root);
        $this->assertStringContainsString('    --bs-link-hover-color: #abc;' . PHP_EOL, $root);
        $this->assertStringContainsString('    --bs-link-color-rgb: 170, 187, 204;' . PHP_EOL, $root);
        $this->assertStringContainsString('    --bs-link-hover-color-rgb: 170, 187, 204;' . PHP_EOL, $root);
        $this->assertStringNotContainsString('#88c5e8', $root, 'no dark mode color in the light mode block');

        // Dark mode: the same variable names, without the `-dark` suffix
        $this->assertStringContainsString('    --bs-link-color: #88c5e8;' . PHP_EOL, $dark);
        $this->assertStringContainsString('    --bs-link-color-rgb: 136, 197, 232;' . PHP_EOL, $dark);
        $this->assertStringContainsString('    --hh-background-color-main: #222222;' . PHP_EOL, $dark);
        $this->assertStringNotContainsString('-dark:', $dark);
        $this->assertStringNotContainsString('--hh-ct-font-size', $dark, 'only the colors have a dark variant');

        $this->assertStringNotContainsString('@font-face', $css, 'no downloaded Google Fonts');
    }

    public function testLinkColorRgbIsSkippedForOtherColorFormats(): void
    {
        $configuration = new Configuration(['settingsManager' => $this->getCleanThemeModule()->settings]);
        $configuration->linkColor = 'rgb(1, 2, 3)';
        [$root] = $this->getCssBlocks($configuration->getCss());

        $this->assertStringContainsString('    --bs-link-hover-color: rgb(1, 2, 3);' . PHP_EOL, $root);
        $this->assertStringNotContainsString('--bs-link-color-rgb', $root, 'an invalid `-rgb` value would break all links');
    }

    public function testGoogleFontsUrlSkipsTheLocallyHostedFonts(): void
    {
        $configuration = new Configuration(['settingsManager' => $this->getCleanThemeModule()->settings]);
        $url = static function (string $font, string $headingFont) use ($configuration): ?string {
            $configuration->fontFamily = $font;
            $configuration->headingFontFamily = $headingFont;
            return $configuration->getGoogleFontsCss2Url();
        };

        $this->assertNull($url('Open Sans', 'Open Sans'), 'Open Sans is shipped with the theme');
        $this->assertSame('https://fonts.googleapis.com/css2?family=Fira+Sans' . Configuration::GOOGLE_FONTS_CSS2_URL_SUFFIX, $url('Fira Sans', 'Fira Sans'), 'loaded once');
        $this->assertSame('https://fonts.googleapis.com/css2?family=Roboto+Slab' . Configuration::GOOGLE_FONTS_CSS2_URL_SUFFIX, $url('Open Sans', 'Roboto Slab'));
        $this->assertSame('https://fonts.googleapis.com/css2?family=Lato&family=Merriweather' . Configuration::GOOGLE_FONTS_CSS2_URL_SUFFIX, $url('Lato', 'Merriweather'));

        $locallyHostedFonts = Module::$locallyHostedFonts;
        Module::$locallyHostedFonts[] = 'Lato';
        try {
            $this->assertSame('https://fonts.googleapis.com/css2?family=Merriweather' . Configuration::GOOGLE_FONTS_CSS2_URL_SUFFIX, $url('Lato', 'Merriweather'), 'a font added by another module');
        } finally {
            Module::$locallyHostedFonts = $locallyHostedFonts;
        }
    }

    public function testExportedJsonCanBeImported(): void
    {
        $module = $this->getCleanThemeModule();
        $module->settings->set('linkColor', '#123456');
        $module->settings->set('hideTopMenuOnScrollDown', '1');
        $module->configuration->loadBySettings();

        /** @var ConfigController $controller */
        [$controller] = Yii::$app->createController('clean-theme/config');
        $controller->actionDownloadJson();
        $json = json_decode((string)Yii::$app->response->content, true);
        Yii::$app->response->clear();
        $this->assertSame('#123456', $json['linkColor']);
        $this->assertTrue($json['hideTopMenuOnScrollDown']);

        // Another site: other values, and a JSON from another module version
        $module->settings->set('linkColor', '#654321');
        $module->settings->set('hideTopMenuOnScrollDown', '0');
        $module->configuration->loadBySettings();
        $json['unknownAttribute'] = 'ignored';

        $file = tempnam(sys_get_temp_dir(), 'clean-theme');
        file_put_contents($file, json_encode($json));
        $_FILES = ['UploadConfiguration' => [
            'name' => ['jsonConfigurationFile' => 'humhub-clean-theme-configuration.json'],
            'type' => ['jsonConfigurationFile' => 'application/json'],
            'tmp_name' => ['jsonConfigurationFile' => $file],
            'error' => ['jsonConfigurationFile' => UPLOAD_ERR_OK],
            'size' => ['jsonConfigurationFile' => filesize($file)],
        ]];
        UploadedFile::reset();
        try {
            $this->assertTrue((new UploadConfiguration())->save());
        } finally {
            $_FILES = [];
            UploadedFile::reset();
            unlink($file);
        }

        $reloaded = $this->newConfiguration();
        $this->assertSame('#123456', $reloaded->linkColor);
        $this->assertTrue($reloaded->hideTopMenuOnScrollDown);
    }

    /**
     * @return string[] the `:root` and the `[data-bs-theme="dark"]` blocks
     */
    private function getCssBlocks(string $css): array
    {
        $this->assertSame(1, preg_match('/^:root \{(.*?)^}/ms', $css, $root));
        $this->assertSame(1, preg_match('/^\[data-bs-theme="dark"] \{(.*?)^}/ms', $css, $dark));

        return [$root[1], $dark[1]];
    }
}
