<?php
    require_once __DIR__ . '/../config/config.php';
    
    class Product { 
        private $db;

        public function __construct() {
            $this->db = Database::connect();
        }

        public function getAll(){
            $stmt = $this->db->query("SELECT * FROM products WHERE deleted_at IS NULL");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>