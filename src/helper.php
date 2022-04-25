<?php
/**
 * User : Zelin Ning(NiZerin)
 * Date : 2022/4/25
 * Time : 15:05
 * Email: i@nizer.in
 * Site : nizer.in
 * FileName: helper.php
 */

if (!function_exists('is_cli')) {
    /**
     * Is CLI?
     *
     * Test to see if a request was made from the command line.
     *
     * @return  bool
     */
    function is_cli(): bool
    {
        return (PHP_SAPI === 'cli' or defined('STDIN'));
    }
}

if (!function_exists('character_limiter')) {
    /**
     * @param string $string
     * @param int $length
     * @param string $end_char
     * @return string
     */
    function character_limiter(string $string, int $length, string $end_char = '&#8230;')
    {
        if (mb_strlen($string) < $length) {
            return $string;
        }

        $string = preg_replace('/(\s)*&(n|N)(b|B)(s|S)(p|P)(;)*(\s)*/', ' ', $string);

        return rtrim(mb_substr($string, 0, $length, 'UTF-8')) . $end_char;
    }
}
