<?php
/**
 * Clean Theme
 * @link https://github.com/cuzy-app/humhub-modules-clean-theme
 * @license https://github.com/cuzy-app/humhub-modules-clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme;

use Yii;

class Module extends \humhub\components\Module
{

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
}
