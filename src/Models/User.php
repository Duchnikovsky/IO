<?php

class User
{
    private $email;
    private $passwordHash;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->setPassword($password);
    }

    public static function fromDatabase($email, $passwordHash)
    {
        $instance = new self($email, '');
        $instance->passwordHash = $passwordHash;
        return $instance;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPassword($password)
    {
        $this->passwordHash = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    public function verifyPassword($password)
    {
        return password_verify($password, $this->passwordHash);
    }
}
