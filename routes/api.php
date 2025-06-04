<?php
    switch(true) {
        case $method === 'GET' && $uri === '/products':
            require_once __DIR__ . '/../controllers/ProductController.php';
            (new ProductController())->index();
            break;

        case $method === 'GET' && $uri === '/transactions':
            require_once __DIR__ . '/../controllers/TransactionController.php';
            (new TransactionController())->index();
            break;

        case $method === 'POST' && $uri === '/transactions':
            require_once __DIR__ . '/../controllers/TransactionController.php';
            (new TransactionController())->store();
            break;

        default:
            http_response_code(404);
            echo json_encode(['error' => 'Route Invalid']);
            break;
    }
?>