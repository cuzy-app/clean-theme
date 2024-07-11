<?php

use humhub\modules\ecommerce\Module;
use humhub\modules\ui\view\helpers\ThemeHelper;
use yii\db\Migration;

/**
 * Class m240706_073409_getVersion1ThemeConfiguration
 */
class m240706_073409_getVersion1ThemeConfiguration extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // TODO: later, remove this migration

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
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240706_073409_getVersion1ThemeConfiguration cannot be reverted.\n";

        return false;
    }
}
