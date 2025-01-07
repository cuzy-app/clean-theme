<?php

/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\commands;

use humhub\modules\cleanTheme\Module;
use Yii;
use yii\base\Exception;
use yii\console\Controller;
use yii\console\ExitCode;

class DeveloperController extends Controller
{
    /**
     * Generate dynamic CSS file
     *
     * Usage: php yii clean-theme/generate-dynamic-css-file
     *
     * Can be used after installing the module by cloning the GitHub repository
     */
    public function actionGenerateDynamicCssFile()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('clean-theme');
        try {
            $module->configuration->generateDynamicCSSFile();
        } catch (Exception $e) {
            $this->message('Could not generate dynamic CSS file: ' . $e->getMessage(), 'error');
            return ExitCode::UNSPECIFIED_ERROR;
        }
        $this->message("\nSuccessfully generated dynamic CSS file", 'success');
        return ExitCode::OK;
    }
}
