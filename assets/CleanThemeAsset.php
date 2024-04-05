<?php
/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\assets;

use humhub\components\assets\AssetBundle;
use yii\web\View;


class CleanThemeAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $jsOptions = ['position' => View::POS_END];

    /**
     * @inheritdoc
     */
    public $sourcePath = '@clean-theme/resources';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/humhub.clean.theme.js'
    ];
}
