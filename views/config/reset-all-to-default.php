<?php
/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

use humhub\helpers\Html;
use humhub\modules\cleanTheme\models\Configuration;
use humhub\modules\ui\view\components\View;
use yii\helpers\Url;

/**
 * @var $this View
 * @var $model Configuration
 */
?>

<script <?= Html::nonce() ?>>
    window.location.href = <?= json_encode(Url::to(['index'])) ?>;
</script>
