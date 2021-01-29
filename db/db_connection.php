<?php
if (!defined('CONST_INCLUDE'))
    die('Direct access prohibited !');

class DBConnection {
    public static $db;

    /**
	 * Init the connection to the db
	 */
    public static function initDBConnection() {
            try {
                $host = "host";
                $dbname = "databasename";
                $username = "username";
                $pwd = "password";
                self::$db = new PDO("mysql:host=$host; dbname=$dbname", "$username", "$pwd");
            } catch(PDOException $e) {
                die ('DB connection failed !');
            }
    }
}
?>