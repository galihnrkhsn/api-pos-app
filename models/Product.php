<?php
    require_once __DIR__ . '/../config/config.php';
    
    class Product { 
        private $db;

        public function __construct() {
            $this->db = Database::connect();
        }

        public function getAll(){
            $stmt = $this->db->query("SELECT products.productId, products.productName, products.barcode, products.price, 
                                            products.stock, products.created_at, categories.categoryName AS category 
                                        FROM products
                                        INNER JOIN categories ON products.category_id = categories.categoryId 
                                        WHERE products.deleted_at IS NULL
                                        AND categories.deleted_at IS NULL
                                    ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getByCategory() {
            $rows       = Product::getAll();
            $grouped    = [];

            foreach ($rows as $row) {
                $category = $row['category'];
                unset($row['category']);
                $grouped[$category][] = $row; 
            }

            return $grouped;
        }

        public function findByName($productName) {
            $stmt = $this->db->prepare("SELECT * FROM products WHERE productName = ? AND deleted_at IS NULL");
            $stmt->execute([$productName]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function getLast() {
            $stmt = $this->db->query("SELECT * FROM products WHERE deleted_at IS NULL ORDER BY productId DESC LIMIT 1");
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function create($category_id, $productName, $barcode, $price, $stock) {
            $stmt = $this->db->prepare("INSERT INTO products (category_id, productName, barcode, price, stock) VALUES (?, ?, ?, ?, ?)");
            return $stmt->execute([$category_id, $productName, $barcode, $price, $stock]);
        }

        public function updateStock($id, $stock) {
            $stmt = $this->db->prepare("UPDATE products SET stock = ? WHERE productId = ?");
            return $stmt->execute([$stock, $id]);
        }

        public function findById($id) {
            $stmt = $this->db->prepare("SELECT productId FROM products WHERE productId = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
?>