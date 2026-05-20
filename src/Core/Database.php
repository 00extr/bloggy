<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $databaseInstance = null;
    private static string $databaseHost = 'db';
    private static string $databaseName = 'bloggy_blog';
    private static string $databaseUser = 'root';
    private static string $databasePassword = 'root';
    private static string $databaseCharset  = 'utf8mb4';

    private function __construct() {}

    public static function getInstance(): PDO
    {
        if (self::$databaseInstance === null) {
            
            $dataSourceName = sprintf(
                "mysql:host=%s;dbname=%s;charset=%s", self::$databaseHost, self::$databaseName, self::$databaseCharset
            );
            
            $connectionOptions = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                self::$databaseInstance = new PDO(
                    $dataSourceName, self::$databaseUser, self::$databasePassword, $connectionOptions
                );
            } catch (PDOException $exception) {
                die("Critical connection error - " . $exception->getMessage());
            }
        }

        return self::$databaseInstance;
    }
}