<?php

/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.app](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\tests\codeception\unit;

use humhub\helpers\ThemeHelper;
use humhub\models\ModuleEnabled;
use humhub\modules\cleanTheme\Module;
use Yii;
use yii\base\UserException;

class ModuleTest extends CleanThemeTestCase
{
    public function testCannotBeDisabledWhileCleanThemeProIsEnabled(): void
    {
        if (!in_array('clean-theme-pro', ModuleEnabled::getEnabledIds(), true)) {
            $this->markTestSkipped('Clean Theme Pro is not enabled in the test database');
        }
        $module = $this->getCleanThemeModule();
        $module->settings->set('linkColor', '#123456');

        try {
            $module->disable();
            $this->fail('disabling must be refused');
        } catch (UserException $e) {
            $this->assertStringContainsString('Clean Theme Pro', $e->getMessage());
        }

        $this->assertContains('clean-theme', ModuleEnabled::getEnabledIds());
        $this->assertSame('#123456', $module->settings->get('linkColor'), 'refused before anything is removed');
    }

    public function testThemeDetectionIncludesTheChildThemes(): void
    {
        $theme = Yii::$app->view->theme;
        try {
            Yii::$app->view->theme = ThemeHelper::getThemeByName(Module::THEME_NAME);
            $this->assertTrue(Module::isThemeBasedActive());

            Yii::$app->view->theme = ThemeHelper::getThemeByName('HumHub');
            $this->assertFalse(Module::isThemeBasedActive());

            $childTheme = ThemeHelper::getThemeByName('CleanPro');
            if ($childTheme === null) {
                $this->markTestIncomplete('The CleanPro child theme (Clean Theme Pro module) is not installed');
            }
            Yii::$app->view->theme = $childTheme;
            $this->assertTrue(Module::isThemeBasedActive(), 'a child theme of the Clean theme');
        } finally {
            Yii::$app->view->theme = $theme;
        }
    }
}
