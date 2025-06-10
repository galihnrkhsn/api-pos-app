<?php
    class Database {
        public static function connect() {
            $host           = getenv('DB_HOST');
            $dbname         = getenv('DB_NAME');
            $username       = getenv('DB_USER');
            $password       = getenv('DB_PASS');
            $charset        = 'utf8mb4';
            $dsn            = "mysql:host=$host;dbname=$dbname;charset=$charset";

            try {
                return new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                die(json_encode(['error' => $e->getMessage()]));
            }
        }
    }
?>