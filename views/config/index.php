<?php
/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

/* @var $this View */

use humhub\modules\cleanTheme\Module;
use humhub\modules\ui\view\components\View;
use humhub\widgets\Button;

/** @var Module $module */
$module = Yii::$app->getModule('clean-theme');
?>
<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong><?= $module->getName() ?></strong>
            <div class="help-block"><?= $module->getDescription() ?></div>
        </div>

        <div class="panel-body">
            <div class="alert alert-info">
                This module was created and is maintained by
                <a href="https://www.cuzy.app/"
                   target="_blank">CUZY.APP (view other modules)</a>.
                <br>
                It's free, but it's the result of a lot of design and maintenance work over time.
                <br>
                If it's useful to you, please consider
                <a href="https://www.cuzy.app/checkout/donate/"
                   target="_blank">making a donation</a>
                or
                <a href="https://github.com/cuzy-app/clean-theme"
                   target="_blank">participating in the code</a>.
                Thanks!
            </div>

            <div>
                <?= Button::primary('Choose the theme')->link(['/admin/setting/design']) ?>
            </div>
        </div>
    </div>
</div>
