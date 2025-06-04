<?php

// Ambil method dan URI
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Base path (ganti sesuai folder kamu di localhost)
$basePath = '/project/pos-app/public';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}
$uri = rtrim($uri, '/');

// Include file route
require_once __DIR__ . '/../routes/api.php';
