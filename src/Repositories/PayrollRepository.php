<?php

class PayrollRepository extends Repository
{
    public function generatePayroll(int $employeeId, string $fromDate, string $toDate): void
    {
        $stmt = $this->db->connect()->prepare("
            SELECT SUM(hours_worked) AS hours
            FROM work_logs
            WHERE employee_id = :id
              AND date BETWEEN :from AND :to
        ");
        $stmt->execute([
            'id' => $employeeId,
            'from' => $fromDate,
            'to' => $toDate
        ]);
        $totalHours = $stmt->fetchColumn() ?? 0;

        $rateStmt = $this->db->connect()->prepare("
            SELECT hourly_rate FROM employees WHERE id = :id
        ");
        $rateStmt->execute(['id' => $employeeId]);
        $rate = $rateStmt->fetchColumn();

        $totalPayment = $totalHours * $rate;

        $stmt = $this->db->connect()->prepare("
            INSERT INTO payrolls (employee_id, from_date, to_date, total_hours, total_payment)
            VALUES (:employee_id, :from, :to, :hours, :payment)
            ON CONFLICT (employee_id, from_date, to_date)
            DO UPDATE SET total_hours = EXCLUDED.total_hours,
                          total_payment = EXCLUDED.total_payment
        ");
        $stmt->execute([
            'employee_id' => $employeeId,
            'from' => $fromDate,
            'to' => $toDate,
            'hours' => $totalHours,
            'payment' => $totalPayment
        ]);
    }

    public function getPayrollsByUser(int $userId): array
    {
        $stmt = $this->db->connect()->prepare("
            SELECT p.*, e.first_name, e.last_name
            FROM payrolls p
            JOIN employees e ON p.employee_id = e.id
            WHERE e.user_id = :user_id
            ORDER BY from_date DESC
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus(int $payrollId, string $field): void
    {
        $column = match ($field) {
            'approve' => 'is_approved',
            'pay' => 'is_paid',
            default => null
        };

        if (!$column) return;

        $stmt = $this->db->connect()->prepare("
            UPDATE payrolls SET $column = TRUE WHERE id = :id
        ");
        $stmt->execute(['id' => $payrollId]);
    }
}
