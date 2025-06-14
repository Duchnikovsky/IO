<?php

require_once 'Database.php';

$db = (new Database())->connect();

$db->exec("
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
");

$db->exec("
CREATE TABLE IF NOT EXISTS employees (
    id SERIAL PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    hourly_rate NUMERIC(10, 2) NOT NULL,
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
");

$db->exec("
CREATE TABLE IF NOT EXISTS work_logs (
    id SERIAL PRIMARY KEY,
    employee_id INT NOT NULL REFERENCES employees(id) ON DELETE CASCADE,
    date DATE NOT NULL,
    hours_worked NUMERIC(5, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
");

$db->exec(
    "
    CREATE UNIQUE INDEX IF NOT EXISTS uniq_employee_date
    ON work_logs(employee_id, date
);"
);

$db->exec("
CREATE TABLE IF NOT EXISTS payrolls (
    id SERIAL PRIMARY KEY,
    employee_id INT NOT NULL REFERENCES employees(id) ON DELETE CASCADE,
    from_date DATE NOT NULL,
    to_date DATE NOT NULL,
    total_hours NUMERIC(5, 2) NOT NULL,
    total_payment NUMERIC(10, 2) NOT NULL,
    is_approved BOOLEAN DEFAULT FALSE,
    is_paid BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
");

$db->exec("
ALTER TABLE payrolls
ADD CONSTRAINT unique_employee_month UNIQUE (employee_id, from_date, to_date
);
");

$db->exec("
CREATE TABLE IF NOT EXISTS events (
    id SERIAL PRIMARY KEY,
    entity_type VARCHAR(50) NOT NULL,
    entity_id INT NOT NULL,
    event_type VARCHAR(100) NOT NULL,
    payload JSONB,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
");

echo "Setup complete! All tables are ready.\n";
