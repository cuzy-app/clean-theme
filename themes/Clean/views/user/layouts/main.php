<?php

use humhub\modules\cleanTheme\assets\CleanThemeAsset;
use humhub\modules\ui\view\components\View;

/* @var $this View */
/* @var $content string */

CleanThemeAsset::register($this);

require Yii::$app->getModule('user')->viewPath . '/layouts/main.php';
