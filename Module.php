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
     * @var string defines the icon
     */
    public $icon = 'circle-o-notch';

    /**
     * @var string defines path for resources, including the screenshots path for the marketplace
     */
    public $resourcesPath = 'resources';

    /**
     * @var bool
     */
    public $collapsibleLeftNavigation = false;


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
            if ($theme->name === self::BASE_THEME_NAME) {
                return;
            }
        }

        $theme = ThemeHelper::getThemeByName(self::BASE_THEME_NAME);
        if ($theme !== null) {
            $theme->activate();
            DynamicConfig::rewrite();
        }
    }

    /**
     * @return void
     */
    private function disableTheme()
    {
        foreach (ThemeHelper::getThemeTree(Yii::$app->view->theme) as $theme) {
            if ($theme->name === self::BASE_THEME_NAME) {
                $ceTheme = ThemeHelper::getThemeByName('HumHub');
                $ceTheme->activate();
                break;
            }
        }
    }
}
