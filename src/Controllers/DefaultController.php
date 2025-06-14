<?php

require_once 'AppController.php';

class DefaultController extends AppController
{

    public function index()
    {
        if ($this->isLoggedIn()) {
            header("Location: /dashboard");
            exit;
        }
        $this->render("Auth/login");
    }

    public function dashboard()
    {
        if (!$this->isLoggedIn()) {
            header("Location: /");
            exit;
        }

        $this->render("dashboard");
    }

    public function register()
    {
        if ($this->isLoggedIn()) {
            header("Location: /dashboard");
            exit;
        }

        $this->render("Auth/register");
    }
}
