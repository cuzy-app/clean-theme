<?php

use humhub\modules\cleanTheme\Module;
use yii\db\Migration;

class m250402_151649_bs5 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('clean-theme');
        $scss = $module?->configuration?->settingsManager->get('scss');
        if (!$scss) {
            return;
        }

        $themeCustomScss = Yii::$app->settings->get('themeCustomScss');
        if ($themeCustomScss) {
            $scss = $themeCustomScss . "\n" . $scss;
        }

        Yii::$app->settings->set('themeCustomScss', $scss);

        $module->configuration->settingsManager->delete('scss');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250402_151649_bs5 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250402_151649_bs5 cannot be reverted.\n";

        return false;
    }
    */
}
