<?php

use humhub\components\View;
use humhub\helpers\Html;
use humhub\modules\cleanTheme\assets\CleanThemeLeftNavigationAsset;
use humhub\modules\cleanTheme\Module;
use humhub\modules\ui\menu\MenuEntry;
use humhub\modules\ui\menu\widgets\LeftNavigation;
use humhub\widgets\bootstrap\Button;

/* @var $this View */
/* @var $menu LeftNavigation */
/* @var $entries MenuEntry[] */
/* @var $options [] */

$collapseBtn = 'left-navigation-collapse-btn';
$expandBtn = 'left-navigation-expand-btn';

CleanThemeLeftNavigationAsset::register($this);
$this->registerJsConfig('cleanTheme.leftNavigation', [
    'menuId' => $options['id'],
    'collapseBtn' => $collapseBtn,
    'expandBtn' => $expandBtn,
]);

/** @var Module $module */
$module = Yii::$app->getModule('clean-theme');
if ($module?->collapsibleLeftNavigation && empty($options['id'])) {
    $options['id'] = 'left-navigation-collapse';
}
?>

<?= $module?->collapsibleLeftNavigation
    ? Button::light()->icon('bars')->id($expandBtn)->cssClass('d-none')->sm()->loader(false)
    : '' ?>

<?= Html::beginTag('div', $options) ?>

    <?php if (!empty($menu->panelTitle)) : ?>
        <div class="panel-heading">
            <?= $menu->panelTitle ?>
            <?= $module?->collapsibleLeftNavigation
                ? Button::light()->icon('chevron-left')->id($collapseBtn)->right()->sm()->loader(false)
                : '' ?>
        </div>
    <?php endif; ?>

    <div class="list-group list-group-horizontal list-group-vertical-lg">
        <?php foreach ($entries as $entry): ?>
            <?= $entry->render(['class' => 'list-group-item']) ?>
        <?php endforeach; ?>
    </div>

<?= Html::endTag('div') ?>
