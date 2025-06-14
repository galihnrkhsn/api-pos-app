<?php
    require_once __DIR__ .  '/../models/Product.php';

    class ProductController {
        private $productModel;

        public function __construct() {
            $this->productModel = new Product();
        }

        public function index() {
            $groupBy        = $_GET['group_by'] ?? null;

            if ($groupBy === 'category') {
                $products   = $this->productModel->getByCategory();
            } else {
                $products       = $this->productModel->getAll();
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

            $existProduct   = $this->productModel->findByName($productName);

            $last_id        = $this->productModel->getLast();
            $barcode        = 'GNI' . str_pad($last_id['productId'] + 1, 4, '0', STR_PAD_LEFT);

            if ($existProduct) {
                http_response_code(400);
                echo json_encode(['error' => 'Produk sudah ada!']);
                return;
            }

            $this->productModel->create($category_id, $productName, $barcode, $price, $stock);

            echo json_encode([
                'status' => 'Create Product Successfully'
            ]);
        }

        public function show($productId) {
            $getId = $this->productModel->findById($productId);

            if (!$getId) {
                http_response_code(400);
                echo json_encode([
                    'error' => 'Failed to Get ID Products'
                ]);
                return;
            }

            echo json_encode([
                'status' => 'success',
                'data' => $getId
            ]);
        }

        public function updateStock() {
            $input = json_decode(file_get_contents("php://input"), true);

            if (!is_array($input)) {
                http_response_code(400);
                echo json_encode([
                    'error' => 'Invalid JSON'
                ]);
            }

            $failed     = [];
            $updated    = [];

            foreach($input as $item) {
                $productId  = $item['productId'] ?? null;
                $stock      = $item['stock'] ?? 0;

                if (!is_numeric($stock) || !$productId) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Invalid Data!']);
                    return;
                }

                $this->productModel->updateStock($productId, $stock);
            }

            echo json_encode([
                'status' => 'Update stock Successfully'
            ]);
        }

        // public function updateProduct($id, $data) {
        //     $existId    = $this->productModel->findById($id);

        //     if (!$existId) {
        //         http_response_code(400);
        //         echo json_encode([
        //             'error' => 'ID not Valid'
        //         ]);
        //         return;
        //     }

        //     $required = ['stock'];

        //     foreach ($required as $field) {
        //         if (!isset($data[$field])) {
        //             http_response_code(400);
        //             echo json_encode(['status' => 'error', 'message' => "$field is required"]);
        //             return;
        //         }
        //     }

        //     $updated    = $this->productModel->updateStock($id, $data);

        //     echo json_encode([
        //         'status' => $updated ? 'success' : 'error',
        //         'message' => $updated ? 'Update Stock Success!' : 'Failed to update Stock'
        //     ]);
        // }
    }
?>