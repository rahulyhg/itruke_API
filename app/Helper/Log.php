<?php
/**
 * Created by HuaTu.
 * User: 陈仁焕
 * Email: ruke318@gmail.com
 * Date: 2018/4/24
 * Time: 12:40
 * Desc: [文件描述]
 */
namespace App\Helper;

use Illuminate\Support\Facades\Input;

class Log
{
    private static $logPath = '../../apiLogs/';

    private static function add($type, $message)
    {
        $currentDate = new \DateTime;

        $message = '[' . $type . '] ' . '{' . Input::method() . ' => ' . Input::getUri() . '} ' . $message . $currentDate->format('H:i:s');
        $writePath = self::$logPath . $currentDate->format('Y-m-d') . '.log';
        file_put_contents($writePath, $message . PHP_EOL);
    }

    public static function __callStatic($name, $arguments)
    {
        if (in_array($name, ['notice', 'error'])) {
            self::add($name, ...$arguments);
        } else {
            self::add('error', ...$arguments);
        }
    }
}