<?php
session_start();

require 'Routing.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$path = trim($path, '/');

if ($path === '') {
    $path = 'index';
}

/* Pages */
Routing::get('index',      'DefaultController');
Routing::get('register',   'DefaultController');
Routing::get('dashboard',  'DashboardController');
Routing::get('employees',  'EmployeeController');
Routing::get('add',        'EmployeeController');
Routing::get('edit',       'EmployeeController');
Routing::get('logHours', 'EmployeeController');

/* API */
Routing::post('login',     'AuthController');
Routing::post('register',  'AuthController');
Routing::get('logout',    'AuthController');
Routing::post('add',       'EmployeeController');
Routing::post('delete',    'EmployeeController');
Routing::post('edit',    'EmployeeController');
Routing::post('logHours', 'EmployeeController');

Routing::run($path);
