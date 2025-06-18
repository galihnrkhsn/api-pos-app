<?php
    require_once __DIR__ . '/../models/Expense.php';

    class ExpenseController {
        private $expenseModel;

        public function __construct() {
            $this->expenseModel = new Expense();
        }

        public function index() {
            $date = $_GET['date'] ?? null;
            $data = $this->expenseModel->getAll($date);

            echo json_encode([
                'status' => 'success',
                'data' => $data
            ]);
        }

        public function store() {
            $input = json_decode(file_get_contents("php://input"), true);

            $required = ['name', 'amount', 'date'];
            foreach ($required as $item) {
                if (!isset($input[$item])) {
                    http_response_code(400);
                    echo json_encode(['error' => "Field '{$item}' missing"]);
                    return;
                }
            }

            try {
                $this->expenseModel->create($input);
                
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Expenses Added'
                ]);
            } catch (PDOException $err) {
                http_response_code(500);
                echo json_encode(['error' => 'Failed Add Expense', 'message' => $err->getMessage()]);
            }

            

        }
    }   
?>