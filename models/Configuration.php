<?php
/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\models;

use humhub\components\SettingsManager;
use humhub\modules\cleanTheme\helpers\ColorHelper;
use humhub\widgets\Button;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Exception\SassException;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Inflector;

class Configuration extends Model
{
    public const STATIC_WITH_CSS_VARIABLES = '@clean-theme/resources/static-for-css-variables';
    public const SPECIAL_COLORS_LESS_FILE_NAME = 'special-colors-for-humhub-css-variables.less';
    public const SUPPORTED_LESS_FUNCTIONS = ['darken', 'lighten', 'fade', 'fadein', 'fadeout'];
    public const UNSUPPORTED_LESS_FUNCTIONS = ['saturate', 'desaturate', 'spin'];
    public const DYNAMIC_CSS_FILE_PATH = '@clean-theme/resources/css/';
    public const DYNAMIC_CSS_FILE_NAME = 'humhub.clean-theme.dynamic.css';
    public const CSS_ATTRIBUTE_UNITS = [
        'containerMaxWidth' => 'px',
        'topMenuNavJustifyContent' => '',
        'default' => '',
        'primary' => '',
        'info' => '',
        'success' => '',
        'warning' => '',
        'danger' => '',
        'link' => '',
        'menuBorderColor' => '',
        'textColorHeading' => '',
        'textColorMain' => '',
        'textColorDefault' => '',
        'textColorSecondary' => '',
        'textColorHighlight' => '',
        'textColorSoft' => '',
        'textColorSoft2' => '',
        'textColorSoft3' => '',
        'textColorContrast' => '',
        'backgroundColorMain' => '',
        'backgroundColorSecondary' => '',
        'backgroundColorPage' => '',
        'backgroundColorHighlight' => '',
        'backgroundColorHighlightSoft' => '',
        'fontFamily' => '',
        'headingFontFamily' => '',
        'fontSize' => 'px',
        'phFontSize' => 'px',
        'menuFontSize' => 'px',
        'h1FontSize' => 'em',
        'h1StreamFontSize' => 'em',
        'h2FontSize' => 'em',
        'h2StreamFontSize' => 'em',
        'h3FontSize' => 'em',
        'h4FontSize' => 'em',
        'h5FontSize' => 'em',
        'h6FontSize' => 'em',
        'fontWeight' => '',
        'phFontWeight' => '',
        'panelBorderWidth' => 'px',
        'panelBorderStyle' => '',
        'panelBorderColor' => '',
        'panelBorderRadius' => 'px',
        'panelBoxShadow' => '',
    ];

    public SettingsManager $settingsManager;

    public string $containerMaxWidth = '1600';
    public string $topMenuNavJustifyContent = 'center';
    public string $default = '#f3f3f3';
    public string $primary = '#31414a';
    public string $info = '#1A808E';
    public string $success = '#518132';
    public string $warning = '#AF640E';
    public string $danger = '#EC0426';
    public string $link = '#1A7DB2';
    public string $menuBorderColor = '#e4eaec';
    public string $textColorHeading = '#37474f';
    public string $textColorMain = '#31414a';
    public string $textColorDefault = '#4b4b4b';
    public string $textColorSecondary = '#7a7a7a';
    public string $textColorHighlight = '#242424';
    public string $textColorSoft = '#555555';
    public string $textColorSoft2 = '#838383';
    public string $textColorSoft3 = '#bac2c7';
    public string $textColorContrast = '#ffffff';
    public string $backgroundColorMain = '#ffffff';
    public string $backgroundColorSecondary = '#f7f7f7';
    public string $backgroundColorPage = '#f1f4f5';
    public string $backgroundColorHighlight = '#daf0f3';
    public string $backgroundColorHighlightSoft = '#f2f9fb';
    public string $fontFamily = 'Open Sans';
    public string $headingFontFamily = 'Open Sans';
    public string $fontSize = '13';
    public string $phFontSize = '16'; // Panel Heading
    public string $menuFontSize = '12';
    public string $h1FontSize = '1.7';
    public string $h1StreamFontSize = '1.45';
    public string $h2FontSize = '1.5';
    public string $h2StreamFontSize = '1.3';
    public string $h3FontSize = '1.2';
    public string $h4FontSize = '1.1';
    public string $h5FontSize = '1.0';
    public string $h6FontSize = '0.85';
    public string $fontWeight = '400';
    public string $phFontWeight = '700'; // Panel Heading
    public string $panelBorderWidth = '1';
    public string $panelBorderStyle = 'solid';
    public string $panelBorderColor = '#c7c9e7';
    public string $panelBorderRadius = '4';
    public string $panelBoxShadow = '0 1px 10px #00000019';
    public string $scss = '';

