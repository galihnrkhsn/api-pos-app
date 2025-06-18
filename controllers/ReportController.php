<?php
    require_once __DIR__ . '/../models/Report.php';

    class ReportController{
        private $transactionModel;
        private $expenseModel;

        public function __construct() {
            $this->transactionModel = new Report();
            $this->expenseModel = new Expense();
        }

        public function reportSalesSummary() {
            $date       = $_GET['date'] ?? null;
            $month      = $_GET['month'] ?? null;
            $year       = $_GET['year'] ?? null;
            $groupBy    = $_GET['groupBy'] ?? null;

            $groupByPayment = ($groupBy === 'payment_type');

            if ($month && $year) {
                $report     = $this->transactionModel->getSalesSummary('monthly', $year, $month, $groupByPayment);
                $top        = $this->transactionModel->getTopSelling('monthly', $year, $month);
                $expenses   = $this->expenseModel->getTotalExpenseByDate('monthly', $year, $month);
            } elseif ($date) {
                $report     = $this->transactionModel->getSalesSummary('daily', $date, null, $groupByPayment);
                $top        = $this->transactionModel->getTopSelling('daily', $date);
                $expenses   = $this->expenseModel->getTotalExpenseByDate($date, null);
            } else {
                $today      = date('Y-m-d');
                $report     = $this->transactionModel->getSalesSummary('daily', $today, null, $groupByPayment);
                $top        = $this->transactionModel->getTopSelling('daily', $today);
                $expenses   = $this->expenseModel->getTotalExpenseByDate($date, null);

            }

            $grouped = [];

            if ($groupByPayment === true) {
                foreach ($report as $rep) {
                    $type = $rep['paymentType'];
                    unset($rep['paymentType']);
                    $grouped[$type] = $rep;
                }
            } else {
                $grouped = $report;
            }

            $grouped['topBuying']       = $top ?? null;
            $grouped['total_expense']   = $expenses ?? 0;

            echo json_encode([
                'status' => 'success',
                'data' => $grouped
            ]);
        }
    }
?>