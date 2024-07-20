<?php
/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\commands;

use humhub\modules\cleanTheme\models\Configuration;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\BaseConsole;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;

class DeveloperController extends Controller
{
    public const DARK_MODE_MODULE_LESS_FILE_PATH = '@dark-mode/resources/DarkHumHub/less/theme.less';
    public array $specialColors = [];

    public function actionBuildModifiedSources()
    {
        // Copy HumHub LESS files
        $src = Yii::getAlias('@webroot/static/less');
        $dst = Yii::getAlias(Configuration::HUMHUB_MODIFIED_PATH . '/less');
        FileHelper::copyDirectory($src, $dst);

        // Copy Dark Mode module LESS theme file
        $src = Yii::getAlias(static::DARK_MODE_MODULE_LESS_FILE_PATH);
        copy($src, $dst . '/dark-mode.less');

        // Modify HumHub sources with the new special color variables
        $files = FileHelper::findFiles($dst);
        foreach ($files as $file) {
            $this->modifyHumHubSourcesWithNewSpecialColorVariables($file, basename($file) === 'variables.less');
        }
        // Same for Select2
        $this->modifySelect2SourcesWithNewSpecialColorsVariables();

        // Special color lists used in HumHub modified
        sort($this->specialColors);
        $this->createSpecialColorListInHumHubModified();

        $this->message("\nSuccessfully rebuilt theme files", 'success');
        return ExitCode::OK;
    }

    /*
     * Checks the given file line by line for LESS functions
     * and replaces them with special color variables
     */
    private function modifyHumHubSourcesWithNewSpecialColorVariables($file, bool $isVariableFile = false): void
    {
        $newLines = [];
        foreach (file($file, FILE_IGNORE_NEW_LINES) as $lineNumber => $line) {
            if (!$isVariableFile) {
                $newLine = $this->removeLessFunctions($line);
            } else {
                $newLine = $this->fixVariableFile($line);
            }
            if ($newLine) {
                $newLines[] = $newLine;
            } elseif ($newLine === false) {
                $this->message("Manual correction required!" . PHP_EOL . "Unsupported function line $lineNumber in $file", 'warning');
            }
        }
        file_put_contents($file, implode(PHP_EOL, $newLines));
    }

    /*
     * returns string the corrected line and fills $this->special_colors
     */
    private function removeLessFunctions($line): string|false
    {
        // Return unchanged if line is a comment
        if (str_starts_with($line, '//')) {
            return $line;
        }

        foreach (Configuration::UNSUPPORTED_LESS_FUNCTIONS as $less_function) {
            // Do not change lines with unsupported function but display a warning
            if (str_contains($line, $less_function . '(')) {
                return false;
            }
        }
        foreach (Configuration::SUPPORTED_LESS_FUNCTIONS as $less_function) {
            // Replace lines with supported function
            while ($pos = str_contains($line, $less_function . '(')) {

                $parts = explode($less_function . '(@', $line, 2);

                // Line beginning until LESS function
                $first = $parts[0];

                $rest = explode(',', $parts[1], 2);

                // Color variable (the @ symbol has been removed above)
                $color = $rest[0];

                $rest = explode(')', $rest[1], 2);

                // amount
                $amount = trim($rest[0], ' %');

                // Line ending (e.g. "!important;")
                $end = $rest[1];

                $specialColor = $color . '-' . $less_function . '-' . $amount;

                $this->specialColors[$specialColor] = $specialColor;

                $line = $first . 'var(' . Configuration::HUMHUB_CSS_PREFIX . $specialColor . ')' . $end;
            }
        }

        return $line;
    }

    private function createSpecialColorListInHumHubModified(): void
    {
        $content = '';

        foreach ($this->specialColors as $color) {
            $content .= Configuration::HUMHUB_CSS_PREFIX . $color . PHP_EOL;
        }

        $file = Yii::getAlias(Configuration::SPECIAL_COLOR_VARIABLES_IN_HUMHUB_MODIFIED_FILE);
        file_put_contents($file, $content);
        $this->message("Rebuilt file: $file", 'success');
    }

    /*
     * Helper function to output messages with a defined level
     */
    private function message($text, $level = 'info'): void
    {
        $color = '';
        if ($level === 'success') {
            $color = BaseConsole::FG_GREEN;
            $text = "$text\n";
        } elseif ($level === 'warning') {
            $color = BaseConsole::FG_YELLOW;
        } elseif ($level === 'error') {
            $color = BaseConsole::FG_RED;
            $text = "\n*** $text";
        }
        if ($level !== 'no-break') {
            $text .= "\n";
        }
        $this->stdout((string)$text, $color);
    }

    private function fixVariableFile(string $line): ?string
    {
        if (str_contains($line, '@import (reference) "../../')) {
            return str_replace('@import (reference) "../../', '@import (reference) "../../../../../../../', $line);
        }
        foreach (Configuration::getCssAttributeNames() as $cssAttributeName) {
            $cssVarName = Inflector::camel2id($cssAttributeName);
            // LESS variables which are generated by the Clean Theme module Configuration must have the CSS variable value
            if (str_starts_with($line, '@' . $cssVarName . ': ')) {
                return '@' . Inflector::camel2id($cssAttributeName) . ': var(' . Configuration::HUMHUB_CSS_PREFIX . $cssVarName . ');';
            }
            // Remove LESS variables creation already created by the Configuration
            if (str_contains($line, Configuration::HUMHUB_CSS_PREFIX . $cssVarName . ': @' . $cssVarName . ';')) {
                return null;
            }
        }
        return $line;
    }

    private function modifySelect2SourcesWithNewSpecialColorsVariables(): void
    {
        // Copy files
        $src = Yii::getAlias('@webroot/static/css/select2Theme');
        $dst = Yii::getAlias(Configuration::HUMHUB_MODIFIED_PATH . '/css/select2Theme');
        FileHelper::copyDirectory($src, $dst);
        $this->message("Copied $src to $dst", 'success');

        // Go through copied files
        $this->correctSelect2Build($dst . '/build.less');
        $this->correctSelect2Theme($dst . '/select2-humhub.less');
        $this->modifyHumHubSourcesWithNewSpecialColorVariables($dst . '/select2-humhub.less');
    }

    private function correctSelect2Build($file): void
    {
        $lines = file($file, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $key => $line) {
            if (str_contains($line, '@import "../../../protected/')) {
                // Correct import because of subfolder
                $lines[$key] = str_replace('@import "../../../protected/', '@import "../../../../../../../', $line);
            }
        }
        $data = implode(PHP_EOL, $lines);
        file_put_contents($file, $data);
    }

    private function correctSelect2Theme($file): void
    {
        $lines = file($file, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $key => $line) {
            if (!isset($pattern)) {
                if (isset($copyEnd)) {
                    $pattern = implode(PHP_EOL, array_slice($lines, $copyStart + 1, $copyEnd - $copyStart - 1));
                } elseif (isset($copyStart)) {
                    if ($line === '}') {
                        $copyEnd = (int)$key;
                    }
                } elseif (str_contains($line, '.validation-state-focus(@color) {')) {
                    $copyStart = (int)$key;
                }
            } elseif (str_contains($line, '.validation-state-focus(')) {
                $parts = explode('(', $line, 2);
                $color = trim($parts[1], ");");

                $lines[$key] = str_replace('@color', $color, $pattern);
            }
        }

        // Comment out pattern
        $i = $copyStart;
        while ($i <= $copyEnd) {
            $lines[$i] = '//' . $lines[$i];
            $i++;
        }

        $data = implode(PHP_EOL, $lines);
        file_put_contents($file, $data);
    }
}
