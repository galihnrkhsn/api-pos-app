<?php
    class Database {
        private $host       = "localhost";
        private $db         = "pos-app";
        private $username   = "root";
        private $password   = "";
        public $conn;

        public function __construct() {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db);

            if ($this->conn->connect_error) {
                die("koneksi gagal:" . $this->conn->connect_error);
            }
        }
    }
?>