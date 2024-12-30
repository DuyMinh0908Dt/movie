<?php

namespace App\Core;

class Request
{
    /**
     *
     * @return string
     */
    public static function uri()
    {
        return trim(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
            '/'
        );
    }

    /**
     *
     * @return string
     */
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    public static function isAuthenicated()
    {
        return isset($_SESSION['user']);
    }
    public static function setHeader($key, $value)
    {
        header("$key: $value");
    }
    public static function setQueryCount($count)
    {
        self::setHeader("X-Total-Count", $count);
    }

    public static function getUser()
    {
        return getArrayValueSafe($_SESSION, 'user', null);
    }
}