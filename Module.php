<?php
/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme;

use humhub\libs\DynamicConfig;
use humhub\modules\cleanTheme\models\Configuration;
use humhub\modules\ui\view\helpers\ThemeHelper;
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
    public $resourcesPath = 'resources';

    public bool $hideTopMenuOnScrollDown = true; // On small screens only
    public bool $hideBottomMenuOnScrollDown = true; // On small screens only
    public bool $hideTextInBottomMenuItems = true; // On small screens only
    public bool $collapsibleLeftNavigation = false;
    private ?Configuration $_configuration = null;

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
        return Yii::t('CleanThemeModule.config', '"{Clean}" theme based on the community "{HumHub}" theme', [
            'Clean' => 'Clean',
            'CommunityHumHub' => 'HumHub',
        ]);
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
            try {
                $this->configuration->generateDynamicCSSFile();
            } catch (Exception $e) {
                Yii::error('Could not generate dynamic CSS file: ' . $e->getMessage(), 'clean-theme');
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

        // Recreate dynamic CSS file because it was removed by module update
        try {
            $this->configuration->generateDynamicCSSFile();
        } catch (Exception $e) {
            Yii::error('Could not generate dynamic CSS file: ' . $e->getMessage(), 'clean-theme');
        }
    }

    /**
     * @return void
     */
    private function disableTheme()
    {
        foreach (ThemeHelper::getThemeTree(Yii::$app->view->theme) as $theme) {
            if ($theme->name === 'Clean') {
                $ceTheme = ThemeHelper::getThemeByName('HumHub');
                $ceTheme->activate();
                break;
            }
        }
    }

    /**
     * @return void
     */
    private function enableTheme()
    {
        // Check if already active
        foreach (ThemeHelper::getThemeTree(Yii::$app->view->theme) as $theme) {
            if ($theme->name === 'Clean') {
                return;
            }
        }

        $cleanTheme = ThemeHelper::getThemeByName('Clean');
        if ($cleanTheme !== null) {
            $cleanTheme->activate();
            DynamicConfig::rewrite();
        }
    }
}
