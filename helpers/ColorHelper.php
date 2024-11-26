<?php

/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\helpers;

/**
 * helper class for color manipulations, imitating the LESS functions lighten, darken, fade
 */
class ColorHelper
{
    /*
     * lighten() imitates the LESS function lighten()
     * It does not convert the color into HSL and back because this is not necessary to achieve the same result.
     * @param string $color RGB hexadecimal color code including '#'
     * @param int $amount between 0 and 100
     * @param bool $relative wether to lighten relatively to ligthness, default: false
     * @return string RGB hexadecimal color code including '#'
     */
    public static function lighten(string $color, int $amount, bool $relative = false): string
    {
        // split color into its components
        $colorParts = static::getColorComponents($color);

        $percentage = $amount / 100;

        // By default the LESS lighten() function adds the $amount absolutely to L, not relatively
        if (!$relative) {
            //Converting a RGB color to HSL, the Lightness would be calculated by L = [max(R,G,B) + min(R,G,B)] / (2 * 255)
            $max = hexdec(max($colorParts));
            $min = hexdec(min($colorParts));
            if ($max != 0) {
                $divider = (1 - ($max + $min) / (2 * 255));
                if ($divider) {
                    $percentage /= (1 - ($max + $min) / (2 * 255));
                }
            }
        }

        $result = '#';

        foreach ($colorParts as $colorPart) {
            $colorPart = hexdec($colorPart); // Convert to decimal
            $colorPart = round($colorPart + (255 - $colorPart) * $percentage); // Adjust color
            $colorPart = max(min($colorPart, 255), 0); // keep between 0 and 255
            $result .= str_pad(dechex($colorPart), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }

        return $result;
    }

    /*
     * see lighten()
     * @param string $color RGB hexadecimal color code including '#'
     * @param int $amount between 0 and 100
     * @param bool $relative wether to darken relatively to ligthness, default: false
     * @return string RGB hexadecimal color code including '#'
     */
    public static function darken(string $color, int $amount, bool $relative = false): string
    {
        // split color into its components
        $colorParts = static::getColorComponents($color);

        $percentage = $amount / 100;

        // By default the LESS darken() function substracts the $amount absolutely to L, not relatively
        if (!$relative) {
            //Converting a RGB color to HSL, the Lightness would be calculated by L = [max(R,G,B) + min(R,G,B)] / (2 * 255)
            $max = hexdec(max($colorParts));
            $min = hexdec(min($colorParts));
            if ($max !== 0) {
                $divider = $max + $min;
                if ($divider) {
                    $percentage = 2 * 255 * $percentage / $divider;
                }
            }
        }

        $result = '#';

        foreach ($colorParts as $colorPart) {
            $colorPart = hexdec($colorPart); // Convert to decimal
            $colorPart = round($colorPart * (1 - $percentage)); // Adjust color
            $colorPart = max(min($colorPart, 255), 0); // keep between 0 and 255
            $result .= str_pad(dechex($colorPart), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }

        return $result;
    }

    /*
     * fade() imitates the LESS function fade() which sets the opacity defined by $amount
     * @param string $color RGB hexadecimal color code including '#'
     * @param int $amount between 0 and 100
     * @return string RGBA hexadecimal color code including '#'
     */
    public static function fade(string $color, int $amount): string
    {
        $opacity = round(($amount / 100) * 255);
        $opacity = max(min($opacity, 255), 0); // keep between 0 and 255
        $opacity = str_pad(dechex($opacity), 2, '0', STR_PAD_LEFT); // make 2 char hex code

        return $color . $opacity;
    }

    /*
     * Split color into its components (R, G, B)
     * @param string $color RGB hexadecimal color code
     * @return array color components as 2 character hexadecimal code
     */
    protected static function getColorComponents(string $color): array
    {
        // Remove leading '#'
        $hexstr = ltrim($color, '#');
        // if color has just 3 digits
        if (strlen($hexstr) === 3) {
            $hexstr = $hexstr[0] . $hexstr[0] . $hexstr[1] . $hexstr[1] . $hexstr[2] . $hexstr[2];
        }

        return str_split($hexstr, 2);
    }
}
