<?php
/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\BaseConsole;
use yii\helpers\FileHelper;

class DevController extends Controller
{
    public const STATIC_WITH_CSS_VARIABLES = '@clean-theme/resources/static-for-css-variables';
    public const SPECIAL_COLORS_LESS_FILE_NAME = 'special-colors-for-humhub-css-variables.less';
    public const SUPPORTED = ['darken', 'lighten', 'fade', 'fadein', 'fadeout'];
    public const UNSUPPORTED = ['saturate', 'desaturate', 'spin'];
    public array $specialColors = [];
    public array $unsupportedLines = [];

    public function actionRebuild()
    {
        // Copy HumHub LESS files
        $src = Yii::getAlias('@webroot/static/less');
        $dst = Yii::getAlias(self::STATIC_WITH_CSS_VARIABLES . '/less');
        FileHelper::copyDirectory($src, $dst);
        $this->message("Copied $src to $dst", 'success');

        // Check and correct copied files
        $files = FileHelper::findFiles($dst);
        foreach ($files as $file) {
            $this->checkAndCorrectFile($file, basename($file) === 'variables.less');
        }

        // Select2
        $this->handleSelect2();

        // special-colors.less
        sort($this->specialColors);
        $this->createSpecialColorsLess();

        // Output special colors array for manually updating AbstractColorSettings.php
        $this->message("\nSuccessfully rebuilt theme files", 'success');
        $this->message('*** Special colors to be copied:', 'warning');
        $this->message('    const SPECIAL_COLORS = [', 'no-break');
        foreach ($this->specialColors as $color) {
            $this->message("'" . $color . "',", 'no-break');
        }
        $this->message("];\n");

        // Warning about unsupported lines
        if ($this->unsupportedLines !== []) {
            $this->message("***\n Unsupported Lines: ", 'warning');
            foreach ($this->unsupportedLines as $line) {
                $this->message("$line[2] at $line[1]: $line[0]");
            }
        }

        return ExitCode::OK;
    }

    /*
     * Checks the given file line by line for LESS functions
     * and replaces them with special color variables
     */
    private function checkAndCorrectFile($file, bool $isVariableFile = false): void
    {
        //$this->message("Going through: $file");

        $lines = file($file, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $key => $line) {
            if (!$isVariableFile) {
                $lines[$key] = $this->removeLessFunctions($key, $line, $file);
            } else {
                $lines[$key] = $this->fixVariableFile($line);
            }
        }
        $data = implode(PHP_EOL, $lines);
        file_put_contents($file, $data);
    }

    /*
     * returns string the corrected line
     * and fills $this->special_colors and $this->unsupportedLines
     */
    private function removeLessFunctions($lineNumber, $line, $file): string
    {
        // Return unchanged if line is a comment
        if (str_starts_with($line, '//')) {
            return $line;
        }

        foreach (self::UNSUPPORTED as $less_function) {
            // Do not change lines with unsupported function but display a warning
            if (str_contains($line, $less_function . '(')) {
                $this->message("Manual correction required!\nUnsupported function in line ++$lineNumber in $file", 'warning');

                $this->unsupportedLines[] = [$line, $lineNumber, $file];

                // Return unchanged
                return $line;
            }
        }
        foreach (self::SUPPORTED as $less_function) {
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

                $special_color = str_replace('-', '_', $color) . '__' . $less_function . '__' . $amount;

                $this->specialColors[$special_color] = $special_color;

                $line = $first . '@' . str_replace(['__', '_'], '-', $special_color) . $end;
            }
        }

        return $line;
    }

    /*
     * Creates the file special-colors.less
     * LESS variables referring to CSS variables
     * e.g. @primary-darken-5: var(--primary--darken--5);
     */
    private function createSpecialColorsLess(): void
    {
        $content = '';

        foreach ($this->specialColors as $color) {
            $colorAsLessVar = '@' . str_replace(['__', '_'], '-', $color);
            $color = str_replace('_', '-', $color);
            $content .= $colorAsLessVar . ': var(--' . $color . ');' . PHP_EOL;
        }

        $file = Yii::getAlias(self::STATIC_WITH_CSS_VARIABLES . '/' . self::SPECIAL_COLORS_LESS_FILE_NAME);
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

    private function fixVariableFile(string $line): string
    {
        if (str_contains($line, '@import (reference) "../../')) {
            return str_replace('@import (reference) "../../', '@import (reference) "../../../../../../', $line);
        }
        if (str_contains($line, 'spin(@info')) {
            return str_replace('spin(@info', 'spin(#21A1B3', $line);
        }
        return $line;
    }


    private function handleSelect2(): void
    {
        // Copy files
        $src = Yii::getAlias('@webroot/static/css/select2Theme');
        $dst = Yii::getAlias(self::STATIC_WITH_CSS_VARIABLES . '/css/select2Theme');
        FileHelper::copyDirectory($src, $dst);
        $this->message("Copied $src to $dst", 'success');

        // Go through copied files
        $this->correctSelect2Build($dst . '/build.less');
        $this->correctSelect2Theme($dst . '/select2-humhub.less');
        $this->checkAndCorrectFile($dst . '/select2-humhub.less');
    }

    private function correctSelect2Build($file): void
    {
        $lines = file($file, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $key => $line) {
            if (str_contains($line, '@import "../../../protected/')) {
                // Correct import because of subfolder
                $lines[$key] = str_replace('@import "../../../protected/', '@import "../../../../../../', $line);
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
