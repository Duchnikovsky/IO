<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repositories/PayrollRepository.php';
require_once __DIR__ . '/../repositories/EmployeeRepository.php';

class PayrollController extends AppController
{
    public function payrolls()
    {
        if (!$this->isLoggedIn()) {
            header("Location: /");
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $repo = new PayrollRepository();
        $payrolls = $repo->getPayrollsByUser($userId);

        $this->render("Payroll/payroll", ['payrolls' => $payrolls]);
    }

    public function generate()
    {
        if (!$this->isLoggedIn()) {
            header("Location: /");
            exit;
        }

        $employeeId = $_GET['id'] ?? null;
        $month = $_GET['month'] ?? date('Y-m');

        if (!$employeeId) {
            header("Location: /employees");
            exit;
        }

        $from = "$month-01";
        $to = date("Y-m-t", strtotime($from));

        $repo = new PayrollRepository();
        $repo->generatePayroll((int)$employeeId, $from, $to);

        header("Location: /payrolls");
        exit;
    }

    public function updateStatus()
    {
        if (!$this->isLoggedIn()) {
            header("Location: /");
            exit;
        }

        $id = $_POST['payroll_id'] ?? null;
        $field = $_POST['action'] ?? null;

        if ($id && in_array($field, ['approve', 'pay'])) {
            $repo = new PayrollRepository();
            $repo->updateStatus((int)$id, $field);
        }

        header("Location: /payrolls");
    }
}
