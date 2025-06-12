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
}
