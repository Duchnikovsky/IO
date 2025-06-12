<?php

class DashboardRepository extends Repository
{
    public function countEmployees($userId)
    {
        $stmt = $this->db->connect()->prepare(
            'SELECT COUNT(*) FROM employees WHERE user_id = :user_id'
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchColumn();
    }

    public function sumHoursThisMonth($userId)
    {
        $stmt = $this->db->connect()->prepare('
            SELECT COALESCE(SUM(w.hours_worked), 0)
            FROM work_logs w
            JOIN employees e ON w.employee_id = e.id
            WHERE e.user_id = :user_id
              AND EXTRACT(MONTH FROM w.date) = EXTRACT(MONTH FROM CURRENT_DATE)
              AND EXTRACT(YEAR FROM w.date) = EXTRACT(YEAR FROM CURRENT_DATE)
        ');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchColumn();
    }

    public function countPayrolls($userId)
    {
        $stmt = $this->db->connect()->prepare('
            SELECT COUNT(p.id)
            FROM payrolls p
            JOIN employees e ON p.employee_id = e.id
            WHERE e.user_id = :user_id
        ');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchColumn();
    }

    public function countApprovedPayrolls($userId)
    {
        $stmt = $this->db->connect()->prepare('
            SELECT COUNT(p.id)
            FROM payrolls p
            JOIN employees e ON p.employee_id = e.id
            WHERE e.user_id = :user_id AND p.is_approved = TRUE
        ');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchColumn();
    }
}
