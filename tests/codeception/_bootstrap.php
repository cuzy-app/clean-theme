<?php

/**
 * Bootstrap for the Clean Theme module test suite.
 * Loads the core HumHub bootstrap and sets module-specific aliases.
 */

use Codeception\Configuration;
use Codeception\Util\Autoload;

$env = $GLOBALS['env'] ?? [];

if (count($env) > 0) {
    Configuration::append(['environment' => $env]);
    $envCfgFile = dirname(__DIR__) . '/config/env/test.' . $env[0][0] . '.php';
    if (file_exists($envCfgFile)) {
        $cfg = array_merge(require_once(__DIR__ . '/../config/test.php'), require_once($envCfgFile));
    }
}

$cfg ??= require_once(__DIR__ . '/../config/test.php');

// Fall back: assume the module lives inside a standard HumHub checkout at
// protected/modules/clean-theme — four levels above the tests/ directory.
$cfg['humhub_root'] ??= dirname(__DIR__) . '/../../../..';

require_once($cfg['humhub_root'] . '/protected/humhub/tests/codeception/_bootstrap.php');

Yii::setAlias('@tests', dirname(__DIR__));
Yii::setAlias('@env', '@tests/config/env');
Yii::setAlias('@root', $cfg['humhub_root']);
Yii::setAlias('@humhubTests', $cfg['humhub_root'] . '/protected/humhub/tests');
Yii::setAlias('@clean-theme', dirname(__DIR__, 2));

Autoload::addNamespace('', Yii::getAlias('@humhubTests/codeception/_support'));
Autoload::addNamespace('tests\codeception\fixtures', Yii::getAlias('@humhubTests/codeception/fixtures'));
Autoload::addNamespace('', Yii::getAlias('@humhubTests/codeception/_pages'));
Autoload::addNamespace('humhub\modules\cleanTheme\tests\codeception', __DIR__);

if (isset($cfg['modules'])) {
    Configuration::append(['humhub_modules' => $cfg['modules']]);
}

if (isset($cfg['fixtures'])) {
    Configuration::append(['fixtures' => $cfg['fixtures']]);
}
