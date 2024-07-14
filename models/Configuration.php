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

/**
 *
 * @property-read string $googleFontsCss2UrlParams
 * @property-read string $cssFromScss
 * @property-read array $specialColorCssVariables
 */
class Configuration extends Model
{
    public const HUMHUB_MODIFIED_PATH = '@clean-theme/resources/less/humhub.modified';
    public const SPECIAL_COLOR_VARIABLES_IN_HUMHUB_MODIFIED_FILE = '@clean-theme/resources/less/special-color-variables-in-humhub-modified.txt';
    public const SUPPORTED_LESS_FUNCTIONS = ['darken', 'lighten', 'fade', 'fadein', 'fadeout'];
    public const UNSUPPORTED_LESS_FUNCTIONS = ['saturate', 'desaturate', 'spin'];
    public const DYNAMIC_CSS_FILE_PATH = '@clean-theme/resources/css';
    public const DYNAMIC_CSS_FILE_NAME = 'humhub.clean-theme.dynamic.css';

    /**
     * This list must contain all CSS attribute names
     */
    public const CSS_ATTRIBUTE_UNITS = [
        'containerMaxWidth' => 'px',
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
        'topBarHeight' => 'px',
        'topBarFontSize' => 'px',
        'topMenuNavJustifyContent' => '',
        'topMenuBackgroundColor' => '',
        'topMenuTextColor' => '',
        'topMenuButtonHoverBackgroundColor' => '',
        'topMenuButtonHoverTextColor' => '',
    ];

    public SettingsManager $settingsManager;

    public string $containerMaxWidth = '1600';
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
    public string $topBarHeight = '50';
    public string $topBarFontSize = '10';
    public string $topMenuNavJustifyContent = 'center';
    public string $topMenuBackgroundColor = '#ffffff';
    public string $topMenuTextColor = '#31414a';
    public string $topMenuButtonHoverBackgroundColor = '#f7f7f7';
    public string $topMenuButtonHoverTextColor = '#242424';
    public string $scss = '';
    public string|bool $hideTopMenuOnScrollDown = true;
    public string|bool $hideBottomMenuOnScrollDown = true;
    public string|bool $hideTextInBottomMenuItems = true;

    public static function getJustifyContentOptions(): array
    {
        return [
            'center' => Yii::t('CleanThemeModule.config', 'Items centered'),
            'flex-start' => Yii::t('CleanThemeModule.config', 'Items grouped on the left'),
            'flex-end' => Yii::t('CleanThemeModule.config', 'Items grouped on the right'),
            'space-between' => Yii::t('CleanThemeModule.config', 'Items are evenly distributed; first item is on the left, last item on the right'),
            'space-around' => Yii::t('CleanThemeModule.config', 'Items are evenly distributed with equal space around them'),
        ];
    }

    public static function getBorderStyleOptions()
    {
        return [
            'none' => 'None',
            'solid' => 'Solid',
            'inset' => 'Inset',
            'outset' => 'Outset',
        ];
    }

    public static function getCssAttributeNames()
    {
        return array_keys(static::CSS_ATTRIBUTE_UNITS);
    }

