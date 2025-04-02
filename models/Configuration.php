<?php

/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\models;

use humhub\components\SettingsManager;
use humhub\widgets\bootstrap\Button;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\Inflector;

/**
 *
 * @property-read string $googleFontsCss2UrlParams
 * @property-read string $cssFromScss
 * @property-read array $specialColorCssVariables
 */
class Configuration extends Model
{
    public const DYNAMIC_CSS_FILE_PATH = '@clean-theme/resources/css';
    public const DYNAMIC_CSS_FILE_NAME = 'humhub.clean-theme.dynamic.css';
    public const BOOTSTRAP_CSS_PREFIX = '--bs-';
    public const HUMHUB_CSS_PREFIX = '--hh-';
    public const CLEAN_THEME_CSS_PREFIX = '--hh-ct-';
    public const TOP_BAR_BOTTOM_SPACING = 30;
    public const TOP_BAR_HEIGHT_SM = 50;
    public const TOP_BAR_BOTTOM_SPACING_SM = 5;
    public const BOTTOM_BAR_HEIGHT_XS = 50;
    public const MENU_STYLE_BACKGROUND = 'background';
    public const MENU_STYLE_BORDERED = 'bordered';

    /**
     * This list must contain all CSS attribute names
     */
    public const CSS_ATTRIBUTE_PREFIXES = [
        'containerMaxWidth' => self::CLEAN_THEME_CSS_PREFIX,
        'default' => self::BOOTSTRAP_CSS_PREFIX,
        'primary' => self::BOOTSTRAP_CSS_PREFIX,
        'info' => self::BOOTSTRAP_CSS_PREFIX,
        'success' => self::BOOTSTRAP_CSS_PREFIX,
        'warning' => self::BOOTSTRAP_CSS_PREFIX,
        'danger' => self::BOOTSTRAP_CSS_PREFIX,
        'link' => self::BOOTSTRAP_CSS_PREFIX,
        'textColorHeading' => self::CLEAN_THEME_CSS_PREFIX,
        'textColorMain' => self::HUMHUB_CSS_PREFIX,
        'textColorDefault' => self::HUMHUB_CSS_PREFIX,
        'textColorSecondary' => self::HUMHUB_CSS_PREFIX,
        'textColorHighlight' => self::HUMHUB_CSS_PREFIX,
        'textColorSoft' => self::HUMHUB_CSS_PREFIX,
        'textColorSoft2' => self::HUMHUB_CSS_PREFIX,
        'textColorSoft3' => self::HUMHUB_CSS_PREFIX,
        'textColorContrast' => self::HUMHUB_CSS_PREFIX,
        'backgroundColorMain' => self::HUMHUB_CSS_PREFIX,
        'backgroundColorSecondary' => self::HUMHUB_CSS_PREFIX,
        'backgroundColorPage' => self::HUMHUB_CSS_PREFIX,
        'backgroundColorHighlight' => self::HUMHUB_CSS_PREFIX,
        'backgroundColorHighlightSoft' => self::HUMHUB_CSS_PREFIX,
        'fontFamily' => self::CLEAN_THEME_CSS_PREFIX,
        'fontSize' => self::CLEAN_THEME_CSS_PREFIX,
        'fontWeight' => self::CLEAN_THEME_CSS_PREFIX,
        'headingFontFamily' => self::CLEAN_THEME_CSS_PREFIX,
        'phFontSize' => self::CLEAN_THEME_CSS_PREFIX,
        'h1FontSize' => self::CLEAN_THEME_CSS_PREFIX,
        'h1StreamFontSize' => self::CLEAN_THEME_CSS_PREFIX,
        'h2FontSize' => self::CLEAN_THEME_CSS_PREFIX,
        'h2StreamFontSize' => self::CLEAN_THEME_CSS_PREFIX,
        'h3FontSize' => self::CLEAN_THEME_CSS_PREFIX,
        'h4FontSize' => self::CLEAN_THEME_CSS_PREFIX,
        'h5FontSize' => self::CLEAN_THEME_CSS_PREFIX,
        'h6FontSize' => self::CLEAN_THEME_CSS_PREFIX,
        'phFontWeight' => self::CLEAN_THEME_CSS_PREFIX,
        'panelBorderWidth' => self::CLEAN_THEME_CSS_PREFIX,
        'panelBorderStyle' => self::CLEAN_THEME_CSS_PREFIX,
        'panelBorderColor' => self::CLEAN_THEME_CSS_PREFIX,
        'panelBorderRadius' => self::CLEAN_THEME_CSS_PREFIX,
        'panelBoxShadow' => self::CLEAN_THEME_CSS_PREFIX,
        'menuFontSize' => self::CLEAN_THEME_CSS_PREFIX,
        'menuTextColor' => self::CLEAN_THEME_CSS_PREFIX,
        'menuBorderColor' => self::CLEAN_THEME_CSS_PREFIX,
        'topBarHeight' => self::CLEAN_THEME_CSS_PREFIX,
        'topBarFontSize' => self::CLEAN_THEME_CSS_PREFIX,
        'topMenuNavJustifyContent' => self::CLEAN_THEME_CSS_PREFIX,
        'topMenuBackgroundColor' => self::CLEAN_THEME_CSS_PREFIX,
        'topMenuTextColor' => self::CLEAN_THEME_CSS_PREFIX,
        'topMenuButtonHoverBackgroundColor' => self::CLEAN_THEME_CSS_PREFIX,
        'topMenuButtonHoverTextColor' => self::CLEAN_THEME_CSS_PREFIX,
    ];

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
        'fontSize' => 'px',
        'fontWeight' => '',
        'headingFontFamily' => '',
        'phFontSize' => 'px',
        'h1FontSize' => 'em',
        'h1StreamFontSize' => 'em',
        'h2FontSize' => 'em',
        'h2StreamFontSize' => 'em',
        'h3FontSize' => 'em',
        'h4FontSize' => 'em',
        'h5FontSize' => 'em',
        'h6FontSize' => 'em',
        'phFontWeight' => '',
        'panelBorderWidth' => 'px',
        'panelBorderStyle' => '',
        'panelBorderColor' => '',
        'panelBorderRadius' => 'px',
        'panelBoxShadow' => '',
        'menuFontSize' => 'px',
        'menuTextColor' => '',
        'menuBorderColor' => '',
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
    public string $fontSize = '14';
    public string $fontFamily = 'Open Sans';
    public string $fontWeight = '400';
    public string $headingFontFamily = 'Open Sans';
    public string $phFontSize = '16'; // Panel Heading
    public string $h1FontSize = '1.7';
    public string $h1StreamFontSize = '1.45';
    public string $h2FontSize = '1.5';
    public string $h2StreamFontSize = '1.3';
    public string $h3FontSize = '1.2';
    public string $h4FontSize = '1.1';
    public string $h5FontSize = '1.0';
    public string $h6FontSize = '0.85';
    public string $phFontWeight = '700'; // Panel Heading
    public string $panelBorderWidth = '1';
    public string $panelBorderStyle = 'solid';
    public string $panelBorderColor = '#d2d3e4';
    public string $panelBorderRadius = '4';
    public string $panelBoxShadow = '0 1px 10px #00000019';
    public string $menuFontSize = '12';
    public string $menuTextColor = '#31414a';
    public string $menuBorderColor = '#e4eaec';
    public string $menuStyle = self::MENU_STYLE_BACKGROUND;
    public string $topBarHeight = '50';
    public string $topBarFontSize = '10';
    public string $topMenuNavJustifyContent = 'center';
    public string $topMenuBackgroundColor = '#ffffff';
    public string $topMenuTextColor = '#31414a';
    public string $topMenuButtonHoverBackgroundColor = '#f7f7f7';
    public string $topMenuButtonHoverTextColor = '#242424';
    public string|bool $hideTopMenuOnScrollDown = false;
    public string|bool $hideBottomMenuOnScrollDown = false;
    public string|bool $hideTextInBottomMenuItems = false;

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

