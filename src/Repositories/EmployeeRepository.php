<?php

class EmployeeRepository extends Repository
{
    public function getEmployeesByUser($userId)
    {
        $stmt = $this->db->connect()->prepare("
        SELECT 
            e.id,
            e.first_name,
            e.last_name,
            e.hourly_rate,
            COALESCE(SUM(w.hours_worked), 0) AS monthly_hours
        FROM employees e
        LEFT JOIN work_logs w ON e.id = w.employee_id
            AND DATE_TRUNC('month', w.date) = DATE_TRUNC('month', CURRENT_DATE)
        WHERE e.user_id = :user_id
        GROUP BY e.id
        ORDER BY e.last_name
    ");
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

    public function logHours(int $employeeId, string $date, float $hours): void
    {
        $stmt = $this->db->connect()->prepare("
        INSERT INTO work_logs (employee_id, date, hours_worked)
        VALUES (:employee_id, :date, :hours)
        ON CONFLICT (employee_id, date)
        DO UPDATE SET hours_worked = EXCLUDED.hours_worked
    ");
        $stmt->execute([
            'employee_id' => $employeeId,
            'date' => $date,
            'hours' => $hours
        ]);
    }
}
