<?php
    session_start();
    $env = parse_ini_file(__DIR__ . '/../.env');
    foreach ($env as $key => $value) {
        putenv("$key=$value");
    }

    require_once __DIR__ . '/../routes/api.php';
?>