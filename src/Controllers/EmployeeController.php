<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repositories/EmployeeRepository.php';

class EmployeeController extends AppController
{
    public function employees()
    {
        if (!$this->isLoggedIn()) {
            header('Location: /Auth/login');
            exit;
        }

        $user = $_SESSION['user'];
        $repo = new EmployeeRepository();

        $employees = $repo->getEmployeesByUser($user['id']);

        $this->render("Employee/list", ['employees' => $employees]);
    }

    public function add()
    {
        if (!$this->isLoggedIn()) {
            header('Location: /');
            exit;
        }

        if ($this->getRequestMethod() === 'GET') {
            $this->render("Employee/add");
            return;
        }

      
        $first = $_POST['first_name'] ?? '';
        $last = $_POST['last_name'] ?? '';
        $rate = $_POST['hourly_rate'] ?? '';

        if (empty($first) || empty($last) || !is_numeric($rate)) {
            $this->render("Employee/add", ['error' => 'Wszystkie pola są wymagane.']);
            return;
        }

        $repo = new EmployeeRepository();
        $repo->createEmployee($_SESSION['user']['id'], $first, $last, $rate);

        header("Location: /employees");
    
    }

    public function delete()
    {
        if (!$this->isLoggedIn()) {
            header('Location: /Auth/login');
            exit;
        }

        $id = $_POST['employee_id'] ?? null;
        $userId = $_SESSION['user']['id'];

        if ($id) {
            $repo = new EmployeeRepository();
            $repo->deleteEmployee($id, $userId);
        }

        header("Location: /employees");
    }
}
