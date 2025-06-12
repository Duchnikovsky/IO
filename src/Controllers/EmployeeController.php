<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repositories/EmployeeRepository.php';

class EmployeeController extends AppController
{
    public function employees()
    {
        if (!$this->isLoggedIn()) {
            header('Location: /login');
            exit;
        }

        $user = $_SESSION['user'];
        $repo = new EmployeeRepository();

        $employees = $repo->getEmployeesByUser($user['id']);

        $this->render("Employee/list", ['employees' => $employees]);
    }
}
