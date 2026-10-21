<?php

/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\controllers;

use humhub\components\access\ControllerAccess;
use humhub\components\Controller;
use humhub\modules\cleanTheme\Module;
use Yii;
use yii\web\Response;

/**
 * Serves the CSS variables generated from the theme configuration (see `Configuration::getCss()`)
 * at the `clean-theme-config.css` URL (see `urlManagerRules` in `config.php`).
 *
 * @property Module $module
 * @since 2.5.1
 */
class CssController extends Controller
{
    /**
     * Allow guest access independently from guest mode setting.
     *
     * @var string
     */
    public $access = ControllerAccess::class;

    public function actionIndex()
    {
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_RAW;
        $response->headers->set('Content-Type', 'text/css; charset=UTF-8');
        // The URL contains a cache buster (see `Configuration::getCssUrl()`), so browsers can cache the file for a long time
        $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');

        return $this->module->configuration->getCss();
    }
}
