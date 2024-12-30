<?php

namespace App\Core\Database;

use Exception;
use mysqli;
use PDO;
use PDOException;

class Connection
{
    /**
     * Create a new PDO connection.
     *
     * @param array $config
     */
    public static function make($config)
    {
        try {
            $mysqli = new mysqli(
                $config['host'],
                $config['username'],
                $config['password'],
                $config['name']
            );
            $mysqli->set_charset('utf8');
            return $mysqli;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
