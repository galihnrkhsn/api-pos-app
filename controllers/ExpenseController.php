<?php
    require_once __DIR__ . '/../models/Expense.php';

    class ExpenseController {
        private $expenseModel;

        public function __construct() {
            $this->expenseModel = new Expense();
        }

        public function index() {

        }

        public function store() {
            
        }
    }
?>