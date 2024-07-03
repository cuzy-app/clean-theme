<?php

use humhub\modules\cleanTheme\Module;
use yii\db\Migration;

/**
 * Class m240703_142612_generate_dynamic_css_file
 */
class m240703_142612_generate_dynamic_css_file extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('clean-theme');
        $configuration = $module->getConfiguration();
        $configuration->generateDynamicCSSFile();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240703_142612_generate_dynamic_css_file cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240703_142612_generate_dynamic_css_file cannot be reverted.\n";

        return false;
    }
    */
}
