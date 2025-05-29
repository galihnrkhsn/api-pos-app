<?php
    // Izinkan akses dari frontend Vite (port 5173)
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    // Jika method-nya OPTIONS (preflight), hentikan di sini
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    require_once 'db.php';

    header("Content-Type: application/json");

    $method = $_SERVER['REQUEST_METHOD'];
    $uri    = $_GET['r'] ?? '';

    switch ("$method $uri") {
        case 'GET /products':
            require 'routes/product_list.php';
            break;
        
        case 'POST /products':
            require 'routes/product_create.php';
            break;

        case 'PUT /products':
            require 'routes/product_update.php';
            break;

        case 'DELETE /products':
            require 'routes/product_delete.php';
            break;

        default:
            http_response_code(404);
            echo json_encode(['error' => 'Route not Found']);
    }
?>