<?php
/**
 * User : Zelin Ning(NiZerin)
 * Date : 2022/4/21
 * Time : 15:43
 * Email: i@nizer.in
 * Site : nizer.in
 * FileName: helper.php
 */

if (!function_exists('get_env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function get_env(string $key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return value($default);
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return;
        }

        if (($valueLength = strlen($value)) > 1 && $value[0] === '"' && $value[$valueLength - 1] === '"') {
            return substr($value, 1, -1);
        }

        return $value;
    }
}

if (!function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param mixed $value
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('is_cli')) {
    /**
     * Is CLI?
     *
     * Test to see if a request was made from the command line.
     *
     * @return  bool
     */
    function is_cli()
    {
        return (PHP_SAPI === 'cli' or defined('STDIN'));
    }
}
