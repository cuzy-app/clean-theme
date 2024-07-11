<?php
/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme;


use humhub\components\console\Application;
use humhub\modules\cleanTheme\commands\DeveloperController;

class Events
{
    public static function onConsoleApplicationInit($event)
    {
        /** @var Application $application */
        $application = $event->sender;
        $application->controllerMap['clean-theme'] = DeveloperController::class;
    }
}
