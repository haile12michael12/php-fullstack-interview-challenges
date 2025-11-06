<?php

namespace Challenge02\Http\Request;

class RegisterRequest
{
    public string $email;
    public string $password;
    public string $firstName;
    public string $lastName;
    public ?string $phone;

    public function __construct(array $data)
    {
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->firstName = $data['first_name'] ?? '';
        $this->lastName = $data['last_name'] ?? '';
        $this->phone = $data['phone'] ?? null;
    }

    public function validate(): bool
    {
        $required = ['email', 'password'];
        foreach ($required as $field) {
            if (empty($this->$field)) {
                throw new \Exception("Field '{$field}' is required");
            }
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Invalid email format');
        }

        if (strlen($this->password) < 8) {
            throw new \Exception('Password must be at least 8 characters long');
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/', $this->password)) {
            throw new \Exception('Password must contain uppercase, lowercase, and number');
        }

        return true;
    }
}