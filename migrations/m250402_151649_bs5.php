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
        $moduleSettingsManager = $module?->configuration?->settingsManager;
        if (!$moduleSettingsManager) {
            return;
        }

        $coreSettingsManager = Yii::$app->settings;

        $scss = $moduleSettingsManager->get('scss');
        if ($scss) {
            $themeCustomScss = $coreSettingsManager->get('themeCustomScss');
            if ($themeCustomScss) {
                $scss = $themeCustomScss . "\n" . $scss;
            }
            $coreSettingsManager->set('themeCustomScss', $scss);
            $moduleSettingsManager->delete('scss');
        }

        $coreSettingsManager->set('themePrimaryColor', $moduleSettingsManager->get('primary', '#31414a'));
        $coreSettingsManager->set('useDefaultThemePrimaryColor', false);
        $moduleSettingsManager->delete('primary');

        $coreSettingsManager->set('themeSuccessColor', $moduleSettingsManager->get('success', '#518132'));
        $coreSettingsManager->set('useDefaultThemeSuccessColor', false);
        $moduleSettingsManager->delete('success');

        $coreSettingsManager->set('themeDangerColor', $moduleSettingsManager->get('danger', '#EC0426'));
        $coreSettingsManager->set('useDefaultThemeDangerColor', false);
        $moduleSettingsManager->delete('danger');

        $coreSettingsManager->set('themeWarningColor', $moduleSettingsManager->get('warning', '#AF640E'));
        $coreSettingsManager->set('useDefaultThemeWarningColor', false);
        $moduleSettingsManager->delete('warning');

        $coreSettingsManager->set('themeInfoColor', $moduleSettingsManager->get('info', '#1A808E'));
        $coreSettingsManager->set('useDefaultThemeInfoColor', false);
        $moduleSettingsManager->delete('info');

        $coreSettingsManager->set('themeLightColor', $moduleSettingsManager->get('default', '#f3f3f3'));
        $coreSettingsManager->set('useDefaultThemeLightColor', false);
        $moduleSettingsManager->delete('default'); // Default becomes Light

        $link = $moduleSettingsManager->get('link');
        if ($link) {
            $moduleSettingsManager->set('linkColor', $link);
            $moduleSettingsManager->delete('link');
        }
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
