<?php

/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

use humhub\components\View;
use humhub\modules\cleanTheme\Events;

return [
    'id' => 'clean-theme',
    'class' => humhub\modules\cleanTheme\Module::class,
    'namespace' => 'humhub\modules\cleanTheme',
    'urlManagerRules' => [
        'clean-theme-config.css' => 'clean-theme/css/index',
    ],
    'events' => [
        [
            'class' => View::class,
            'event' => View::EVENT_BEFORE_RENDER,
            'callback' => [Events::class, 'onViewBeforeRender'],
        ],
        [
            'class' => View::class,
            'event' => View::EVENT_BEGIN_BODY,
            'callback' => [Events::class, 'onViewBeginBody'],
        ],
    ],
];
