<?php
/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\models;

use humhub\components\SettingsManager;
use Yii;
use yii\base\Model;
use yii\helpers\Inflector;

class Configuration extends Model
{
    public const DYNAMIC_CSS_FILE_PATH = '@clean-theme/themes/Clean/css/';
    public const DYNAMIC_CSS_FILE_NAME = 'variables.css';
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
        'fontFamily' => '',
        'headingFontFamily' => '',
        'fontSize' => 'px',
        'phFontSize' => 'px',
        'rtFontSize' => 'px',
        'h1FontSize' => 'em',
        'h1RtFontSize' => 'em',
        'h2FontSize' => 'em',
        'h2RtFontSize' => 'em',
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

    public int $containerMaxWidth = 1600;
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
    public string $textColorSecondary = '#7a7a7a';
    public string $textColorHighlight = '#242424';
    public string $textColorSoft = '#555555';
    public string $textColorSoft2 = '#838383';
    public string $textColorSoft3 = '#bac2c7';
    public string $textColorContrast = '#ffffff';
    public string $backgroundColorMain = '#ffffff';
    public string $backgroundColorSecondary = '#f7f7f7';
    public string $backgroundColorPage = '#f1f4f5';
    public string $backgroundColorHighlight = '#fff8e0';
    public ?string $fontFamily = null;
    public ?string $headingFontFamily = null;
    public int $fontSize = 13;
    public int $phFontSize = 16; // Panel Heading
    public int $rtFontSize = 13; // Reach Text
    public float $h1FontSize = 1.7;
    public float $h1RtFontSize = 1.45;
    public float $h2FontSize = 1.5;
    public float $h2RtFontSize = 1.3;
    public float $h3FontSize = 1.2;
    public float $h4FontSize = 1.1;
    public float $h5FontSize = 1.0;
    public float $h6FontSize = 0.85;
    public int $fontWeight = 400;
    public int $phFontWeight = 700;
    public int $panelBorderWidth = 1;
    public string $panelBorderStyle = 'solid';
    public string $panelBorderColor = '#c7c9e7';
    public int $panelBorderRadius = 4;
    public ?string $panelBoxShadow = null;

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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['containerMaxWidth', 'fontSize', 'phFontSize', 'rtFontSize', 'fontWeight', 'phFontWeight', 'panelBorderWidth', 'panelBorderRadius'], 'integer'],
            [['h1FontSize', 'h1RtFontSize', 'h2FontSize', 'h2RtFontSize', 'h3FontSize', 'h4FontSize', 'h5FontSize', 'h6FontSize'], 'number'],
            [['topMenuNavJustifyContent', 'default', 'primary', 'info', 'success', 'warning', 'danger', 'link', 'menuBorderColor', 'textColorHeading',
                'textColorMain', 'textColorSecondary', 'textColorHighlight', 'textColorSoft', 'textColorSoft2', 'textColorSoft3',
                'textColorContrast', 'backgroundColorMain', 'backgroundColorSecondary', 'backgroundColorPage', 'backgroundColorHighlight', 'fontFamily', 'headingFontFamily', 'panelBorderStyle', 'panelBorderColor', 'panelBoxShadow'], 'string'],
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
            'menuBorderColor' => Yii::t('CleanThemeModule.config', 'Menu border color'),
            'textColorHeading' => Yii::t('CleanThemeModule.config', 'Heading text color'),
            'textColorMain' => Yii::t('CleanThemeModule.config', 'Main text color'),
            'textColorSecondary' => Yii::t('CleanThemeModule.config', 'Secondary text color'),
            'textColorHighlight' => Yii::t('CleanThemeModule.config', 'Highlight text color'),
            'textColorSoft' => Yii::t('CleanThemeModule.config', 'Soft text color'),
            'textColorSoft2' => Yii::t('CleanThemeModule.config', 'Soft text color 2'),
            'textColorSoft3' => Yii::t('CleanThemeModule.config', 'Soft text color 3'),
            'textColorContrast' => Yii::t('CleanThemeModule.config', 'Contrast text color'),
            'backgroundColorMain' => Yii::t('CleanThemeModule.config', 'Main background color'),
            'backgroundColorSecondary' => Yii::t('CleanThemeModule.config', 'Secondary background color'),
            'backgroundColorPage' => Yii::t('CleanThemeModule.config', 'Page background color'),
            'backgroundColorHighlight' => Yii::t('CleanThemeModule.config', 'Highlight background color'),
            'fontFamily' => Yii::t('CleanThemeModule.config', 'Font family'),
            'headingFontFamily' => Yii::t('CleanThemeModule.config', 'Heading font family'),
            'fontSize' => Yii::t('CleanThemeModule.config', 'Font size'),
            'phFontSize' => Yii::t('CleanThemeModule.config', 'Panel heading font size'),
            'rtFontSize' => Yii::t('CleanThemeModule.config', 'Reach text font size'),
            'h1FontSize' => Yii::t('CleanThemeModule.config', 'H1 font size'),
            'h1RtFontSize' => Yii::t('CleanThemeModule.config', 'H1 reach text font size'),
            'h2FontSize' => Yii::t('CleanThemeModule.config', 'H2 font size'),
            'h2RtFontSize' => Yii::t('CleanThemeModule.config', 'H2 reach text font size'),
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
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'containerMaxWidth' => Yii::t('CleanThemeModule.config', 'In pixel'),
            'fontFamily' => Yii::t('CleanThemeModule.config', 'Google Font name'),
            'headingFontFamily' => Yii::t('CleanThemeModule.config', 'Google Font name'),
            'fontSize' => Yii::t('CleanThemeModule.config', 'In pixel'),
            'phFontSize' => Yii::t('CleanThemeModule.config', 'In pixel'),
            'rtFontSize' => Yii::t('CleanThemeModule.config', 'In pixel'),
            'h1FontSize' => Yii::t('CleanThemeModule.config', 'Relative size (em)'),
            'h1RtFontSize' => Yii::t('CleanThemeModule.config', 'Relative size (em)'),
            'h2FontSize' => Yii::t('CleanThemeModule.config', 'Relative size (em)'),
            'h2RtFontSize' => Yii::t('CleanThemeModule.config', 'Relative size (em)'),
            'h3FontSize' => Yii::t('CleanThemeModule.config', 'Relative size (em)'),
            'h4FontSize' => Yii::t('CleanThemeModule.config', 'Relative size (em)'),
            'h5FontSize' => Yii::t('CleanThemeModule.config', 'Relative size (em)'),
            'h6FontSize' => Yii::t('CleanThemeModule.config', 'Relative size (em)'),
            'panelBorderWidth' => Yii::t('CleanThemeModule.config', 'In pixel'),
            'panelBorderRadius' => Yii::t('CleanThemeModule.config', 'In pixel'),
            'panelBoxShadow' => Yii::t('CleanThemeModule.config', 'CSS value (e.g. {cssValue}, see {documentation})', [
                'cssValue' => '<code>2px 1px 1px #333;</code>',
                'documentation' => '<a href="https://developer.mozilla.org/en-US/docs/Web/CSS/box-shadow" target="_blank">' . Yii::t('CleanThemeModule.config', 'documentation') . '</a>',
            ]),
        ];
    }

    public function loadBySettings(): void
    {
        $this->containerMaxWidth = (int)$this->settingsManager->get('containerMaxWidth', $this->containerMaxWidth);
        $this->topMenuNavJustifyContent = $this->settingsManager->get('topMenuNavJustifyContent', $this->topMenuNavJustifyContent);
        $this->default = $this->settingsManager->get('default', $this->default);
        $this->primary = $this->settingsManager->get('primary', $this->primary);
        $this->info = $this->settingsManager->get('info', $this->info);
        $this->success = $this->settingsManager->get('success', $this->success);
        $this->warning = $this->settingsManager->get('warning', $this->warning);
        $this->danger = $this->settingsManager->get('danger', $this->danger);
        $this->link = $this->settingsManager->get('link', $this->link);
        $this->menuBorderColor = $this->settingsManager->get('menuBorderColor', $this->menuBorderColor);
        $this->textColorHeading = $this->settingsManager->get('textColorHeading', $this->textColorHeading);
        $this->textColorMain = $this->settingsManager->get('textColorMain', $this->textColorMain);
        $this->textColorSecondary = $this->settingsManager->get('textColorSecondary', $this->textColorSecondary);
        $this->textColorHighlight = $this->settingsManager->get('textColorHighlight', $this->textColorHighlight);
        $this->textColorSoft = $this->settingsManager->get('textColorSoft', $this->textColorSoft);
        $this->textColorSoft2 = $this->settingsManager->get('textColorSoft2', $this->textColorSoft2);
        $this->textColorSoft3 = $this->settingsManager->get('textColorSoft3', $this->textColorSoft3);
        $this->textColorContrast = $this->settingsManager->get('textColorContrast', $this->textColorContrast);
        $this->backgroundColorMain = $this->settingsManager->get('backgroundColorMain', $this->backgroundColorMain);
        $this->backgroundColorSecondary = $this->settingsManager->get('backgroundColorSecondary', $this->backgroundColorSecondary);
        $this->backgroundColorPage = $this->settingsManager->get('backgroundColorPage', $this->backgroundColorPage);
        $this->backgroundColorHighlight = $this->settingsManager->get('backgroundColorHighlight', $this->backgroundColorHighlight);
        $this->fontFamily = $this->settingsManager->get('fontFamily', $this->fontFamily);
        $this->headingFontFamily = $this->settingsManager->get('headingFontFamily', $this->headingFontFamily);
        $this->fontSize = (int)$this->settingsManager->get('fontSize', $this->fontSize);
        $this->phFontSize = (int)$this->settingsManager->get('phFontSize', $this->phFontSize);
        $this->rtFontSize = (int)$this->settingsManager->get('rtFontSize', $this->rtFontSize);
        $this->h1FontSize = (float)$this->settingsManager->get('h1FontSize', $this->h1FontSize);
        $this->h1RtFontSize = (float)$this->settingsManager->get('h1RtFontSize', $this->h1RtFontSize);
        $this->h2FontSize = (float)$this->settingsManager->get('h2FontSize', $this->h2FontSize);
        $this->h2RtFontSize = (float)$this->settingsManager->get('h2RtFontSize', $this->h2RtFontSize);
        $this->h3FontSize = (float)$this->settingsManager->get('h3FontSize', $this->h3FontSize);
        $this->h4FontSize = (float)$this->settingsManager->get('h4FontSize', $this->h4FontSize);
        $this->h5FontSize = (float)$this->settingsManager->get('h5FontSize', $this->h5FontSize);
        $this->h6FontSize = (float)$this->settingsManager->get('h6FontSize', $this->h6FontSize);
        $this->fontWeight = (int)$this->settingsManager->get('fontWeight', $this->fontWeight);
        $this->phFontWeight = (int)$this->settingsManager->get('phFontWeight', $this->phFontWeight);
        $this->panelBorderWidth = (int)$this->settingsManager->get('panelBorderWidth', $this->panelBorderWidth);
        $this->panelBorderStyle = $this->settingsManager->get('panelBorderStyle', $this->panelBorderStyle);
        $this->panelBorderColor = $this->settingsManager->get('panelBorderColor', $this->panelBorderColor);
        $this->panelBorderRadius = (int)$this->settingsManager->get('panelBorderRadius', $this->panelBorderRadius);
        $this->panelBoxShadow = $this->settingsManager->get('panelBoxShadow', $this->panelBoxShadow);
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        // If empty value, reset to default value (only for attributes which cannot be empty)
        $defaultConfiguration = new Configuration();
        $this->containerMaxWidth = $this->containerMaxWidth ?: $defaultConfiguration->containerMaxWidth;
        $this->topMenuNavJustifyContent = $this->topMenuNavJustifyContent ?: $defaultConfiguration->topMenuNavJustifyContent;
        $this->default = $this->default ?: $defaultConfiguration->default;
        $this->primary = $this->primary ?: $defaultConfiguration->primary;
        $this->info = $this->info ?: $defaultConfiguration->info;
        $this->success = $this->success ?: $defaultConfiguration->success;
        $this->warning = $this->warning ?: $defaultConfiguration->warning;
        $this->danger = $this->danger ?: $defaultConfiguration->danger;
        $this->link = $this->link ?: $defaultConfiguration->link;
        $this->menuBorderColor = $this->menuBorderColor ?: $defaultConfiguration->menuBorderColor;
        $this->textColorHeading = $this->textColorHeading ?: $defaultConfiguration->textColorHeading;
        $this->textColorMain = $this->textColorMain ?: $defaultConfiguration->textColorMain;
        $this->textColorSecondary = $this->textColorSecondary ?: $defaultConfiguration->textColorSecondary;
        $this->textColorHighlight = $this->textColorHighlight ?: $defaultConfiguration->textColorHighlight;
        $this->textColorSoft = $this->textColorSoft ?: $defaultConfiguration->textColorSoft;
        $this->textColorSoft2 = $this->textColorSoft2 ?: $defaultConfiguration->textColorSoft2;
        $this->textColorSoft3 = $this->textColorSoft3 ?: $defaultConfiguration->textColorSoft3;
        $this->textColorContrast = $this->textColorContrast ?: $defaultConfiguration->textColorContrast;
        $this->backgroundColorMain = $this->backgroundColorMain ?: $defaultConfiguration->backgroundColorMain;
        $this->backgroundColorSecondary = $this->backgroundColorSecondary ?: $defaultConfiguration->backgroundColorSecondary;
        $this->backgroundColorPage = $this->backgroundColorPage ?: $defaultConfiguration->backgroundColorPage;
        $this->backgroundColorHighlight = $this->backgroundColorHighlight ?: $defaultConfiguration->backgroundColorHighlight;
        $this->fontSize = $this->fontSize ?: $defaultConfiguration->fontSize;
        $this->phFontSize = $this->phFontSize ?: $defaultConfiguration->phFontSize;
        $this->rtFontSize = $this->rtFontSize ?: $defaultConfiguration->rtFontSize;
        $this->h1FontSize = $this->h1FontSize ?: $defaultConfiguration->h1FontSize;
        $this->h1RtFontSize = $this->h1RtFontSize ?: $defaultConfiguration->h1RtFontSize;
        $this->h2FontSize = $this->h2FontSize ?: $defaultConfiguration->h2FontSize;
        $this->h2RtFontSize = $this->h2RtFontSize ?: $defaultConfiguration->h2RtFontSize;
        $this->h3FontSize = $this->h3FontSize ?: $defaultConfiguration->h3FontSize;
        $this->h4FontSize = $this->h4FontSize ?: $defaultConfiguration->h4FontSize;
        $this->h5FontSize = $this->h5FontSize ?: $defaultConfiguration->h5FontSize;
        $this->h6FontSize = $this->h6FontSize ?: $defaultConfiguration->h6FontSize;
        $this->fontWeight = $this->fontWeight ?: $defaultConfiguration->fontWeight;
        $this->phFontWeight = $this->phFontWeight ?: $defaultConfiguration->phFontWeight;
        $this->panelBorderWidth = $this->panelBorderWidth ?: $defaultConfiguration->panelBorderWidth;
        $this->panelBorderStyle = $this->panelBorderStyle ?: $defaultConfiguration->panelBorderStyle;
        $this->panelBorderColor = $this->panelBorderColor ?: $defaultConfiguration->panelBorderColor;
        $this->panelBorderRadius = $this->panelBorderRadius ?: $defaultConfiguration->panelBorderRadius;

        // Set values
        $this->settingsManager->set('containerMaxWidth', $this->containerMaxWidth);
        $this->settingsManager->set('topMenuNavJustifyContent', $this->topMenuNavJustifyContent);
        $this->settingsManager->set('default', $this->default);
        $this->settingsManager->set('primary', $this->primary);
        $this->settingsManager->set('info', $this->info);
        $this->settingsManager->set('success', $this->success);
        $this->settingsManager->set('warning', $this->warning);
        $this->settingsManager->set('danger', $this->danger);
        $this->settingsManager->set('link', $this->link);
        $this->settingsManager->set('menuBorderColor', $this->menuBorderColor);
        $this->settingsManager->set('textColorHeading', $this->textColorHeading);
        $this->settingsManager->set('textColorMain', $this->textColorMain);
        $this->settingsManager->set('textColorSecondary', $this->textColorSecondary);
        $this->settingsManager->set('textColorHighlight', $this->textColorHighlight);
        $this->settingsManager->set('textColorSoft', $this->textColorSoft);
        $this->settingsManager->set('textColorSoft2', $this->textColorSoft2);
        $this->settingsManager->set('textColorSoft3', $this->textColorSoft3);
        $this->settingsManager->set('textColorContrast', $this->textColorContrast);
        $this->settingsManager->set('backgroundColorMain', $this->backgroundColorMain);
        $this->settingsManager->set('backgroundColorSecondary', $this->backgroundColorSecondary);
        $this->settingsManager->set('backgroundColorPage', $this->backgroundColorPage);
        $this->settingsManager->set('backgroundColorHighlight', $this->backgroundColorHighlight);
        $this->settingsManager->set('fontFamily', $this->fontFamily);
        $this->settingsManager->set('headingFontFamily', $this->headingFontFamily);
        $this->settingsManager->set('fontSize', $this->fontSize);
        $this->settingsManager->set('phFontSize', $this->phFontSize);
        $this->settingsManager->set('rtFontSize', $this->rtFontSize);
        $this->settingsManager->set('h1FontSize', $this->h1FontSize);
        $this->settingsManager->set('h1RtFontSize', $this->h1RtFontSize);
        $this->settingsManager->set('h2FontSize', $this->h2FontSize);
        $this->settingsManager->set('h2RtFontSize', $this->h2RtFontSize);
        $this->settingsManager->set('h3FontSize', $this->h3FontSize);
        $this->settingsManager->set('h4FontSize', $this->h4FontSize);
        $this->settingsManager->set('h5FontSize', $this->h5FontSize);
        $this->settingsManager->set('h6FontSize', $this->h6FontSize);
        $this->settingsManager->set('fontWeight', $this->fontWeight);
        $this->settingsManager->set('phFontWeight', $this->phFontWeight);
        $this->settingsManager->set('panelBorderWidth', $this->panelBorderWidth);
        $this->settingsManager->set('panelBorderStyle', $this->panelBorderStyle);
        $this->settingsManager->set('panelBorderColor', $this->panelBorderColor);
        $this->settingsManager->set('panelBorderRadius', $this->panelBorderRadius);
        $this->settingsManager->set('panelBoxShadow', $this->panelBoxShadow);

        $this->generateDynamicCSSFile();

        return true;
    }

    private function generateDynamicCSSFile(): void
    {
        $css = ':root {' . PHP_EOL;
        foreach (self::CSS_ATTRIBUTE_UNITS as $name => $unit) {
            // Example for `$containerMaxWidth` attribute: `--container-max-width: 1600px;`
            $css .= '    --' . Inflector::camel2id($name) . ': ' . $this->$name . $unit . ';' . PHP_EOL;
        }
        $css .= '}' . PHP_EOL;

        $dynamicCssFile = Yii::getAlias(self::DYNAMIC_CSS_FILE_PATH . self::DYNAMIC_CSS_FILE_NAME);
        file_put_contents($dynamicCssFile, $css);
    }
}
