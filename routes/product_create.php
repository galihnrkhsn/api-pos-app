<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    // Jika method-nya OPTIONS (preflight), hentikan di sini
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    $data           = json_decode(file_get_contents("php://input"), true);
    $product_name   = $data['product_name'] ?? '';
    $price          = $data['price'] ?? 0;
    $stock          = $data['stock'] ?? 0;

    if ($product_name && $price > 0 && $stock >= 0) {
        $stmt = $conn->prepare("INSERT INTO products (product_name, price, stock) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $product_name, $price, $stock);
        $stmt->execute();
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid Input']);
    }
?>