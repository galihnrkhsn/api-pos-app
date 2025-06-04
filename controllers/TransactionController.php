<?php
    class TransactionController{
        public function index() {
            $transactions = [
                ['id' => 1, 'name' => 'Produk A', 'price' => 100000],
                ['id' => 2, 'name' => 'Produk B', 'price' => 200000],
            ];
            echo json_encode($transactions);
        }

        public function store() {
            $input = json_decode(file_get_contents("php://input"), true);
            if (!$input) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid JSON']);
                return;
            }

            echo json_encode([
                'message' => 'success',
                'data'  => $input
            ]);
        }
    }
?>