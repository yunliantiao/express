<?php
/**
 * User : Zelin Ning(NiZerin)
 * Date : 2022/4/25
 * Time : 15:01
 * Email: i@nizer.in
 * Site : nizer.in
 * FileName: Log.php
 */


namespace Txtech\Express\Core\Log;

/**
 * Class Log
 * @package Txtech\Express\Core\Log
 */
class Logger
{
    /**
     * @param $level
     * @param $message
     * @param $context
     * @return void
     */
    public static function printScreen($level, $message, $context): void
    {
        if (is_cli()) {
            if (is_array($context)) $context = json_encode($context);
            fwrite(
                STDOUT,
                '[' . $level . '] [' . strftime('%T %Y-%m-%d') . '] ' . $message . ' ' . $context . PHP_EOL
            );
        }
    }
}