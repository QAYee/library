<?php

class DatabaseConnect {
    private $host = 'localhost';
    private $database = 'newlibrary';
    private $username = 'root';
    private $password = '';
    private $conn = null;

    public function connectDB() {
        // Using mysqli
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        // Check connection
        if ($this->conn->connect_error) {
            echo "Connection failed: " . $this->conn->connect_error;
            return null;
        }

        return $this->conn;
    }
}
?>

