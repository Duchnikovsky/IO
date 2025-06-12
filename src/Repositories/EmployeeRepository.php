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
}
