<?php
    require_once __DIR__ . '/../config/config.php';

    class Expense {
        private $db;

        public function __construct() {
            $this->db = database::connect();
        }

        public function getAll($date = null) {
            $query          = "SELECT expenseId, expenseName, amount, notes, expenseDate, created_at FROM expenses WHERE deleted_at IS NULL";
            $params         = [];

            if ($date) {
                $query      .= " AND expenseDate = ?";
                $params[]   = $date;
            }

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function create(array $data) {
            [
                'name'   => $name,
                'amount' => $amount,
                'date'   => $date,
                'notes'  => $notes
            ] = $data + ['notes' => null];

            try {
                $this->db->beginTransaction();

                $stmt  = $this->db->prepare("INSERT INTO expenses (expenseName, amount, notes, expenseDate) 
                            VALUES (?,?,?,?)");

                if (!$stmt->execute([$name, $amount, $notes, $date])) {
                    throw new Exception("Insert Failed!");
                }

                $this->db->commit();
                return true;
            } catch (PDOException $err) {
                $this->db->rollBack();
                throw $err;
            }
        }

        public function getTotalExpenseByDate($type, $date1, $date2 = null) {
            if ($type == 'daily' && $date1) {
                $stmt   = $this->db->prepare("SELECT SUM(amount) as total_expense FROM expenses WHERE expenseDate = ? AND deleted_at IS NULL");
                $stmt->execute([$date1]);
            } elseif ($type == 'monthly' && $date1 && $date2) {
                $stmt   = $this->db->prepare("SELECT SUM(amount) as total_expense 
                                                FROM expenses 
                                                WHERE YEAR(expenseDate) = ?
                                                AND MONTH(expenseDate) = ?
                                                AND deleted_at IS NULL
                                            ");
                $stmt->execute([$date1, $date2]);
            }
            return $stmt->fetch(PDO::FETCH_ASSOC)['total_expense'] ?? 0;
        }
    }
?>