<?php
    class ProductController {
        public function index() {
            $products = [
                ['id' => 1, 'name' => 'Produk A', 'price' => 100000],
                ['id' => 2, 'name' => 'Produk B', 'price' => 200000],
            ];
            echo json_encode($products);
        }
    }
?>