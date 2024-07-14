<?php
/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\models;

use humhub\modules\cleanTheme\Module;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadConfiguration extends Model
{
    public $jsonConfigurationFile;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jsonConfigurationFile'], 'file', 'extensions' => 'json']
        ];
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function save()
    {
        // Try to get configuration from uploaded file
        $this->jsonConfigurationFile = UploadedFile::getInstance($this, 'jsonConfigurationFile');
        if ($this->jsonConfigurationFile instanceof UploadedFile && $this->jsonConfigurationFile->error !== UPLOAD_ERR_NO_FILE) {
            // Get content from file
            $configurationValues = json_decode((string)file_get_contents($this->jsonConfigurationFile->tempName), true);
        }

        if (empty($configurationValues)) {
            return false;
        }

        /** @var Module $module */
        $module = Yii::$app->getModule('clean-theme');
        $configuration = $module->getConfiguration();

        foreach ($configurationValues as $attributeName => $value) {
            if (isset($configuration->$attributeName)) {
                $configuration->$attributeName = $value;
            }
        }

        if ($configuration->validate()) {
            return $configuration->save();
        }
        return false;
    }
}
