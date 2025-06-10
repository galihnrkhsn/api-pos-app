<?php
    require_once __DIR__ . '/../config/config.php';

    class Category {
        private $db;

        public function __construct() {
            $this->db = Database::connect();
        }

        public function getAll(){
            $stmt = $this->db->query("SELECT categoryId, categoryName, created_at FROM categories WHERE deleted_at IS NULL");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function findByName($CategoryName) {
            $stmt = $this->db->prepare("SELECT * FROM categories WHERE categoryName = ?");
            $stmt->execute([$CategoryName]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function create($CategoryName) {
            $stmt = $this->db->prepare("INSERT INTO categories (categoryName) VALUES (?)");
            return $stmt->execute([$CategoryName]);
        }
    }
?>