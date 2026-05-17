<?php


class Database {
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $dbname = "hotel_management"; 
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);
            
            if ($this->conn->connect_error) {
                die("Connection failure: " . $this->conn->connect_error);
            }

            
            $this->conn->set_charset("utf8mb4");

        } catch (Exception $e) {
            die("Database Error Connection Exception: " . $e->getMessage());
        }
        return $this->conn;
    }
}