<?php

require_once __DIR__ . '/../repositories/DashboardRepository.php';
require_once 'AppController.php';

class DashboardController extends AppController
{
    public function dashboard()
    {
        if (!$this->isLoggedIn()) {
            header('Location: /login');
            exit;
        }

        $user = $_SESSION['user'];
        $userId = $user['id'];

        $repo = new DashboardRepository();

        $data = [
            'employee_count' => $repo->countEmployees($userId),
            'total_hours' => $repo->sumHoursThisMonth($userId),
            'payroll_count' => $repo->countPayrolls($userId),
            'approved_payrolls' => $repo->countApprovedPayrolls($userId)
        ];

        $this->render("dashboard", $data);
    }
}
