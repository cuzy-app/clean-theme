<?php
/* @var $this View */

/* @var $content string */

use humhub\assets\AppAsset;
use humhub\modules\cleanTheme\assets\CleanThemeAsset;
use humhub\modules\cleanTheme\assets\CleanThemeTopNavigationAsset;
use humhub\modules\cleanTheme\Module;
use humhub\modules\space\widgets\Chooser;
use humhub\modules\ui\view\components\View;
use humhub\modules\user\widgets\AccountTopMenu;
use humhub\widgets\NotificationArea;
use humhub\widgets\SiteLogo;
use humhub\widgets\TopMenu;
use humhub\widgets\TopMenuRightStack;

/** @var Module $module */
$module = Yii::$app->getModule('clean-theme');

AppAsset::register($this);
CleanThemeAsset::register($this);
CleanThemeTopNavigationAsset::register($this);
$this->registerJsConfig('cleanTheme.topNavigation', [
    'hideTopMenuOnScrollDown' => $module->hideTopMenuOnScrollDown,
    'hideBottomMenuOnScrollDown' => $module->hideBottomMenuOnScrollDown,
]);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <title><?= strip_tags($this->pageTitle) ?></title>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <?php $this->head() ?>
    <?= $this->render('head') ?>
</head>
<body>
<?php $this->beginBody() ?>

<!-- start: top navigation bar -->
<div id="topbar" class="topbar">
    <div class="container">
        <div class="topbar-brand hidden-xs">
            <?= SiteLogo::widget() ?>
        </div>

        <ul class="nav" id="top-menu-nav">
            <!-- load space chooser widget -->
            <?= Chooser::widget() ?>

            <!-- load navigation from widget -->
            <?= TopMenu::widget() ?>
        </ul>

        <ul class="nav" id="search-menu-nav">
            <?= TopMenuRightStack::widget() ?>
        </ul>

        <div class="notifications">
            <?= NotificationArea::widget() ?>
        </div>

        <div class="topbar-actions">
            <?= AccountTopMenu::widget() ?>
        </div>
    </div>
</div>
<!-- end: top navigation bar -->

<?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
