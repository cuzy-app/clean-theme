<?php

/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.app](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\tests\codeception\unit;

use humhub\components\gates\GateManager;
use humhub\modules\cleanTheme\controllers\CssController;
use Yii;

/**
 * The stylesheet of the CSS variables generated from the configuration, which every page loads
 */
class CssStylesheetTest extends CleanThemeTestCase
{
    public function testIsNotInterceptedByUserGates(): void
    {
        // The event handler registered in `config.php` is called when the gates are collected
        $gateManager = new GateManager();

        $this->assertTrue($gateManager->isInfrastructureRoute('clean-theme/css/index'));
        $this->assertFalse($gateManager->isInfrastructureRoute('clean-theme/config/index'), 'the configuration page is not');
    }

    public function testIsServedAsCss(): void
    {
        /** @var CssController $controller */
        [$controller] = Yii::$app->createController('clean-theme/css');
        $format = Yii::$app->response->format;
        try {
            $css = $controller->actionIndex();
            $this->assertSame('text/css; charset=UTF-8', Yii::$app->response->headers->get('Content-Type'), 'browsers ignore a stylesheet of another type');
        } finally {
            Yii::$app->response->clear();
            Yii::$app->response->format = $format;
        }

        $this->assertSame($this->getCleanThemeModule()->configuration->getCss(), $css);
    }
}
