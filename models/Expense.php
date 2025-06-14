<?php
    require_once __DIR__ . '/../config/config.php';

    class Expense {
        private $db;

        public function __construct() {
            $this->db = database::connect();
        }
    }
?>