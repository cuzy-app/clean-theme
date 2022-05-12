<?php

use humhub\libs\Html;
use humhub\modules\user\widgets\ProfileHeader;

$user = Yii::$app->user->identity;
?>

<?php // Add Profile header ?>
<?php if ($user): ?>
    <div id="account-profile-header" class="container">
        <div class="row">
            <div class="col-md-12">
                <?= ProfileHeader::widget(['user' => $user]); ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php // Replace "My account" button with "My profile" ?>
    <script <?= Html::nonce() ?>>
        $(function () {
            $('#account-profile-header a.edit-account').attr('href', '<?= Yii::$app->user->identity->createUrl('/user/profile/home') ?>').text(<?= json_encode(Yii::t('base', 'My profile'), JSON_HEX_TAG) ?>);
        });
    </script>

<?php require Yii::$app->getModule('user')->viewPath . '/account/_layout.php'; ?>