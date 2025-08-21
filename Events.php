<?php

/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme;

use humhub\assets\TopNavigationAsset;
use humhub\components\console\Application;
use humhub\components\View;
use humhub\modules\cleanTheme\assets\CleanThemeAsset;
use humhub\modules\cleanTheme\assets\CleanThemeTopNavigationAsset;
use humhub\modules\cleanTheme\commands\DeveloperController;
use Yii;

class Events
{
    public static function onConsoleApplicationInit($event)
    {
        /** @var Application $application */
        $application = $event->sender;
        $application->controllerMap['clean-theme'] = DeveloperController::class;
    }

    public static function onViewBeforeRender($event)
    {
        if (!Module::isThemeBasedActive()) {
            return;
        }

        /** @var View $view */
        $view = $event->sender;

        /** @var Module $module */
        $module = Yii::$app->getModule('clean-theme');

        // Unregister the core TopNavigationAsset to prevent conflicts with the Clean Theme
        unset($view->assetBundles[TopNavigationAsset::class]);

        // Register the Clean Theme Assets instead
        CleanThemeAsset::register($view);
        CleanThemeTopNavigationAsset::register($view);
        $view->registerJsConfig('cleanTheme.topNavigation', [
            'hideTopMenuOnScrollDown' => $module?->configuration->hideTopMenuOnScrollDown ?? false,
            'hideBottomMenuOnScrollDown' => $module?->configuration->hideBottomMenuOnScrollDown ?? false,
        ]);
    }
}
