<?php
/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\controllers;

use humhub\modules\admin\components\Controller;
use humhub\modules\cleanTheme\Module;
use Yii;

/**
 * Module configuation
 */
class ConfigController extends Controller
{
    /**
     * Render admin only page
     *
     * @return string
     */
    public function actionIndex()
    {
        /** @var Module $module */
        $module = $this->module;
        $model = $module->getConfiguration();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->view->saved();
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }
}
