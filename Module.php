<?php

/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme;

use humhub\helpers\ThemeHelper;
use humhub\modules\cleanTheme\models\Configuration;
use Yii;
use yii\base\Exception;
use yii\helpers\Url;

/**
 *
 * @property-read mixed $configUrl
 * @property-read Configuration $configuration
 */
class Module extends \humhub\components\Module
{
    /**
     * @inheridoc
     */
    public string $icon = 'circle-o-notch';
    /**
     * @inheridoc
     */
    public bool $collapsibleLeftNavigation = false;
    private ?Configuration $_configuration = null;

    public const THEME_NAME = 'Clean';

    public function getConfiguration(): Configuration
    {
        if ($this->_configuration === null) {
            $this->_configuration = new Configuration(['settingsManager' => $this->settings]);
            $this->_configuration->loadBySettings();
        }
        return $this->_configuration;
    }

    public function getName()
    {
        return 'Clean Theme';
    }

    public function getDescription()
    {
        return Yii::t('CleanThemeModule.config', 'Modern, smooth and uncluttered theme');
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
        if (parent::enable() !== false) {
            try {
                $this->configuration->generateScssRootFile();
            } catch (Exception $e) {
                Yii::error('Could not generate SCSS root file: ' . $e->getMessage(), 'clean-theme');
                return false;
            }
            $this->enableTheme();
            return true;
        }
        return false;
    }

    public function update()
    {
        parent::update();

        // Recreate SCSS root file because it was removed by module update
        try {
            $this->configuration->generateScssRootFile();
        } catch (Exception $e) {
            Yii::error('Could not generate SCSS root file: ' . $e->getMessage(), 'clean-theme');
        }

        // Rebuild CSS: TODO remove for 1.18-beta-4+
        try {
            ThemeHelper::buildCss();
        } catch (Exception $e) {
            Yii::error('Could not build CSS: ' . $e->getMessage(), 'clean-theme');
        }
    }

    /**
     * @return void
     */
    private function disableTheme()
    {
        if (static::isThemeBasedActive()) {
            $ceTheme = ThemeHelper::getThemeByName('HumHub');
            $ceTheme?->activate();
        }
    }

    /**
     * @return void
     */
    private function enableTheme()
    {
        if (!static::isThemeBasedActive()) {
            $cleanTheme = ThemeHelper::getThemeByName(self::THEME_NAME);
            $cleanTheme?->activate();
        }
    }

    /**
     * Checks if the Clean Theme, or a child theme of it, is currently active
     */
    public static function isThemeBasedActive(): bool
    {
        foreach (ThemeHelper::getThemeTree(Yii::$app->view->theme) as $theme) {
            if ($theme->name === self::THEME_NAME) {
                return true;
            }
        }
        return false;
    }
}
