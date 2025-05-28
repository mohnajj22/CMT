<?php

class Database {
    private static $instance = null; // static instance holder
    private $conn;

    private $host = 'localhost';
    private $db_name = 'cmt_dt';
    private $username = 'root';
    private $password = '';

    // 🛑 Private constructor to prevent multiple instances
    private function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // ✅ Static method to get the instance
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // ✅ Accessor for the PDO connection
    public function getConnection() {
        return $this->conn;
    }
}
