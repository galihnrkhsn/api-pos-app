<?php
    $method     = $_SERVER['REQUEST_METHOD'];
    $uri        = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if ($method === 'GET' && $uri === '/products') {
        require_once __DIR__ . '/../controllers/ProductController.php';
        (new ProductController())->index();
        exit;
    }

    if ($method === 'POST' && $uri === '/transactions') {
        require_once __DIR__ . '/../controllers/TransactionController.php';
        (new TransactionController())->store();
        exit;
    }

    if ($method === 'GET' && $uri === '/transactions') {
        require_once __DIR__ . '/../controllers/TransactionController.php';
        (new TransactionController())->index();
        exit;
    }

    http_response_code(404);
    echo json_encode(['error' => 'Invalid Route']);
?>