<?php

use humhub\modules\cleanTheme\Module;
use humhub\modules\cleanTheme\services\GoogleFontsService;
use yii\db\Migration;

class m261118_120000_download_google_fonts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Since 2.6.0: serve the Google Fonts from the server, if it can download them (otherwise they are still loaded from Google)
        try {
            /** @var Module $module */
            $module = Yii::$app->getModule('clean-theme');
            if ($module) {
                (new GoogleFontsService($module->getConfiguration()))->download();
            }
        } catch (Throwable $e) {
            Yii::warning('Could not download the Google Fonts: ' . $e->getMessage(), 'clean-theme');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m261118_120000_download_google_fonts cannot be reverted.\n";

        return false;
    }
}
