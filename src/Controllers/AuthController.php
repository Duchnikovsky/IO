<?php

require_once 'AppController.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Repositories/UserRepository.php';

class AuthController extends AppController
{

    public function login()
    {
        $userRepository = new UserRepository();

        if ($this->getRequestMethod() !== 'POST') {
            $this->render("Auth/login");
            return;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $userRepository->getUserByEmail($email ?? '');

        if (!$user) {
            $this->render("Auth/login", ["error" => "User not found"]);
            return;
        }

        if ($user->getEmail() !== $email || !$user->verifyPassword($password)) {
            $this->render("Auth/login", ["error" => "Invalid email or password"]);
            return;
        }

        $url = "http://$_SERVER[HTTP_HOST]";
        $_SESSION['user'] = [
            'email' => $user->getEmail(),
            'id' => $user->getId(),
        ];
        header("Location: $url/dashboard");
        exit;
    }

    public function register()
    {
        $userRepository = new UserRepository();

        if ($this->getRequestMethod() !== 'POST') {
            $this->render("Auth/register");
            return;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->render("Auth/register", ["error" => "All fields are required"]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->render("Auth/register", ["error" => "Invalid email format"]);
            return;
        }

        if (strlen($password) < 6) {
            $this->render("Auth/register", ["error" => "Password must be at least 6 characters long"]);
            return;
        }

        if ($userRepository->getUserByEmail($email)) {
            $this->render("Auth/register", ["error" => "Email already registered"]);
            return;
        }

        $user = new User(null, $email, $password);
        $userRepository->saveUser($user);

        $this->render("Auth/login", ["success" => "Registration successful! You can now log in."]);
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header("Location: /");
        exit;
    }
}
