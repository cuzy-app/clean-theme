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
use yii\base\Exception;
use yii\helpers\Url;

/**
 * Module class for Clean Theme module.
 *
 * This module provides a clean theme for HumHub based on the Community theme.
 */
class Module extends \humhub\components\Module
{
    /**
     * @var array The name of the clean themes.
     */
    public const THEME_NAMES = [
        'clean-base',
        'clean-bordered',
        'clean-contrasted',
    ];

    /**
     * @var string The icon for the module.
     */
    public string $icon = 'circle-o-notch';

    /**
     * @var string The path to the module's resources.
     */
    public $resourcesPath = 'resources';

    /**
     * @var bool Whether to hide the top menu on scroll down (on small screens only).
     */
    public bool $hideTopMenuOnScrollDown = true;

    /**
     * @var bool Whether to hide the bottom menu on scroll down (on small screens only).
     */
    public bool $hideBottomMenuOnScrollDown = true;

    /**
     * @var bool Whether to hide text in bottom menu items (on small screens only).
     */
    public bool $hideTextInBottomMenuItems = true;

    /**
     * @var bool Whether to make the left navigation collapsible.
     */
    public bool $collapsibleLeftNavigation = false;

    /**
     * Returns the name of the module.
     *
     * @return string The module name.
     */
    public function getName()
    {
        return Yii::t('CleanThemeModule.config', 'Clean theme');
    }

    /**
     * Returns the description of the module.
     *
     * @return string The module description.
     */
    public function getDescription()
    {
        return Yii::t('CleanThemeModule.config', 'Clean theme for Humhub based on the Community theme');
    }

    /**
     * Returns the URL for configuring the module.
     *
     * @return string The configuration URL.
     */
    public function getConfigUrl()
    {
        return Url::to(['/clean-theme/config']);
    }

    /**
     * Disables the module.
     */
    public function disable()
    {
        $this->disableTheme();
        parent::disable();
    }

    /**
     * Enables the module.
     *
     * @return bool Whether the module was successfully enabled.
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
     * Enables the clean theme.
     *
     * @throws Exception if an error occurs while enabling the theme.
     */
    private function enableTheme()
    {
        try {
            foreach (ThemeHelper::getThemeTree(Yii::$app->view->theme) as $theme) {
                if ($theme->name === self::THEME_NAMES) return;
            }

            $theme = ThemeHelper::getThemeByName(self::THEME_NAMES);
            if ($theme !== null) {
                $theme->activate();
                $this->updateDynamicConfig();
            }
        } catch (Exception $e) {
            Yii::error('Error enabling theme: ' . $e->getMessage(), 'clean-theme');
            throw $e;
        }
    }

    /**
     * Disables the clean theme.
     */
    private function disableTheme()
    {
        foreach (ThemeHelper::getThemeTree(Yii::$app->view->theme) as $theme) {
            if ($theme->name === self::THEME_NAMES) {
                $ceTheme = ThemeHelper::getThemeByName('HumHub');
                $ceTheme->activate();
                break;
            }
        }
    }

    /**
     * Updates the dynamic configuration after enabling the theme.
     *
     * @throws Exception if an error occurs while updating the dynamic configuration.
     */
    private function updateDynamicConfig()
    {
        try {
            $config = DynamicConfig::load();
            $config['theme'] = self::THEME_NAMES;
            DynamicConfig::save($config);
        } catch (Exception $e) {
            Yii::error('Error updating dynamic config: ' . $e->getMessage(), 'clean-theme');
            throw $e;
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
