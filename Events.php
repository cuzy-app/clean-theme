<?php

/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme;

use humhub\assets\CoreBundleAsset;
use humhub\assets\TopNavigationAsset;
use humhub\components\gates\GateInitEvent;
use humhub\components\View;
use humhub\modules\cleanTheme\assets\CleanThemeAsset;
use humhub\modules\cleanTheme\assets\CleanThemeTopNavigationAsset;
use Yii;

class Events
{
    public static function onViewBeforeRender($event)
    {
        if (Yii::$app->request->isAjax ?? false) {
            return;
        }

        /** @var View $view */
        $view = $event->sender;

        $module = static::getModuleIfThemeActive();
        if (!$module) {
            return;
        }

        // Unregister the core TopNavigationAsset to prevent conflicts with the Clean Theme
        unset($view->assetBundles[TopNavigationAsset::class]);
    }

    public static function onViewBeginBody($event)
    {
        if (Yii::$app->request->isAjax) {
            return;
        }

        $module = static::getModuleIfThemeActive();
        if (!$module) {
            return;
        }

        /** @var View $view */
        $view = $event->sender;

        // Register the CSS variables generated from the configuration, after the theme CSS so that they overwrite the core defaults
        $view->registerCssFile($module->configuration->getCssUrl(), ['depends' => CoreBundleAsset::class]);

        // Register the Clean Theme Assets instead
        CleanThemeAsset::register($view);
        CleanThemeTopNavigationAsset::register($view);
        $view->registerJsConfig('cleanTheme.topNavigation', [
            'hideTopMenuOnScrollDown' => $module?->configuration->hideTopMenuOnScrollDown ?? false,
            'hideBottomMenuOnScrollDown' => $module?->configuration->hideBottomMenuOnScrollDown ?? false,
        ]);
    }

    /**
     * The generated CSS variables (`CssController`) are a stylesheet every page depends on to
     * render, the page of an open user gate included (2FA check, terms, forced password change…).
     * Declared as a gate infrastructure route, so that no gate intercepts the request: the
     * browser fetches a stylesheet without `Accept: text/html`, and an intercepting gate would
     * answer with a 403 and leave the gate page without its theme configuration.
     * The controller delivers no user content.
     *
     * @see https://github.com/humhub/humhub/blob/develop/docs/develop/user-gates.md
     */
    public static function onGateInit(GateInitEvent $event): void
    {
        $event->manager->registerInfrastructureRoute('clean-theme/css');
    }

    protected static function getModuleIfThemeActive(): ?Module
    {
        static $cachedModule = false;
        if ($cachedModule !== false) {
            return $cachedModule ?: null;
        }

        if (!Module::isThemeBasedActive()) {
            $cachedModule = null;
            return null;
        }

        /** @var Module $module */
        $module = Yii::$app->getModule('clean-theme');
        if (!$module?->isEnabled) {
            $cachedModule = null;
            return null;
        }

        $cachedModule = $module;
        return $module;
    }
}
