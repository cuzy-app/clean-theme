<?php

/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

use humhub\components\console\Application;
use humhub\components\View;
use humhub\modules\cleanTheme\Events;

return [
    'id' => 'clean-theme',
    'class' => humhub\modules\cleanTheme\Module::class,
    'namespace' => 'humhub\modules\cleanTheme',
    'events' => [
        [
            'class' => Application::class,
            'event' => Application::EVENT_ON_INIT,
            'callback' => Events::onConsoleApplicationInit(...),
        ],
        [
            'class' => View::class,
            'event' => View::EVENT_BEFORE_RENDER,
            'callback' => Events::onViewBeforeRender(...),
        ],
        [
            'class' => View::class,
            'event' => View::EVENT_BEGIN_BODY,
            'callback' => Events::onViewBeginBody(...),
        ],
    ],
];
