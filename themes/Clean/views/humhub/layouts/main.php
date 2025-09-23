<?php
/* @var $this View */

/* @var $content string */

use humhub\assets\AppAsset;
use humhub\components\View;
use humhub\helpers\DeviceDetectorHelper;
use humhub\helpers\Html;
use humhub\modules\cleanTheme\models\Configuration;
use humhub\modules\cleanTheme\Module;
use humhub\modules\space\widgets\Chooser;
use humhub\modules\user\widgets\AccountTopMenu;
use humhub\widgets\NotificationArea;
use humhub\widgets\SiteLogo;
use humhub\widgets\TopMenu;
use humhub\widgets\TopMenuRightStack;

/** @var Module $module */
$module = Yii::$app->getModule('clean-theme');
$googleFontsCss2UrlParams = $module?->configuration->getGoogleFontsCss2UrlParams();

AppAsset::register($this);

$bodyClasses = DeviceDetectorHelper::getBodyClasses();
$bodyClasses[] = 'clean-theme';
$bodyClasses[] = 'hh-ct-menu-style-' . ($module?->configuration->menuStyle ?? Configuration::MENU_STYLE_BACKGROUND);
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
            <link rel="stylesheet" href="https://fonts.googleapis.com/css2?<?= $googleFontsCss2UrlParams ?>:wght@100;200;300;400;500;600;700;800;900&display=swap"> <?php // Don't replace with :wght@100..900 because some fonts such as Quicksand won't load because of the missing font weight?>
        <?php endif; ?>
    </head>

    <?= Html::beginTag('body', ['class' => $bodyClasses]) ?>
    <?php $this->beginBody() ?>

        <!-- start: top navigation bar -->
        <div id="topbar" class="topbar navbar">
            <div class="container flex-nowrap">
                <div class="topbar-brand d-flex text-nowrap overflow-hidden">
                    <?= SiteLogo::widget() ?>
                </div>

                <ul id="top-menu-nav" class="flex-grow-1 nav<?= $module?->configuration->hideTextInBottomMenuItems ? ' hide-menu-item-texts' : '' ?>">
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
