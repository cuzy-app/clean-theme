<?php

use humhub\helpers\Html;
use humhub\modules\ui\menu\MenuEntry;
use humhub\modules\ui\view\components\View;
use humhub\widgets\TopMenu;

/* @var $this View */
/* @var $menu TopMenu */
/* @var $entries MenuEntry[] */
?>

<?php foreach ($entries as $entry): ?>
    <li class="top-menu-item <?php if ($entry->getIsActive()): ?>active<?php endif; ?>">
        <?= Html::a($entry->getIcon() . '<br />' . $entry->getLabel(), $entry->getUrl(), $entry->getHtmlOptions()); ?>
    </li>
<?php endforeach; ?>

<li id="top-menu-sub" class="dropdown" style="display:none;">
    <a href="#" id="top-dropdown-menu" class="dropdown-toggle" data-bs-toggle="dropdown">
        <i class="fa fa-align-justify"></i><br>
        <?= Yii::t('base', 'Menu') ?>
    </a>
    <ul id="top-menu-sub-dropdown" class="dropdown-menu dropdown-menu-end">

    </ul>
</li>
