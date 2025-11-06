<?php

namespace Challenge02\Http\Request;

class LoginRequest
{
    public string $email;
    public string $password;
    public bool $rememberMe;

    public function __construct(array $data)
    {
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->rememberMe = (bool) ($data['remember_me'] ?? false);
    }

    public function validate(): bool
    {
        if (empty($this->email) || empty($this->password)) {
            throw new \Exception('Email and password are required');
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Invalid email format');
        }

        return true;
    }
}