    public static function getMenuStyleOptions()
    {
        return [
            self::MENU_STYLE_BACKGROUND => Yii::t('CleanThemeModule.config', 'Full primary color background for active items'),
            self::MENU_STYLE_BORDERED => Yii::t('CleanThemeModule.config', 'Distinct border link color for active items'),
        ];
    }

    public static function getCssAttributeNames()
    {
        return array_keys(static::CSS_ATTRIBUTE_UNITS);
    }

    public static function getAllAttributeNames()
    {
        return array_merge(static::getCssAttributeNames(), [
            'menuStyle',
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
            [array_merge(static::getCssAttributeNames(), ['menuStyle']), 'string'],
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
            'textColorHeading' => Yii::t('CleanThemeModule.config', 'Headings text color'),
            'textColorMain' => Yii::t('CleanThemeModule.config', 'Main text color'),
            'textColorDefault' => Yii::t('CleanThemeModule.config', 'Default text color for icons, buttons, etc.'),
            'textColorSecondary' => Yii::t('CleanThemeModule.config', 'Secondary text color for some icons, buttons, etc.'),
            'textColorHighlight' => Yii::t('CleanThemeModule.config', 'Text highlight color for some active links, hover links, etc.'),
            'textColorSoft' => Yii::t('CleanThemeModule.config', 'Soft text color for side information like dates, placeholder, some dropdown headers'),
            'textColorSoft2' => Yii::t('CleanThemeModule.config', 'Soft text color 2 for other side information like wall entry links (like/comment), help blocks in forms, etc.'),
            'textColorSoft3' => Yii::t('CleanThemeModule.config', 'Soft text color 3 for placeholders, grid view summary, etc.'),
            'textColorContrast' => Yii::t('CleanThemeModule.config', 'Contrast text color for "primary", "info", "success", "warning", "danger" buttons, etc.'),
            'backgroundColorMain' => Yii::t('CleanThemeModule.config', 'Main background color which should be in contrast with main, secondary and other text colors'),
            'backgroundColorSecondary' => Yii::t('CleanThemeModule.config', 'Secondary background color used for tabs, etc.'),
            'backgroundColorPage' => Yii::t('CleanThemeModule.config', 'Background color for page body and UI components such as comment box, etc.'),
            'backgroundColorHighlight' => Yii::t('CleanThemeModule.config', 'Highlight color for informative backgrounds (e.g. Comment Permalinks, Shared items, Wiki active page navigation, Messenger bubbles)'),
            'backgroundColorHighlightSoft' => Yii::t('CleanThemeModule.config', 'Soft highlight color for informative backgrounds (e.g. Wiki active category)'),
            'fontFamily' => Yii::t('CleanThemeModule.config', 'Font family'),
            'fontSize' => Yii::t('CleanThemeModule.config', 'Font size'),
            'fontWeight' => Yii::t('CleanThemeModule.config', 'Font weight'),
            'headingFontFamily' => Yii::t('CleanThemeModule.config', 'Font family'),
            'phFontSize' => Yii::t('CleanThemeModule.config', 'Panels heading font size'),
            'h1FontSize' => Yii::t('CleanThemeModule.config', '1st level header font size'),
            'h1StreamFontSize' => Yii::t('CleanThemeModule.config', '1st level header font size in stream'),
            'h2FontSize' => Yii::t('CleanThemeModule.config', '2nd level header font size'),
            'h2StreamFontSize' => Yii::t('CleanThemeModule.config', '2nd level header font size in stream'),
            'h3FontSize' => Yii::t('CleanThemeModule.config', '3rd level header font size'),
            'h4FontSize' => Yii::t('CleanThemeModule.config', '4th level header font size'),
            'h5FontSize' => Yii::t('CleanThemeModule.config', '5th level header font size'),
            'h6FontSize' => Yii::t('CleanThemeModule.config', '6th level header font size'),
            'phFontWeight' => Yii::t('CleanThemeModule.config', 'Panels heading font weight'),
            'panelBorderWidth' => Yii::t('CleanThemeModule.config', 'Panels border width'),
            'panelBorderStyle' => Yii::t('CleanThemeModule.config', 'Panels border style'),
            'panelBorderColor' => Yii::t('CleanThemeModule.config', 'Panels border color'),
            'panelBorderRadius' => Yii::t('CleanThemeModule.config', 'Panels border radius'),
            'panelBoxShadow' => Yii::t('CleanThemeModule.config', 'Panels box shadow'),
            'menuFontSize' => Yii::t('CleanThemeModule.config', 'Font size'),
            'menuTextColor' => Yii::t('CleanThemeModule.config', 'Text color'),
            'menuBorderColor' => Yii::t('CleanThemeModule.config', 'Border color (tab and dropdown menus)'),
            'menuStyle' => Yii::t('CleanThemeModule.config', 'Menu style'),
            'topBarHeight' => Yii::t('CleanThemeModule.config', 'Top bar height'),
            'topBarFontSize' => Yii::t('CleanThemeModule.config', 'Button font size'),
            'topMenuNavJustifyContent' => Yii::t('CleanThemeModule.config', 'Navigation alignment'),
            'topMenuBackgroundColor' => Yii::t('CleanThemeModule.config', 'Background color'),
            'topMenuTextColor' => Yii::t('CleanThemeModule.config', 'Text color'),
            'topMenuButtonHoverBackgroundColor' => Yii::t('CleanThemeModule.config', 'Button background color on hover'),
            'topMenuButtonHoverTextColor' => Yii::t('CleanThemeModule.config', 'Button text color on hover'),
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
            Yii::t('CleanThemeModule.config', 'You must authorize HumHub to download Google Fonts in the configuration file: {seeDocumentationLink}', [
                'documentationLink' => Button::asLink(Yii::t('CleanThemeModule.config', 'see documentation'))->link('https://marketplace.humhub.com/module/clean-theme/installation')->options(['target' => '_blank']),
            ]);

        return [
            'containerMaxWidth' => $inPx,
            'fontFamily' => $googleFonts,
            'fontSize' => $inPx,
            'headingFontFamily' => $googleFonts,
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
            $cssVarName = (self::CSS_ATTRIBUTE_PREFIXES[$name] ?? '--hh-ct-') . Inflector::camel2id($name);
            $value = static::isFontAttribute($name) ?
                '"' . $this->$name . '", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif' :
                $this->$name;

            // Example for `$containerMaxWidth` attribute: `--container-max-width: 1600px;`
            $css .= '    ' . $cssVarName . ': ' . $value . $unit . ';' . PHP_EOL;
        }
        $css .= PHP_EOL;

        // Dimensions
        $css .= '    --hh-ct-top-bar-bottom-spacing: ' . self::TOP_BAR_BOTTOM_SPACING . 'px;' . PHP_EOL;
        $css .= '    --hh-fixed-header-height: ' . ((int)$this->topBarHeight + self::TOP_BAR_BOTTOM_SPACING) . 'px;' . PHP_EOL;
        $css .= '    --hh-fixed-footer-height: 0px;' . PHP_EOL;

        // End CSS variables
        $css .= '}' . PHP_EOL;
        $css .= PHP_EOL;

        // Mobile CSS variables
        $css .= '@media (max-width: var(--bs-breakpoint-md)) {' . PHP_EOL;
        $css .= '    :root {' . PHP_EOL;
        $css .= '        --hh-ct-top-bar-height: ' . self::TOP_BAR_HEIGHT_SM . 'px;' . PHP_EOL;
        $css .= '        --hh-ct-top-bar-bottom-spacing: ' . self::TOP_BAR_BOTTOM_SPACING_SM . 'px;' . PHP_EOL;
        $css .= '        --hh-fixed-header-height: ' . (self::TOP_BAR_HEIGHT_SM + self::TOP_BAR_BOTTOM_SPACING_SM) . 'px;' . PHP_EOL;
        $css .= '    }' . PHP_EOL;
        $css .= '}' . PHP_EOL;
        $css .= PHP_EOL;
        $css .= '@media (max-width: var(--bs-breakpoint-sm)) {' . PHP_EOL;
        $css .= '    :root {' . PHP_EOL;
        $css .= '        --hh-fixed-footer-height: ' . (self::BOTTOM_BAR_HEIGHT_XS + 2) . 'px;' . PHP_EOL; // + 2px for the bottom border
        $css .= '    }' . PHP_EOL;
        $css .= '}' . PHP_EOL;

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
}
