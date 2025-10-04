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
use yii\helpers\BaseConsole;

class DeveloperController extends Controller
{
    /**
     * Generate SCSS root file
     *
     * Usage: php yii clean-theme/generate-scss-root-file
     *
     * Can be used after installing the module by cloning the GitHub repository
     */
    public function actionGenerateScssRootFile()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('clean-theme');
        try {
            $module->configuration->generateScssRootFile();
        } catch (Exception $e) {
            $this->message('Could not generate SCSS root file: ' . $e->getMessage(), 'error');
            return ExitCode::UNSPECIFIED_ERROR;
        }
        $this->stdout("\nSuccessfully generated SCSS root file.\n", BaseConsole::FG_GREEN);
        return ExitCode::OK;
    }
}
