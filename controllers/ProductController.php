<?php
    require_once __DIR__ .  '/../models/Product.php';

    class ProductController {
        public function index() {
            $productModel   = new Product();
            $products       = $productModel->getAll();
            
            echo json_encode([
                'status' => 'success',
                'data' => $products
            ]);
        }

        // public function store() {
        //     $input = json_decode(file_get_contents("php://input"), true);
        //     $input =
        // }
    }
?>