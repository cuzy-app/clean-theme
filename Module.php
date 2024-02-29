<?php
/**
 * Clean Theme
 * @link https://github.com/cuzy-app/humhub-modules-clean-theme
 * @license https://github.com/cuzy-app/humhub-modules-clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme;

use humhub\libs\DynamicConfig;
use humhub\modules\ui\view\helpers\ThemeHelper;
use Yii;
use yii\helpers\Url;

class Module extends \humhub\components\Module
{
    public const BASE_THEME_NAME = 'clean-base';

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
        $baseTheme = ThemeHelper::getThemeByName(self::BASE_THEME_NAME);
        if ($baseTheme !== null) {
            $ceTheme = ThemeHelper::getThemeByName('HumHub');
            if ($ceTheme !== null) {
                $ceTheme->activate();
            } else {
                Yii::error('Failed to activate theme: HumHub', 'ui');
            }
        } else {
            Yii::error('Failed to find base theme: ' . self::BASE_THEME_NAME, 'ui');
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
        $baseTheme = ThemeHelper::getThemeByName(self::BASE_THEME_NAME);
        if ($baseTheme === null) {
            Yii::error('Failed to find base theme: ' . self::BASE_THEME_NAME, 'ui');
            return;
        }

        // Check if already active
        $activeThemes = ThemeHelper::getThemeTree(Yii::$app->view->theme);
        foreach ($activeThemes as $theme) {
            if ($theme->name === self::BASE_THEME_NAME) {
                return;
            }
        }

        // Activate base theme
        $baseTheme->activate();
        DynamicConfig::rewrite();
    }
}