    public static function getAllAttributeNames()
    {
        return array_merge(static::getCssAttributeNames(), [
            'scss',
            'hideTopMenuOnScrollDown',
            'hideBottomMenuOnScrollDown',
            'hideTextInBottomMenuItems',
        ]);
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
            [array_merge(static::getCssAttributeNames(), ['scss']), 'string'],
            [['hideTopMenuOnScrollDown', 'hideBottomMenuOnScrollDown', 'hideTextInBottomMenuItems'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'containerMaxWidth' => Yii::t('CleanThemeModule.config', 'Main content container width'),
            'default' => Yii::t('CleanThemeModule.config', 'Main default color'),
            'primary' => Yii::t('CleanThemeModule.config', 'Main "Primary" color'),
            'info' => Yii::t('CleanThemeModule.config', 'Main "Info" color'),
            'success' => Yii::t('CleanThemeModule.config', 'Main "Success" color'),
            'warning' => Yii::t('CleanThemeModule.config', 'Main "Warning" color'),
            'danger' => Yii::t('CleanThemeModule.config', 'Main "Danger" color'),
            'link' => Yii::t('CleanThemeModule.config', 'Main color for links'),
            'menuBorderColor' => Yii::t('CleanThemeModule.config', 'Menu border color (tab and dropdown menus)'),
            'textColorHeading' => Yii::t('CleanThemeModule.config', 'Headings text color'),
            'textColorMain' => Yii::t('CleanThemeModule.config', 'Main text color'),
            'textColorDefault' => Yii::t('CleanThemeModule.config', 'Default text color for icons, buttons, etc.'),
            'textColorSecondary' => Yii::t('CleanThemeModule.config', 'Secondary text color for some icons, buttons, etc.'),
            'textColorHighlight' => Yii::t('CleanThemeModule.config', 'Text highlight color for some active links, hover links, etc.'),
            'textColorSoft' => Yii::t('CleanThemeModule.config', 'Soft text color for side information like dates, placeholder, some dropdown headers'),
            'textColorSoft2' => Yii::t('CleanThemeModule.config', 'Soft text color 2 for other side information like wall entry links (like/comment), help blocks in forms, etc.'),
            'textColorSoft3' => Yii::t('CleanThemeModule.config', 'Soft text color 3 for grid view summary, etc.'),
            'textColorContrast' => Yii::t('CleanThemeModule.config', 'Contrast text color for "primary", "info", "success", "warning", "danger" buttons, etc.'),
            'backgroundColorMain' => Yii::t('CleanThemeModule.config', 'Main background color which should be in contrast with main, secondary and other text colors'),
            'backgroundColorSecondary' => Yii::t('CleanThemeModule.config', 'Secondary background color used for tabs, etc.'),
            'backgroundColorPage' => Yii::t('CleanThemeModule.config', 'Page background color for other UI components such as comment box, etc.'),
            'backgroundColorHighlight' => Yii::t('CleanThemeModule.config', 'Highlight color for informative backgrounds (e.g. Comment Permalinks, Shared items, Wiki active page navigation, Messenger bubbles)'),
            'backgroundColorHighlightSoft' => Yii::t('CleanThemeModule.config', 'Soft highlight color for informative backgrounds (e.g. Wiki active category)'),
            'fontFamily' => Yii::t('CleanThemeModule.config', 'Base font family'),
            'headingFontFamily' => Yii::t('CleanThemeModule.config', 'Headings font family'),
            'fontSize' => Yii::t('CleanThemeModule.config', 'Base font size'),
            'menuFontSize' => Yii::t('CleanThemeModule.config', 'Menu font size'),
            'phFontSize' => Yii::t('CleanThemeModule.config', 'Panels heading font size'),
            'h1FontSize' => Yii::t('CleanThemeModule.config', '1st level header font size'),
            'h1StreamFontSize' => Yii::t('CleanThemeModule.config', '1st level header font size in stream'),
            'h2FontSize' => Yii::t('CleanThemeModule.config', '2nd level header font size'),
            'h2StreamFontSize' => Yii::t('CleanThemeModule.config', '2nd level header font size in stream'),
            'h3FontSize' => Yii::t('CleanThemeModule.config', '3rd level header font size'),
            'h4FontSize' => Yii::t('CleanThemeModule.config', '4th level header font size'),
            'h5FontSize' => Yii::t('CleanThemeModule.config', '5th level header font size'),
            'h6FontSize' => Yii::t('CleanThemeModule.config', '6th level header font size'),
            'fontWeight' => Yii::t('CleanThemeModule.config', 'Base font weight'),
            'phFontWeight' => Yii::t('CleanThemeModule.config', 'Panels heading font weight'),
            'panelBorderWidth' => Yii::t('CleanThemeModule.config', 'Panels border width'),
            'panelBorderStyle' => Yii::t('CleanThemeModule.config', 'Panels border style'),
            'panelBorderColor' => Yii::t('CleanThemeModule.config', 'Panels border color'),
            'panelBorderRadius' => Yii::t('CleanThemeModule.config', 'Panels border radius'),
            'panelBoxShadow' => Yii::t('CleanThemeModule.config', 'Panels box shadow'),
            'topBarHeight' => Yii::t('CleanThemeModule.config', 'Top bar height'),
            'topBarFontSize' => Yii::t('CleanThemeModule.config', 'Button font size'),
            'topMenuNavJustifyContent' => Yii::t('CleanThemeModule.config', 'Navigation alignment'),
            'topMenuBackgroundColor' => Yii::t('CleanThemeModule.config', 'Background color'),
            'topMenuTextColor' => Yii::t('CleanThemeModule.config', 'Text color'),
            'topMenuButtonHoverBackgroundColor' => Yii::t('CleanThemeModule.config', 'Button background color on hover'),
            'topMenuButtonHoverTextColor' => Yii::t('CleanThemeModule.config', 'Button text color on hover'),
            'scss' => Yii::t('CleanThemeModule.config', 'Custom CSS'),
            'hideTopMenuOnScrollDown' => Yii::t('CleanThemeModule.config', 'Hide the top menu on scroll down'),
            'hideBottomMenuOnScrollDown' => Yii::t('CleanThemeModule.config', 'Hide the bottom menu on scroll down'),
            'hideTextInBottomMenuItems' => Yii::t('CleanThemeModule.config', 'Hide the text of the bottom menu buttons'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        $inPx = Yii::t('CleanThemeModule.config', 'In pixel');
        $inEm = Yii::t('CleanThemeModule.config', 'In relative size (em)');
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
            'topBarHeight' => $inPx,
            'topBarFontSize' => $inPx,
            'scss' => Yii::t('CleanThemeModule.config', 'Use Sassy CSS syntax (SCSS)'),
        ];
    }

    public function loadBySettings(): void
    {
        foreach (static::getAllAttributeNames() as $attributeName) {
            $this->$attributeName = $this->settingsManager->get($attributeName, $this->$attributeName);
        }
        $this->hideTopMenuOnScrollDown = (bool)$this->hideTopMenuOnScrollDown;
        $this->hideBottomMenuOnScrollDown = (bool)$this->hideBottomMenuOnScrollDown;
        $this->hideTextInBottomMenuItems = (bool)$this->hideTextInBottomMenuItems;
    }

    /**
     * @throws Exception
     */
    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        if ($this->panelBorderStyle === 'none') {
            $this->panelBorderWidth = '0';
        }

        // Reset to default value if empty CSS value was entered
        $defaultConfiguration = new Configuration();
        foreach (static::getCssAttributeNames() as $attributeName) {
            // If empty value, reset to default value
            if (($this->$attributeName === '')) {
                $this->$attributeName = $defaultConfiguration->$attributeName;
            }
            if (static::isFontAttribute($attributeName)) {
                // Remove + in case the URL of the font was entered
                $this->$attributeName = str_replace('+', ' ', $this->$attributeName);
            }
        }

        // Save configuration attributes
        foreach (static::getAllAttributeNames() as $attributeName) {
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
        $css = '/** This file is auto-generated by the Clean Theme configuration. Do not edit the file directly. */' . PHP_EOL . PHP_EOL;

        // Start CSS variables
        $css .= ':root {' . PHP_EOL;

        // Configuration attributes
        foreach (self::CSS_ATTRIBUTE_UNITS as $name => $unit) {
            $cssVarName = '--' . Inflector::camel2id($name);
            $value = static::isFontAttribute($name) ?
                '"' . $this->$name . '"' :
                $this->$name;

            // Example for `$containerMaxWidth` attribute: `--container-max-width: 1600px;`
            $css .= '    ' . $cssVarName . ': ' . $value . $unit . ';' . PHP_EOL;
        }
        $css .= PHP_EOL;

        // Special colors (darkened, lightened and faded colors from the on selected in the configuration)
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

        // End CSS variables
        $css .= '}' . PHP_EOL;

        // Custom CSS
        if ($this->scss) {
            $css .= PHP_EOL . $this->getCssFromScss() . PHP_EOL;
        }

        // Write file
        $dynamicCssPath = Yii::getAlias(self::DYNAMIC_CSS_FILE_PATH);
        if (
            !is_dir($dynamicCssPath)
            && !mkdir($dynamicCssPath, 0765)
            && !is_dir($dynamicCssPath)
        ) {
            throw new Exception(sprintf('Directory "%s" was not created', $dynamicCssPath));
        }
        file_put_contents($dynamicCssPath . '/' . self::DYNAMIC_CSS_FILE_NAME, $css);

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
        return file(Yii::getAlias(static::SPECIAL_COLOR_VARIABLES_IN_HUMHUB_MODIFIED_FILE), FILE_IGNORE_NEW_LINES);
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
