<?php
    header("Content-Type: application/json");

    $script     = $_SERVER['SCRIPT_NAME'];
    $req_uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    $request    = str_replace($script, '', $req_uri);
    $method     = $_SERVER['REQUEST_METHOD'];

    if ($request === '/login' && $method === 'POST') {
        require_once './controllers/AuthController.php';
        $data = json_decode(file_get_contents('php://input'), true);

        $auth = new AuthController();
        $response = $auth->login($data['username'], $data['password']);

        echo json_encode($response);
        exit;
    }

    if (preg_match("#^/products$#", $request)) {
        require_once './controllers/ProductControllers.php';
        $controller = new ProductControllers();

        if ($method === 'GET') {
            echo json_encode($controller->getAll());
        } elseif ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            echo json_encode($controller->create($data));
        }
        exit;
    }

    if (preg_match("#^/products/(\d+)$#", $request, $matches)) {
        require_once './controllers/ProductControllers.php';
        $controller = new ProductControllers();
        $id = (int)$matches[1];

        if ($method === 'GET') {
            echo json_encode($controller->getOne($id));
        } elseif ($method === 'PUT') {
            $data = json_decode(file_get_contents("php://input"), true);
            echo json_encode($controller->update($id, $data));
        } elseif ($method === 'DELETE') {
            echo json_encode($controller->delete($id));
        }
        exit;
    }

    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Route tidak ditemukan']);
?>