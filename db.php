<?php
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db = 'pos-app';

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['error' => 'Connection Failed!']);
        exit;
    }
?>