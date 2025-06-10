<?php
    require_once __DIR__ . '/../config/config.php';

    class User {
        private $db;

        public function __construct() {
            $this->db = Database::connect();
        }

        public function findByName($name) {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE name = ?");
            $stmt->execute([$name]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function create($name, $hashedPassword, $role) {
            $stmt = $this->db->prepare("INSERT INTO users (name, password, role) VALUES (?, ?, ?)");
            return $stmt->execute([$name, $hashedPassword, $role]);
        }
    }
?>