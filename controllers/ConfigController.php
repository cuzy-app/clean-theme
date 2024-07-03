<?php
/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\controllers;

use humhub\modules\admin\components\Controller;
use humhub\modules\cleanTheme\models\Configuration;
use humhub\modules\cleanTheme\Module;
use Yii;

/**
 * Module configuation
 */
class ConfigController extends Controller
{
    public function actionIndex()
    {
        /** @var Module $module */
        $module = $this->module;
        $model = $module->getConfiguration();

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            $this->view->saved();
            return $this->refresh();
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }

    /**
     * @return void
     */
    public function actionResetAllToDefault()
    {
        /** @var Module $module */
        $module = $this->module;
        $configuration = $module->getConfiguration();

        foreach (Configuration::CSS_ATTRIBUTE_UNITS as $name => $unit) {
            $configuration->$name = '';
        }
        $configuration->save();

        $this->view->success(Yii::t('CleanThemeModule.config', 'Reset successful!'));
        return $this->render('reset-all-to-default'); // Refresh all page an redirect to index
    }
}
