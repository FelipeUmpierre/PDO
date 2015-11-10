<?php

namespace Connection;

use PDO;
use Symfony\Component\Yaml\Parser;

/**
 * Class DatabaseSingleton
 *
 * @package Connection
 * @author Felipe Pieretti Umpierre <umpierre.felipe[at]gmail.com>
 */
final class DatabaseSingleton
{
    /**
     * @var PDO
     */
    private static $instance;

    /**
     * Protected constructor to prevent creating a new instance of the
     * *DatabaseSingleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *DatabaseSingleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *DatabaseSingleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }

    /**
     * Returns the *Singleton* instance of the EntityManager.
     *
     * @return PDO
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = static::configuration();
        }

        return static::$instance;
    }


    /**
     * Start the connection with the database
     */
    private static function configuration()
    {
        try {
            $yaml = new Parser();
            $databaseConfiguration = $yaml->parse(file_get_contents(__DIR__ . "/../../config/database.yml"))["mysql"];

            $pdo = new PDO(sprintf("mysql:host=%s;dbname=%s", $databaseConfiguration["host"], $databaseConfiguration["dbname"]), $databaseConfiguration["username"], $databaseConfiguration["password"]);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, FALSE);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,TRUE);

            return $pdo;
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
        }
    }
}