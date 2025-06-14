<?php
    require_once __DIR__ . '/../config/config.php';

    class Transaction {
        private $db;

        public function __construct() {
            $this->db = Database::connect();
        }

        public function getAll() {
            $stmt = $this->db->query("SELECT transactions.transactionId, users.name AS cashier, transactions.invoice, 
                                            transactions.totalPrice, transactions.paymentType, transactions.created_at
                                        FROM transactions 
                                        INNER JOIN users ON transactions.user_id = users.userId
                                        WHERE transactions.deleted_at IS NULL
                                    ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getById($transactionId) {
            $stmt = $this->db->prepare("SELECT * FROM transactions WHERE transactionId = ?");
            $stmt->execute([$transactionId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function getItems($transactionId) {
            $stmt   = $this->db->prepare("SELECT ti.transaction_id, ti.qty, ti.price, ti.totalPrice,
                                                p.productName 
                                            FROM transaction_items ti
                                            INNER JOIN products p ON ti.product_id = p.productId 
                                            WHERE transaction_id = ?");
            $stmt->execute([$transactionId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function create($userId, $paymentType, $paidAmount, $items) {
            try {
                $this->db->beginTransaction();
                
                $invCode        = $this->generateInvoice();
                
                $totalPrice     = 0;
                foreach ($items as $item) {
                    $totalPrice += $item['price'] * $item['qty'];
                }

                $changeAmount   = $paidAmount - $totalPrice;
                
                $stmt           = $this->db->prepare("INSERT INTO transactions (user_id, invoice, totalPrice, paymentType, paidAmount, changeAmount)
                                                        VALUES (?,?,?,?,?,?)
                                                    ");
                $stmt->execute([$userId, $invCode, $totalPrice, $paymentType, $paidAmount, $changeAmount]);
                $transactionId  = $this->db->lastInsertId();

                $stmtItem       = $this->db->prepare("INSERT INTO transaction_items (product_id, transaction_id, qty, price, totalPrice) 
                                                        VALUES (?,?,?,?,?)
                                                    ");
                foreach ($items as $item) {
                    $total      = $item['price'] * $item['qty'];
                    
                    $stmtItem->execute([
                        $item['product_id'],
                        $transactionId,
                        $item['qty'],
                        $item['price'],
                        $total
                    ]);

                    $this->db->prepare("UPDATE products SET stock = stock - ? WHERE productId = ? ")->execute([$item['qty'], $item['product_id']]);
                }

                $this->db->commit();

                return [
                    "invCode" => $invCode,
                    "total" => $totalPrice,
                    "change" => $changeAmount
                ];
            } catch (PDOException $error) {
                $this->db->rollBack();
                return false;
            }
        }

        private function generateInvoice() {
            $date = date('Ymd');
            $stmt = $this->db->prepare("SELECT COUNT(*) + 1 AS count FROM transactions WHERE DATE(created_at) = CURDATE() AND deleted_at IS NULL");
            $stmt->execute();
            $next = str_pad($stmt->fetch()['count'], 3, '0', STR_PAD_LEFT);
            return "INV-{$date}-{$next}";
        }
    }
?>