<?php
    require_once __DIR__ .  '/../models/Product.php';

    class ProductController {
        public function index() {
            $groupBy        = $_GET['group_by'] ?? null;
            $productModel   = new Product();

            if ($groupBy === 'category') {
                $products   = $productModel->getByCategory();
            } else {
                $products       = $productModel->getAll();
            }
            
            echo json_encode([
                'status' => 'success',
                'data' => $products
            ]);
        }

        public function store() {
            $input          = json_decode(file_get_contents("php://input"), true);
            $category_id    = $input['category_id'] ?? null;
            $productName    = $input['productName'] ?? null;
            $price          = $input['price'] ?? 0;
            $stock          = $input['stock'] ?? 0;

            if (!$input || !isset($category_id, $productName, $price, $stock)) {
                http_response_code(400);
                echo json_encode(['error' => 'Data tidak lengkap!']);
                return;
            }

            $productModel   = new Product();
            $existProduct   = $productModel->findByName($productName);

            $last_id        = $productModel->getLast();
            $barcode        = 'GNI' . str_pad($last_id['productId'] + 1, 4, '0', STR_PAD_LEFT);

            if ($existProduct) {
                http_response_code(400);
                echo json_encode(['error' => 'Produk sudah ada!']);
                return;
            }

            $productModel->create($category_id, $productName, $barcode, $price, $stock);

            echo json_encode([
                'status' => 'Create Product Successfully'
            ]);
        }
    }
?>