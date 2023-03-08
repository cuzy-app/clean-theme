<?php

use humhub\libs\Html;
use humhub\modules\cleanTheme\assets\CleanThemeLeftNavigationAsset;
use humhub\modules\cleanTheme\Module;
use humhub\widgets\Button;

/* @var $this \humhub\modules\ui\view\components\View */
/* @var $menu \humhub\modules\ui\menu\widgets\LeftNavigation */
/* @var $entries \humhub\modules\ui\menu\MenuEntry[] */
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
if ($module->collapsibleLeftNavigation) {
    if (empty($options['id'])) {
        $options['id'] = 'left-navigation-collapse';
    }
}
?>

<?= $module->collapsibleLeftNavigation ?
    Button::defaultType()->icon('bars')->id($expandBtn)->cssClass('hidden')->sm()->loader(false) :
    '' ?>
<?= Html::beginTag('div', $options) ?>
<?php if (!empty($menu->panelTitle)) : ?>
    <div class="panel-heading">
        <?= $menu->panelTitle ?>
        <?= $module->collapsibleLeftNavigation ?
            Button::defaultType()->icon('chevron-left')->id($collapseBtn)->right()->sm()->loader(false) :
            '' ?>
    </div>
<?php endif; ?>

<div class="list-group">
    <?php foreach ($entries as $entry): ?>
        <?= $entry->render(['class' => 'list-group-item']) ?>
    <?php endforeach; ?>
</div>
<?= Html::endTag('div') ?>
