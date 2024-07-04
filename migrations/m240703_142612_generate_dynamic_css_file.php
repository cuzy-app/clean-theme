<?php

use humhub\libs\DynamicConfig;
use humhub\modules\cleanTheme\Module;
use humhub\modules\ui\view\helpers\ThemeHelper;
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

        // Switch old themes to the new one
        foreach (ThemeHelper::getThemeTree(Yii::$app->view->theme) as $theme) {
            if (in_array($theme->name, ['clean-base', 'clean-bordered', 'clean-contrasted'], true)) {
                $cleanTheme = ThemeHelper::getThemeByName('Clean');
                if ($cleanTheme !== null) {
                    $cleanTheme->activate();
                    DynamicConfig::rewrite();
                }
            }
        }
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
