<?php
/* @var $this View */

/* @var $content string */

use humhub\assets\AppAsset;
use humhub\helpers\DeviceDetectorHelper;
use humhub\libs\Html;
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
$googleFontsCss2UrlParams = $module->configuration->getGoogleFontsCss2UrlParams();

AppAsset::register($this);
CleanThemeAsset::register($this);
CleanThemeTopNavigationAsset::register($this);
$this->registerJsConfig('cleanTheme.topNavigation', [
    'hideTopMenuOnScrollDown' => $module->configuration->hideTopMenuOnScrollDown,
    'hideBottomMenuOnScrollDown' => $module->configuration->hideBottomMenuOnScrollDown,
    'screenXsMin' => $this->theme->variable('screen-xs-min'),
]);

$bodyClasses = DeviceDetectorHelper::getBodyClasses();
$bodyClasses[] = 'clean-theme';
$bodyClasses[] = 'hh-ct-menu-style-' . $module->configuration->menuStyle;
if (Yii::$app->user->isGuest) {
    $bodyClasses[] = 'hh-ct-is-guest';
}
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <title><?= strip_tags($this->pageTitle) ?></title>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <?php $this->head() ?>
        <?= $this->render('head') ?>

        <?php if ($googleFontsCss2UrlParams) : ?>
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css2?<?= $googleFontsCss2UrlParams ?>:wght@100..900&display=swap">
        <?php endif; ?>
    </head>

    <?= Html::beginTag('body', ['class' => $bodyClasses]) ?>
    <?php $this->beginBody() ?>

        <!-- start: top navigation bar -->
        <div id="topbar" class="topbar navbar">
            <div class="container">
                <div class="topbar-brand hidden-xs">
                    <?= SiteLogo::widget() ?>
                </div>

                <ul id="top-menu-nav"
                    class="nav<?= $module->configuration->hideTextInBottomMenuItems ? ' hide-menu-item-texts' : '' ?>">
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
    <?= Html::endTag('body') ?>
</html>
<?php $this->endPage() ?>
