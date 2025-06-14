<?php
    require_once __DIR__ . '/../models/Transaction.php';

    class TransactionController{
        private $transactionModel;

        public function __construct() {
            $this->transactionModel = new Transaction();    
        }

        public function index() {
            $transactions       = $this->transactionModel->getAll();

            echo json_encode([
                'status' => 'success',
                'data' => $transactions
            ]);
        }

        public function store() {
            $input = json_decode(file_get_contents("php://input"), true);

            $paymentType    = $input['payment_type'] ?? null;
            $paidAmount     = $input['paid_amount'] ?? null;
            $items          = $input['items'] ?? [];

            $total          = 0;
            foreach ($items as $item) {
                $total += $item['price'] * $item['qty'];
            }

            if ($paidAmount < $total) {
                http_response_code(400);
                echo json_encode(['error' => 'Pembayaran Kurang!']);
                return;
            }

            if (!$input || !isset($paidAmount, $paymentType, $items)) {
                http_response_code(400);
                echo json_encode(['error' => 'Data tidak lengkap!']);
                return;
            }

            $userId = $_SESSION['user_id'];

            if (!$userId) {
                http_response_code(400);
                echo json_encode(['error' => 'Login terlebih dahulu!']);
                return;
            }

            $transaction        = $this->transactionModel->create($userId, $paymentType, $paidAmount, $items);

            if ($transaction) {
                echo json_encode([
                    'status' => "success",
                    'message' => "Transacton stored",
                    'data' => $transaction
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to Store Transaction']);
            }
        }

        public function show($transactionId) {
            $transactionModel = new Transaction();
            $transaction = $transactionModel->getById($transactionId);

            if (!$transaction) {
                http_response_code(400);
                echo json_encode(['error' => 'Transaction Not Found']);
                return;
            }
        
            $items = $transactionModel->getItems($transactionId);

            if (!$items) {
                http_response_code(400);
                echo json_encode(['error' => 'Transaction Items Not Found']);
                return;
            }

            $transaction['items'] = $items;

            echo json_encode([
                'status' => 'success',
                'data' => $transaction
            ]);
        }
    }
?>