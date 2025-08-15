<?php

use humhub\components\View;
use humhub\modules\cleanTheme\assets\CleanThemeAsset;

/* @var $this View */
/* @var $content string */

CleanThemeAsset::register($this);

require Yii::$app->getModule('user')->viewPath . '/layouts/main.php';
