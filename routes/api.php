<?php
    header('Content-Type: application/json');

    require_once __DIR__ . '/../helpers/auth.php';
    require_once __DIR__ . '/../controllers/ProductController.php';
    require_once __DIR__ . '/../controllers/ReportController.php';
    require_once __DIR__ . '/../controllers/AuthController.php';
    require_once __DIR__ . '/../controllers/ExpenseController.php';
    require_once __DIR__ . '/../controllers/TransactionController.php';
    require_once __DIR__ . '/../controllers/CategoryController.php';


    $scriptName     = strtolower($_SERVER['SCRIPT_NAME']);
    $scriptDir      = str_replace('/index.php', '', $scriptName);
    $requestUri     = strtolower(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    $uri            = '/' . trim(str_replace($scriptDir, '', $requestUri), '/');
    $method         = $_SERVER['REQUEST_METHOD'];

    switch(true) {
        // PRODUCTS
        case $method === 'GET' && $uri === '/products':
            requireAuth();
            (new ProductController())->index();
            break;
        
        case $method === 'POST' && $uri === '/products':
            requireAuth();
            (new ProductController())->store();
            break;

        case $method === 'PUT' && $uri === '/products/update-stock':
            requireAuth();
            (new ProductController())->updateStock();
            break;

        // CATEGORIES

        case $method === 'GET' && $uri === '/categories':
            requireAuth();
            (new CategoryController())->index();
            break;
        
        case $method === 'POST' && $uri === '/categories':
            requireAuth();
            (new CategoryController())->store();
            break;

        // CATEGORIES

        case $method === 'GET' && $uri === '/expenses':
            requireAuth();
            (new ExpenseController())->index();
            break;
        
        case $method === 'POST' && $uri === '/expenses':
            requireAuth();
            (new ExpenseController())->store();
            break;

        // TRANSACTIONS

        case $method === 'GET' && $uri === '/transactions':
            requireAuth();
            (new TransactionController())->index();
            break;

        case $method === 'GET' && preg_match('#^/transactions/(\d+)$#', $uri, $matches):
            requireAuth();
            $transactionId = $matches[1];
            (new TransactionController())->show($transactionId);
            break;

        case $method === 'POST' && $uri === '/transactions':
            requireAuth();
            (new TransactionController())->store();
            break;

        // REPORT
        case $method === 'GET' && $uri === '/report':
            requireAuth();
            (new ReportController)->reportSalesSummary();
            break;

        // AUTH
        case $method === 'POST' && $uri === '/login':
            (new AuthController())->login();
            break;

        case $method === 'POST' && $uri === '/register':
            (new AuthController())->register();
            break;

        case $method === 'GET' && $uri === '/logout':
            (new AuthController())->logout();
            break;

        default:
            http_response_code(404);
            echo json_encode(['error' => 'Route Invalid']);
            break;
    }
?>