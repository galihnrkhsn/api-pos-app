<?php
    require_once __DIR__ . '/../helpers/env.php';
    $host   = env('DB_HOST');
    $db     = env('DB_NAME');
    $user   = env('DB_USER');
    $pass   = env('DB_PASS');

    try {
        $pdo = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection Failed:" . $e->getMessage());
    }

    
?>  