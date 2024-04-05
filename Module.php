<?php
/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme;

use humhub\libs\DynamicConfig;
use humhub\modules\ui\view\helpers\ThemeHelper;
use Yii;
use yii\helpers\Url;

class Module extends \humhub\components\Module
{
    public const THEME_NAMES = [
        'clean-base',
        'clean-bordered',
        'clean-contrasted',
    ];

    /**
     * @inheridoc
     */
    public string $icon = 'circle-o-notch';

    /**
     * @inheridoc
     */
    public $resourcesPath = 'resources';

    public bool $hideTopMenuOnScrollDown = true; // On small screens only
    public bool $hideBottomMenuOnScrollDown = true; // On small screens only
    public bool $hideTextInBottomMenuItems = true; // On small screens only
    public bool $collapsibleLeftNavigation = false;


    public function getName()
    {
        return Yii::t('CleanThemeModule.config', 'Clean theme');
    }

    public function getDescription()
    {
        return Yii::t('CleanThemeModule.config', 'Clean theme for Humhub based on the Community theme');
    }

    /**
     * @inheritdoc
     */
    public function getConfigUrl()
    {
        return Url::to(['/clean-theme/config']);
    }

    /**
     * @inheritdoc
     */
    public function disable()
    {
        $this->disableTheme();
        parent::disable();
    }

    /**
     * @return void
     */
    private function disableTheme()
    {
        foreach (ThemeHelper::getThemeTree(Yii::$app->view->theme) as $theme) {
            if (in_array($theme->name, self::THEME_NAMES, true)) {
                $ceTheme = ThemeHelper::getThemeByName('HumHub');
                $ceTheme->activate();
                break;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function enable()
    {
        if (parent::enable()) {
            $this->enableTheme();
            return true;
        }
        return false;
    }

    /**
     * @return void
     */
    private function enableTheme()
    {
        // Check if already active
        foreach (ThemeHelper::getThemeTree(Yii::$app->view->theme) as $theme) {
            if (in_array($theme->name, self::THEME_NAMES, true)) {
                return;
            }
        }

        $theme = ThemeHelper::getThemeByName($this->getBaseThemeName());
        if ($theme !== null) {
            $theme->activate();
            DynamicConfig::rewrite();
        }
    }

    /**
     * The base theme is the first of the list
     */
    public function getBaseThemeName(): string
    {
        return self::THEME_NAMES[0];
    }
}
