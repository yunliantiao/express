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

    /**
     * @param $level
     * @param $message
     * @param $context
     * @return void
     */
    public static function saveFile($level, $message, $context)
    {
        $fileDir = '/var/www/html/storage/logs/dhl/';
        $fileName = date('Y-m-d') . '.log';
        $filePath = $fileDir . $fileName;

        if (!is_dir($fileDir)) {
            mkdir($fileDir, 0777, true);
        }

        if (is_array($context)) $context = json_encode($context, 256);

        file_put_contents($filePath, '[' . $level . '] [' . strftime('%T %Y-%m-%d') . '] ' . $message . ' ' . $context . PHP_EOL);
    }
}