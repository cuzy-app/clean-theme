<?php
/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\assets;

use humhub\components\assets\AssetBundle;


class CleanThemeTopNavigationAsset extends AssetBundle
{
    public $sourcePath = '@clean-theme/resources';

    public $css = [
    ];

    public $js = [
        'js/humhub.clean.theme.topNavigation.js',
    ];
}
