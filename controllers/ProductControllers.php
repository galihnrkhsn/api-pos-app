<?php
    require_once dirname(__DIR__) . '/config/db.php';

    class ProductControllers {
        private $conn;
        
        public function __construct() {
            $db = new Database();
            $this->conn = $db->conn;
        }

        public function getAll() {
            $result = $this->conn->query("SELECT id, product_name, price, stock FROM products WHERE deleted_at IS NULL");
            $products = [];

            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }

            return ['status' => 'success', 'data' => $products];
        }

        public function getOne($id) {
            $stmt = $this->conn->prepare("SELECT id, product_name, price, stock FROM products WHERE id = ? AND deleted_at IS NULL");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                return ['status' => 'success', 'data' => $row ];
            }

            return ['status' => 'error', 'message' => 'Produk tidak ditemukan'];
        }

        public function create($data) {
            $stmt = $this->conn->prepare("INSERT INTO products (product_name, price, stock) VALUES (?, ?, ?)");
            $stmt->bind_param("sii", $data['product_name'], $data['price'], $data['stock']);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                return ['status' => 'success', 'message' => 'Produk berhasil ditambahkan!'];
            }

            return ['status' => 'error', 'message' => 'Gagal menambahkan data!'];
        }

        public function update($id, $data) {
            $stmt = $this->conn->prepare("UPDATE products SET product_name = ?, price = ?, stock = ? WHERE id = ?");
            $stmt->bind_param("siii", $data['product_name'], $data['price'], $data['stock'], $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                return ['status' => 'success', 'message' => 'Berhasil update data!'];
            }

            return ['status' => 'error', 'message' => 'data gagal di update!'];
        }

        public function delete($id) {
            $stmt = $this->conn->prepare("UPDATE products SET deleted_at = NOW() WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                return ['status' => 'success', 'message' => 'Berhasil hapus data'];
            }

            return ['status' => 'error', 'message' => 'data gagal dihapus!'];
        }
    }
?>