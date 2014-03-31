<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 *
 * @date: 31/01/13 13:30
 */

namespace engine;

/**
 * Class Log
 * @package engine
 */
class Log
{
    /**
     * Log file name.
     * Notice that depending on the log type, the logs are stored in different folders. This means that a
     * LOG_FILE might be found in the folders debug, error and info.
     */
    const LOG_FILE = 'Log.txt';
    
    public static function debug($msg)
    {
        $file = fopen(_FOLDER_LOG_DEBUG . self::LOG_FILE, 'a');
        fwrite($file, date('m/d/Y G:i', time()) . ' - ' . $msg. "\r\n");
        fclose($file);
    }
    public static function error($msg)
    {
        $file = fopen(_FOLDER_LOG_ERROR . self::LOG_FILE, 'a');
        fwrite($file, date('m/d/Y G:i', time()) . ' - ' . $msg. "\r\n");
        fclose($file);
        
    }
    public static function info($msg)
    {
        $file = fopen(_FOLDER_LOG_INFO . self::LOG_FILE, 'a');
        fwrite($file, date('m/d/Y G:i', time()) . ' - ' . $msg. "\r\n");
        fclose($file);
    }
}