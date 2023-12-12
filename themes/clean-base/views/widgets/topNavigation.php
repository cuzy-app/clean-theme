<?php

use humhub\libs\Html;
use humhub\modules\cleanTheme\assets\CleanThemeTopNavigationAsset;
use humhub\modules\ui\menu\MenuEntry;
use humhub\modules\ui\view\components\View;
use humhub\widgets\TopMenu;

/* @var $this View */
/* @var $menu TopMenu */
/* @var $entries MenuEntry[] */

CleanThemeTopNavigationAsset::register($this);
$this->registerJsConfig('cleanTheme.topNavigation', [
    'searchItemLabel' => Yii::t('SearchModule.base', 'Search'),
]);
?>

<?php foreach ($entries as $entry): ?>
    <li class="top-menu-item <?php if ($entry->getIsActive()): ?>active<?php endif; ?>">
        <?= Html::a($entry->getIcon() . '<br />' . $entry->getLabel(), $entry->getUrl(), $entry->getHtmlOptions()); ?>
    </li>
<?php endforeach; ?>

<li id="top-menu-sub" class="dropdown" style="display:none;">
    <a href="#" id="top-dropdown-menu" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-align-justify"></i><br>
        <?= Yii::t('base', 'Menu'); ?>
        <b class="caret"></b>
    </a>
    <ul id="top-menu-sub-dropdown" class="dropdown-menu dropdown-menu-right">

    </ul>
</li>
