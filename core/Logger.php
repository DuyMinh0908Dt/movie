<?php

namespace App\Core;

class Logger
{
    private static $logFile = "app.log";
    public static function getLogs()
    {
        return file_get_contents(self::$logFile);
    }
    public static function info(...$message)
    {
        self::write("INFO", implode(" ", $message));
    }

    public static function error(...$message)
    {
        self::write("ERROR", implode(" ", $message));
        error_log(implode(" ", $message));
    }

    private static function write($level, $message)
    {
        $date = date("H:i:s d/m/Y");
        // $backtrace = debug_backtrace();
        // $callerFile = $backtrace[1]['file'];
        // $callerLine = $backtrace[1]['line'];
        // [$callerFile:$callerLine]
        $line = "[$date] [$level]  $message" . PHP_EOL;
        file_put_contents(self::$logFile, $line, FILE_APPEND);
    }
    public static function getLogsPath()
    {
        return self::$logFile;
    }
}