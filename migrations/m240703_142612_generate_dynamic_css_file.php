<?php

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
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('clean-theme');
        $configuration = $module->getConfiguration();

        // Switch old themes to the new one
        $isActiveCleanV1 = false;
        foreach (ThemeHelper::getThemeTree(Yii::$app->view->theme) as $theme) {
            if (in_array($theme->name, ['clean-base', 'clean-bordered'], true)) {
                $configuration->primary = '#435f6f';
                $configuration->info = '#21A1B3';
                $configuration->success = '#619b3c';
                $configuration->warning = '#ee9126';
                $configuration->danger = '#FC4A64';
                $configuration->link = '#2eaae1';
                $configuration->textColorMain = '#76838f';
                $configuration->textColorSoft2 = '#aeaeae';
                $isActiveCleanV1 = true;
            }
            if (in_array($theme->name, ['clean-base', 'clean-contrasted'], true)) {
                $configuration->panelBorderStyle = 'none';
                $configuration->panelBoxShadow = 'none';
                $isActiveCleanV1 = true;
            }
        }

        if ($isActiveCleanV1) {
            $configuration->save();
            $cleanTheme = ThemeHelper::getThemeByName('Clean');
            $cleanTheme?->activate();
        } else {
            $configuration->generateDynamicCSSFile();
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
