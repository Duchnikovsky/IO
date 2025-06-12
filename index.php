<?php
session_start();

require 'Routing.php';

$path = trim($_SERVER["REQUEST_URI"], '/');
$path = parse_url($path, PHP_URL_PATH);

//Pages routes
Routing::get('index', 'DefaultController');
Routing::get('register', 'DefaultController');
Routing::get('dashboard', 'DashboardController');
Routing::get('employees', 'EmployeeController');

//API routes
Routing::post('login', 'AuthController');
Routing::post('register', 'AuthController');
Routing::get('logout', 'AuthController');

Routing::run($path);