    public static function getJustifyContentOptions(): array
    {
        return [
            'center' => Yii::t('CleanThemeModule.config', 'Items centered along the line'),
            'flex-start' => Yii::t('CleanThemeModule.config', 'Items packed toward the start line'),
            'flex-end' => Yii::t('CleanThemeModule.config', 'Items packed toward to end line'),
            'space-between' => Yii::t('CleanThemeModule.config', 'Items are evenly distributed in the line; first item is on the start line, last item on the end line'),
            'space-around' => Yii::t('CleanThemeModule.config', 'Items are evenly distributed in the line with equal space around them'),
        ];
    }

    public static function getBorderStyleOptions()
    {
        return [
            'solid' => Yii::t('CleanThemeModule.config', 'Solid'),
            'none' => Yii::t('CleanThemeModule.config', 'None'),
            'inset' => Yii::t('CleanThemeModule.config', 'Inset'),
            'outset' => Yii::t('CleanThemeModule.config', 'Outset'),
        ];
    }

    public static function getAllAttributeNames()
    {
        $attributeNames = array_keys(static::CSS_ATTRIBUTE_UNITS);
        $attributeNames[] = 'scss';
        return $attributeNames;
    }

    private static function isFontAttribute($attributeName): bool
    {
        return str_ends_with($attributeName, 'ontFamily');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [static::getAllAttributeNames(), 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'containerMaxWidth' => Yii::t('CleanThemeModule.config', 'Container max width'),
            'topMenuNavJustifyContent' => Yii::t('CleanThemeModule.config', 'Top menu navigation alignment'),
            'default' => Yii::t('CleanThemeModule.config', 'Default main color'),
            'primary' => Yii::t('CleanThemeModule.config', 'Primary main color'),
            'info' => Yii::t('CleanThemeModule.config', 'Info main color'),
            'success' => Yii::t('CleanThemeModule.config', 'Success main color'),
            'warning' => Yii::t('CleanThemeModule.config', 'Warning main color'),
            'danger' => Yii::t('CleanThemeModule.config', 'Danger main color'),
            'link' => Yii::t('CleanThemeModule.config', 'Link main color'),
            'menuBorderColor' => Yii::t('CleanThemeModule.config', 'Menu border color (tab and dropdown menus)'),
            'textColorHeading' => Yii::t('CleanThemeModule.config', 'Heading text color'),
            'textColorMain' => Yii::t('CleanThemeModule.config', 'Default body text color'),
            'textColorDefault' => Yii::t('CleanThemeModule.config', 'Default text color for default icons and buttons etc.'),
            'textColorSecondary' => Yii::t('CleanThemeModule.config', 'Secondary text color for some icons and buttons etc.'),
            'textColorHighlight' => Yii::t('CleanThemeModule.config', 'Highlight text color for text like some active links hovered links etc.'),
            'textColorSoft' => Yii::t('CleanThemeModule.config', 'Soft text color for side information like dates, placeholder, some dropdown headers'),
            'textColorSoft2' => Yii::t('CleanThemeModule.config', 'Soft text color 2 for other side information like wall entry links (like/comment), help blocks in forms etc.'),
            'textColorSoft3' => Yii::t('CleanThemeModule.config', 'Soft text color 3 for gridview summary and installer'),
            'textColorContrast' => Yii::t('CleanThemeModule.config', 'Contrast text color for @primary, @info, @success, @warning, @danger buttons etc.'),
            'backgroundColorMain' => Yii::t('CleanThemeModule.config', 'Main background color which should be in contrast with main, secondary and other text colors'),
            'backgroundColorSecondary' => Yii::t('CleanThemeModule.config', 'Secondary background color used beside others for tabs'),
            'backgroundColorPage' => Yii::t('CleanThemeModule.config', 'Page background color for other ui components as comment box etc.'),
            'backgroundColorHighlight' => Yii::t('CleanThemeModule.config', 'Informative highlighting background color (e.g. Comment Permalink, Shared Item, Calendar CurDay, Wiki Active Page Nav, Mail Module Speech Bubble)'),
            'backgroundColorHighlightSoft' => Yii::t('CleanThemeModule.config', 'Informative soft highlighting background color (e.g. Wiki Active Category)'),
            'fontFamily' => Yii::t('CleanThemeModule.config', 'Font family'),
            'headingFontFamily' => Yii::t('CleanThemeModule.config', 'Heading font family'),
            'fontSize' => Yii::t('CleanThemeModule.config', 'Font size'),
            'menuFontSize' => Yii::t('CleanThemeModule.config', 'Menu font size'),
            'phFontSize' => Yii::t('CleanThemeModule.config', 'Panel heading font size'),
            'h1FontSize' => Yii::t('CleanThemeModule.config', 'H1 font size'),
            'h1StreamFontSize' => Yii::t('CleanThemeModule.config', 'H1 font size in stream'),
            'h2FontSize' => Yii::t('CleanThemeModule.config', 'H2 font size'),
            'h2StreamFontSize' => Yii::t('CleanThemeModule.config', 'H2 font size in stream'),
            'h3FontSize' => Yii::t('CleanThemeModule.config', 'H3 font size'),
            'h4FontSize' => Yii::t('CleanThemeModule.config', 'H4 font size'),
            'h5FontSize' => Yii::t('CleanThemeModule.config', 'H5 font size'),
            'h6FontSize' => Yii::t('CleanThemeModule.config', 'H6 font size'),
            'fontWeight' => Yii::t('CleanThemeModule.config', 'Font weight'),
            'phFontWeight' => Yii::t('CleanThemeModule.config', 'Panel heading font weight'),
            'panelBorderWidth' => Yii::t('CleanThemeModule.config', 'Panel border width'),
            'panelBorderStyle' => Yii::t('CleanThemeModule.config', 'Panel border style'),
            'panelBorderColor' => Yii::t('CleanThemeModule.config', 'Panel border color'),
            'panelBorderRadius' => Yii::t('CleanThemeModule.config', 'Panel border radius'),
            'panelBoxShadow' => Yii::t('CleanThemeModule.config', 'Panel box shadow'),
            'scss' => Yii::t('CleanThemeModule.config', 'Custom CSS'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        $inPx = Yii::t('CleanThemeModule.config', 'In pixel');
        $inEm = Yii::t('CleanThemeModule.config', 'Relative size (em)');
        $googleFonts =
            Yii::t('CleanThemeModule.config', 'Google Font name') . ' ' .
            Button::info(Yii::t('CleanThemeModule.config', 'Browse fonts'))->icon('external-link')->link('https://fonts.google.com/')->options(['target' => '_blank'])->loader(false)->xs() . ' (' . Yii::t('CleanThemeModule.config', 'Use the name in the URL') . ')<br>' .
            Yii::t('CleanThemeModule.config', 'You might need to authorize HumHub to download Google Fonts in the {ContentSecurityPolicy} headers by adding the {googleFontsDownloadUrl} URL after {fontSrcSelf} (see {documentationLink})', [
                'googleFontsDownloadUrl' => Html::tag('code', 'https://fonts.gstatic.com'),
                'ContentSecurityPolicy' => Html::tag('code', 'Content-Security-Policy'),
                'fontSrcSelf' => Html::tag('code', 'font-src \'self\''),
                'documentationLink' => Button::asLink(Yii::t('CleanThemeModule.config', 'documentation'))->link('https://docs.humhub.org/docs/admin/security#strict-csp-settings')->options(['target' => '_blank']),
            ]);

        return [
            'containerMaxWidth' => $inPx,
            'fontFamily' => $googleFonts,
            'headingFontFamily' => $googleFonts,
            'fontSize' => $inPx,
            'phFontSize' => $inPx,
            'menuFontSize' => $inPx,
            'h1FontSize' => $inEm,
            'h1StreamFontSize' => $inEm,
            'h2FontSize' => $inEm,
            'h2StreamFontSize' => $inEm,
            'h3FontSize' => $inEm,
            'h4FontSize' => $inEm,
            'h5FontSize' => $inEm,
            'h6FontSize' => $inEm,
            'panelBorderWidth' => $inPx,
            'panelBorderRadius' => $inPx,
            'panelBoxShadow' => Yii::t('CleanThemeModule.config', 'CSS value (e.g. {cssValue1} or {cssValue2}, see {documentation})', [
                'cssValue1' => '<code>0 1px 10px #00000019;</code>',
                'cssValue2' => '<code>none</code>',
                'documentation' => '<a href="https://developer.mozilla.org/en-US/docs/Web/CSS/box-shadow" target="_blank">' . Yii::t('CleanThemeModule.config', 'documentation') . '</a>',
            ]),
            'scss' => Yii::t('CleanThemeModule.config', 'Use Sassy CSS syntax (SCSS)'),
        ];
    }

    public function loadBySettings(): void
    {
        foreach (static::getAllAttributeNames() as $attributeName) {
            $this->$attributeName = $this->settingsManager->get($attributeName, $this->$attributeName);
        }
    }

    /**
     * @throws Exception
     */
    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $defaultConfiguration = new Configuration();
        foreach (static::getAllAttributeNames() as $attributeName) {
            // If empty value, reset to default value
            $this->$attributeName = $this->$attributeName ?: $defaultConfiguration->$attributeName;
            if (static::isFontAttribute($attributeName)) {
                // Remove + in case the URL of the font was entered
                $this->$attributeName = str_replace('+', ' ', $this->$attributeName);
            }
            $this->settingsManager->set($attributeName, $this->$attributeName);
        }

        $this->generateDynamicCSSFile();

        return true;
    }

    /**
     * @throws Exception
     */
    public function generateDynamicCSSFile(): void
    {
        $css =
            '/** This file is auto-generated by the Clean Theme configuration. Do not edit the file directly. */' . PHP_EOL . PHP_EOL .
            ':root {' . PHP_EOL;

        foreach (self::CSS_ATTRIBUTE_UNITS as $name => $unit) {
            $cssVarName = '--' . Inflector::camel2id($name);
            $value = static::isFontAttribute($name) ?
                '"' . $this->$name . '"' :
                $this->$name;

            // Example for `$containerMaxWidth` attribute: `--container-max-width: 1600px; !important`
            $css .= '    ' . $cssVarName . ': ' . $value . $unit . ' !important;' . PHP_EOL;
        }

        $css .= PHP_EOL;

        // Generate special colors (darkened, lightened and faded colors from the on selected in the configuration)
        foreach ($this->getSpecialColorCssVariables() as $cssVariable) {
            [$amount, $function] = array_reverse(explode('-', $cssVariable));
            $colorName = lcfirst(Inflector::camelize(
                substr($cssVariable, 2, strlen($cssVariable) - strlen($function . '-' . $amount) - 3)
            ));
            if (
                $colorName
                && isset($this->$colorName)
                && $amount
                && in_array($function, static::SUPPORTED_LESS_FUNCTIONS, true)
            ) {
                $css .= '    ' . $cssVariable . ': ' . ColorHelper::$function($this->$colorName, $amount) . ';' . PHP_EOL;
            }
        }

        $css .= '}' . PHP_EOL;

        if ($this->scss) {
            $css .= PHP_EOL . $this->getCssFromScss() . PHP_EOL;
        }

        // Write file
        $dynamicCssFile = Yii::getAlias(self::DYNAMIC_CSS_FILE_PATH . self::DYNAMIC_CSS_FILE_NAME);
        if (!file_exists(dirname($dynamicCssFile))) {
            mkdir(dirname($dynamicCssFile), 0765, true);
        }
        file_put_contents($dynamicCssFile, $css);

        // Clear cache
        Yii::$app->assetManager->clear();
    }

    public function getGoogleFontsCss2UrlParams(): string
    {
        $fonts = [];
        if ($this->fontFamily !== 'Open Sans') {
            $fonts[] = $this->fontFamily;
        }
        if (
            $this->headingFontFamily !== 'Open Sans'
            && !in_array($this->headingFontFamily, $fonts, true)
        ) {
            $fonts[] = $this->headingFontFamily;
        }
        $fontsEncoded = [];
        foreach ($fonts as $font) {
            $fontsEncoded[] = 'family=' . urlencode($font);
        }
        return implode('&', $fontsEncoded);
    }

    private function getSpecialColorCssVariables(): array
    {
        $specialColors = file_get_contents(
            Yii::getAlias(static::STATIC_WITH_CSS_VARIABLES . '/' . static::SPECIAL_COLORS_LESS_FILE_NAME)
        );
        $regexPattern = '/var\((.*?)\)/';
        preg_match_all($regexPattern, $specialColors, $matches);
        return $matches[1];
    }

    /**
     * @throws Exception
     */
    private function getCssFromScss(): string
    {
        // Generate custom CSS from SCSS
        if (!class_exists('Compiler')) {
            require_once Yii::getAlias('@clean-theme/vendor/autoload.php');
        }
        $compiler = new Compiler();
        $scss = str_replace(['<style>', '<style type="text/css">', '</style>'], ['', '', ''], $this->scss);
        try {
            return $compiler->compileString($scss)->getCss();
        } catch (SassException $e) {
            throw new Exception(Yii::t('CleanThemeModule.config', 'Cannot compile SCSS to CSS code. Error message:') . ' ' . $e->getMessage());
        }
    }
}
