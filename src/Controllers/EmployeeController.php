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

    public function edit()
    {
        if ($this->getRequestMethod() === 'GET') {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                $this->render("Employee/list", ["error" => "Brak ID pracownika"]);
                return;
            }

            $repo = new EmployeeRepository();

            $employee = $repo->getById((int)$id);
            if (!$employee) {
                $this->render("Employee/list", ["error" => "Nie znaleziono pracownika"]);
                return;
            }

            $this->render("Employee/edit", ["employee" => $employee]);
        }

        if ($this->getRequestMethod() === 'POST') {
            $id = $_POST['id'] ?? null;
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $rate = $_POST['hourly_rate'] ?? 0;

            if (!$id || !$firstName || !$lastName || !$rate) {
                $this->render("Employee/edit", [
                    "error" => "Wszystkie pola są wymagane",
                    "employee" => $_POST
                ]);
                return;
            }
            
            $repo = new EmployeeRepository();
            $repo->update((int)$id, $firstName, $lastName, (float)$rate);

            $url = "http://$_SERVER[HTTP_HOST]/employees";
            header("Location: $url");
            exit;
        }
    }
}
