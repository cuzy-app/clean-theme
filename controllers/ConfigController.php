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
use humhub\modules\cleanTheme\models\UploadConfiguration;
use humhub\modules\cleanTheme\Module;
use Yii;
use yii\helpers\Json;

/**
 * Module configuation
 */
class ConfigController extends Controller
{
    public function actionIndex()
    {
        /** @var Module $module */
        $module = $this->module;
        $model = $module->configuration;
        $uploadModel = new UploadConfiguration();
        $post = Yii::$app->request->post();

        if (isset($post['UploadConfiguration'])) {
            if ($uploadModel->load($post) && $uploadModel->validate() && $uploadModel->save()) {
                $this->view->saved();
                return $this->refresh();
            }
            $this->view->error('Could not upload the configuration: ' . Json::encode($uploadModel->getErrors()));
        }

        if (isset($post['Configuration']) && $model->load($post) && $model->validate() && $model->save()) {
            $this->view->saved();
            return $this->refresh();
        }

        if ($post['reset'] ?? false) {
            $defaultConfiguration = new Configuration();
            foreach (Configuration::getAllAttributeNames() as $attributeName) {
                $model->$attributeName = $defaultConfiguration->$attributeName;
            }
            if ($model->save()) {
                $this->view->success(Yii::t('CleanThemeModule.config', 'Reset successful!'));
            } else {
                $this->view->error('Reset failed! ' . Json::encode($model->getErrors()));
            }
            return $this->refresh();
        }

        return $this->render('index', [
            'model' => $model,
            'uploadModel' => $uploadModel,
        ]);
    }

    public function actionDownloadJson()
    {
        /** @var Module $module */
        $module = $this->module;
        $configuration = $module->getConfiguration();

        $configurationArray = [];
        foreach (Configuration::getAllAttributeNames() as $attributeName) {
            $configurationArray[$attributeName] = $configuration->$attributeName;
        }
        return Yii::$app->response->sendContentAsFile(
            Json::encode($configurationArray),
            'humhub-clean-theme-configuration.json',
            [
                'mimeType' => 'application/json',
                'inline' => false,
            ],
        );
    }
}
