<?php
declare(strict_types=1);

/*
* This file is part of the Twipsi package.
*
* (c) Petrik Gábor <twipsi@twipsi.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Core\Tools;

class Normalizer
{
    /**
     * UTF8 Converts.
     */
    protected const UTF8 = [
        '/[áàâãªä]/u' => 'a',
        '/[ÁÀÂÃÄ]/u' => 'A',
        '/[ÍÌÎÏ]/u' => 'I',
        '/[íìîï]/u' => 'i',
        '/[éèêë]/u' => 'e',
        '/[ÉÈÊË]/u' => 'E',
        '/[óòôõºöő]/u' => 'o',
        '/[ÓÒÔÕÖŐ]/u' => 'O',
        '/[úùûüű]/u' => 'u',
        '/[ÚÙÛÜŰ]/u' => 'U',
        '/ç/' => 'c',
        '/Ç/' => 'C',
        '/ñ/' => 'n',
        '/Ñ/' => 'N',
        '/–/' => '-',
        '/[’‘‹›‚]/u' => ' ',
        '/[“”«»„]/u' => ' ',
        '/ /' => ' ',
    ];

    /**
     * Pattern to use when slugifying.
     */
    protected const SLUG_PATTERN = "[^0-9a-zA-Z%s]+";

    /**
     * Exception characters when slugifying,
     * that should be replaced by the separator.
     */
    protected const BREAKERS = '\\/_|+ -';

    /**
     * Normalize a string while just removing lines and spaces,
     * or normlaizing the encoding if set to strict.
     *
     * @param string $string
     * @param bool $strict
     *
     * @return string
     */
    public static function cleanString(string $string, bool $strict = false): string
    {
        // Transliterate the string.
        $string = $strict ? self::normalizeEncoding($string) : $string;

        // Decode any possible html entities.
        $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');

        // Strip all the html tags if any.
        $string = trim(strip_tags($string));

        // Remove any tab spaces.
        $string = preg_replace("/\t/", "", $string);

        // Remove new lines and breaks.
        return preg_replace("/\r?\n/", "", $string);
    }

    /**
     * Normalize a path or url.
     *
     * @param string $url
     *
     * @return string
     */
    public static function normalizePath(string $url): string
    {
        // Decode and strip and lower.
        $url = strtolower(strip_tags(urldecode($url)));

        // Normalize the slashes.
        $url = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, trim($url));

        // Remove the right slash and return decoded.
        return self::cleanString(ltrim($url, DIRECTORY_SEPARATOR), true);
    }

    /**
     * Normalize encoding to UTF8
     *
     * @param string $string
     *
     * @return string
     */
    public static function normalizeEncoding(string $string): string
    {
        return function_exists('transliterator_transliterate')
          ? self::transliterate($string)
          : preg_replace(array_keys(self::UTF8), array_values(self::UTF8), $string);
    }

    /**
     * Transliterate a string.
     *
     * @return string
     */
    public static function transliterate(string $string): string
    {
        return transliterator_transliterate("Hex-Any/Java", $string);
    }

    /**
     * Slugify a string.
     *
     * @param string $string
     *
     * @return string
     */
    public static function slugifyString(string $string, string $separator = '-'): string
    {
        // Do a basic clean of the string.
        $string = self::cleanString(

            // Execute the pattern matching.
            preg_replace(sprintf(self::SLUG_PATTERN, self::BREAKERS), "", $string)
        );

        // Replace separators with seperator.
        $string = preg_replace(sprintf("/[%s]+/", self::BREAKERS), $separator, $string);

        return trim($string, $separator);
    }
}
