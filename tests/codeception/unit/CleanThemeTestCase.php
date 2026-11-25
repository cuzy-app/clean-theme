<?php

/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.app](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\tests\codeception\unit;

use humhub\modules\cleanTheme\models\Configuration;
use humhub\modules\cleanTheme\Module;
use humhub\modules\cleanTheme\services\GoogleFontsService;
use tests\codeception\_support\HumHubDbTestCase;
use Yii;

/**
 * Base class of the module tests. The Clean Theme module is enabled in the test database, but its theme is not the
 * active one: no test activates it, as it would build the theme CSS.
 */
abstract class CleanThemeTestCase extends HumHubDbTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // The module configuration object lives on the module instance, which outlives a test
        $this->getCleanThemeModule()->configuration->loadBySettings();
    }

    protected function tearDown(): void
    {
        // Downloaded fonts written by a test, in the test mounts (`uploads/tests` and `assets/tests`)
        (new GoogleFontsService($this->newConfiguration()))->deleteFiles();
        $this->getCleanThemeModule()->configuration->loadBySettings();

        parent::tearDown();
    }

    protected function getCleanThemeModule(): Module
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('clean-theme');
        return $module;
    }

    /**
     * Configuration loaded from the module settings, independent of the instance kept by the module
     */
    protected function newConfiguration(): Configuration
    {
        $configuration = new Configuration(['settingsManager' => $this->getCleanThemeModule()->settings]);
        $configuration->loadBySettings();
        return $configuration;
    }
}
