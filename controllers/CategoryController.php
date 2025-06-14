<?php
    require_once __DIR__ . '/../models/Category.php';

    class CategoryController {
        private $categoryModel;

        public function __construct() {
            $this->categoryModel = new Transaction();
        }

        public function index() {
            $category       = $this->categoryModel->getAll();

            echo json_encode([
                'status' => 'success',
                'data' => $category
            ]);
        }

        public function store() {
            $input              = json_decode(file_get_contents("php://input"), true);
            $categoryName       = $input['name'] ?? null;

            if (!$input || !isset($categoryName)) {
                http_response_code(400);
                echo json_encode(['error' => 'Data tidak boleh kosong']);
                return;
            }

            $categoryModel      = new Category();
            $existCate          = $categoryModel->findByName($categoryName);

            if ($existCate) {
                http_response_code(400);
                echo json_encode(['error' => 'Category sudah ada']);
                return;
            }

            $categoryModel->create($categoryName);

            echo json_encode([
                'message' => 'Category berhasil ditambahkan!'
            ]);
        }
    }
?>