<?php

class EmployeeRepository extends Repository
{
    public function getEmployeesByUser($userId)
    {
        $stmt = $this->db->connect()->prepare('
            SELECT id, first_name, last_name, hourly_rate
            FROM employees
            WHERE user_id = :user_id
            ORDER BY last_name
        ');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createEmployee($userId, $first, $last, $rate)
    {
        $stmt = $this->db->connect()->prepare("
        INSERT INTO employees (user_id, first_name, last_name, hourly_rate)
        VALUES (:user_id, :first_name, :last_name, :hourly_rate)
    ");
        $stmt->execute([
            'user_id' => $userId,
            'first_name' => $first,
            'last_name' => $last,
            'hourly_rate' => $rate
        ]);
    }

    public function deleteEmployee($employeeId, $userId)
    {
        $stmt = $this->db->connect()->prepare("
        DELETE FROM employees
        WHERE id = :id AND user_id = :user_id
    ");
        $stmt->execute([
            'id' => $employeeId,
            'user_id' => $userId
        ]);
    }

    public function getById($id)
    {
        $stmt = $this->db->connect()->prepare("SELECT * FROM employees WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateEmployee($id, $firstName, $lastName, $hourlyRate)
    {
        $stmt = $this->db->connect()->prepare("UPDATE employees SET first_name = :first, last_name = :last, hourly_rate = :rate WHERE id = :id");
        $stmt->execute([
            'first' => $firstName,
            'last' => $lastName,
            'rate' => $hourlyRate,
            'id' => $id
        ]);
    }

    public function update(int $id, string $firstName, string $lastName, float $hourlyRate): void
    {
        $stmt = $this->db->connect()->prepare("
        UPDATE employees
        SET first_name = :first_name,
            last_name = :last_name,
            hourly_rate = :hourly_rate
        WHERE id = :id
    ");
        $stmt->execute([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'hourly_rate' => $hourlyRate,
            'id' => $id
        ]);
    }
}
