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
        // Migration from 1.17 to 1.18
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

        $primary = $moduleSettingsManager->get('primary');
        if ($primary && $primary !== '#31414a') {
            $coreSettingsManager->set('themePrimaryColor', $primary);
            $coreSettingsManager->set('useDefaultThemePrimaryColor', false);
            $moduleSettingsManager->delete('primary');
        }

        $success = $moduleSettingsManager->get('success');
        if ($success && $success !== '#518132') {
            $coreSettingsManager->set('themeSuccessColor', $success);
            $coreSettingsManager->set('useDefaultThemeSuccessColor', false);
            $moduleSettingsManager->delete('success');
        }

        $danger = $moduleSettingsManager->get('danger');
        if ($danger && $danger !== '#EC0426') {
            $coreSettingsManager->set('themeDangerColor', $danger);
            $coreSettingsManager->set('useDefaultThemeDangerColor', false);
            $moduleSettingsManager->delete('danger');
        }

        $warning = $moduleSettingsManager->get('warning');
        if ($warning && $warning !== '#AF640E') {
            $coreSettingsManager->set('themeWarningColor', $warning);
            $coreSettingsManager->set('useDefaultThemeWarningColor', false);
            $moduleSettingsManager->delete('warning');
        }

        $info = $moduleSettingsManager->get('info');
        if ($info && $info !== '#1A808E') {
            $coreSettingsManager->set('themeInfoColor', $info);
            $coreSettingsManager->set('useDefaultThemeInfoColor', false);
            $moduleSettingsManager->delete('info');
        }

        $default = $moduleSettingsManager->get('default');
        if ($default && $default !== '#f3f3f3') {
            $coreSettingsManager->set('themeLightColor', $default);
            $coreSettingsManager->set('useDefaultThemeLightColor', false);
            $moduleSettingsManager->delete('default'); // Default becomes Light
        }

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
