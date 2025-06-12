<?php
session_start();

require 'Routing.php';

$path = trim($_SERVER["REQUEST_URI"], '/');
$path = parse_url($path, PHP_URL_PATH);

//Pages routes
Routing::get('index', 'DefaultController');
Routing::get('register', 'DefaultController');

//API routes
Routing::post('login', 'AuthController');
Routing::post('register', 'AuthController');

Routing::run($path);
