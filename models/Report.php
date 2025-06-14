<?php
    require_once __DIR__ . '/../config/config.php';

    class Report {
        private $db;

        public function __construct() {
            $this->db = Database::connect();
        }

        public function getSalesSummary($type, $date1, $date2 = null, $groupByPayment = false) {
            if ($type === 'daily' && $date1) {
                $query   = "SELECT " . ($groupByPayment ? "paymentType," : "") . " 
                                                    SUM(totalPrice) as total_sales, COUNT(*) as total_transactions 
                                                FROM transactions 
                                                WHERE DATE(created_at) = ?
                                                AND deleted_at IS NULL
                                            ";
                
                if ($groupByPayment) {
                    $query .= " GROUP BY paymentType";
                }
                
                $stmt = $this->db->prepare($query);
                $stmt->execute([$date1]);   
            } elseif ($type === 'monthly' && $date1 && $date2) {
                $query   = "SELECT " . ($groupByPayment ? "paymentType," : "") . "
                                                SUM(totalPrice) as total_sales, COUNT(*) as total_transactions
                                            FROM transactions
                                            WHERE YEAR(created_at) = ?
                                            AND MONTH(created_at) = ?
                                            AND deleted_at IS NULL
                                        ";
                if ($groupByPayment) {
                    $query .= " GROUP BY paymentType";
                }
                $stmt = $this->db->prepare($query);
                $stmt->execute([$date1, $date2]);
            } else {
                return [];
            }

            return $groupByPayment
                ? $stmt->fetchAll(PDO::FETCH_ASSOC)
                : $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function getTopSelling($type, $date1, $date2 = null) {
            if ($type === 'daily' && $date1) {
                $query = "SELECT ti.product_id, p.productName, SUM(ti.qty) as total_sold
                            FROM transaction_items ti
                            INNER JOIN products p ON ti.product_id = p.productId
                            INNER JOIN transactions t ON ti.transaction_id = t.transactionId 
                            WHERE DATE(t.created_at) = ?
                            GROUP BY ti.product_id, p.productName
                            ORDER BY total_sold DESC
                            LIMIT 1
                        ";
                $stmt   = $this->db->prepare($query);
                $stmt->execute([$date1]);
            } elseif ($type === 'monthly' && $date1 && $date2) {
                $query = "SELECT ti.product_id, p.productName, SUM(ti.qty) as total_sold
                            FROM transaction_items ti
                            INNER JOIN products p ON ti.product_id = p.productId
                            INNER JOIN transactions t ON ti.transaction_id = t.transactionId 
                            WHERE YEAR(t.created_at) = ?
                            AND MONTH(t.created_at) = ?
                            GROUP BY ti.product_id, p.productName
                            ORDER BY total_sold DESC
                            LIMIT 1
                        ";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$date1, $date2]);
            } else {
                return null;
            }

            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
?>