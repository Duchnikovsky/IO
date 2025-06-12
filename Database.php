<?php

require_once 'config.php';

class Database
{
    private $username;
    private $password;
    private $dbHost;
    private $dbName;

    public function __construct()
    {
        $this->username = DB_USERNAME;
        $this->password = DB_PASSWORD;
        $this->dbHost = DB_HOST;
        $this->dbName = DB_NAME;
    }

    public function connect()
    {
        try {
            $conn = new PDO(
                "pgsql:host={$this->dbHost};port=5432;dbname={$this->dbName}",
                $this->username,
                $this->password,
                ["sslmode" => "prefer"]
            );

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}
