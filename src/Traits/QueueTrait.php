<?php

namespace TxTech\Express\Traits;

/**
 * User : Zelin Ning(NiZerin)
 * Date : 2022/4/21
 * Time : 15:53
 * Email: i@nizer.in
 * Site : nizer.in
 * FileName: QueueTrait.php
 */


trait QueueTrait
{
    /**
     * 打印到屏幕上面
     */
    public function printToScreent($level, $message, $context = [])
    {
        if (is_cli()) {
            if (is_array($context)) $context = json_encode($context);
            fwrite(
                STDOUT,
                '[' . $level . '] [' . strftime('%T %Y-%m-%d') . '] ' . $message . $context . PHP_EOL
            );
        }
    }

    /**
     * 打印到日志文件
     */
    public function printToLogFile($level, $message, $context = [], $withScreent = false)
    {
        if (!is_array($context)) {
            $tmp = $context;
            $context = NULL;
            $context[] = $tmp;
        }

        $this->getLogger()->log($level, $message, $context);

        if ($withScreent) {
            $this->printToScreent($level, $message, $context);
        }
    }
}