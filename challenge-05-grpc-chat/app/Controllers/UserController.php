<?php

namespace App\Controllers;

use App\Contracts\Services\AuthServiceInterface;

class UserController extends BaseController
{
    private AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function login(): void
    {
        if ($this->authService->isLoggedIn()) {
            $this->redirectResponse('/chat');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->getRequestParam('email');
            $password = $this->getRequestParam('password');

            if ($this->authService->authenticate($email, $password)) {
                $this->redirectResponse('/chat');
                return;
            } else {
                $data = [
                    'title' => 'Login',
                    'error' => 'Invalid email or password'
                ];
                $this->viewResponse('login', $data);
                return;
            }
        }

        $data = [
            'title' => 'Login'
        ];
        
        $this->viewResponse('login', $data);
    }

    public function register(): void
    {
        if ($this->authService->isLoggedIn()) {
            $this->redirectResponse('/chat');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'email' => $this->getRequestParam('email'),
                'password' => $this->getRequestParam('password'),
                'username' => $this->getRequestParam('username')
            ];

            try {
                $user = $this->authService->register($data);
                $this->redirectResponse('/login');
                return;
            } catch (\Exception $e) {
                $data = [
                    'title' => 'Register',
                    'error' => $e->getMessage()
                ];
                $this->viewResponse('register', $data);
                return;
            }
        }

        $data = [
            'title' => 'Register'
        ];
        
        $this->viewResponse('register', $data);
    }

    public function logout(): void
    {
        $this->authService->logout();
        $this->redirectResponse('/login');
    }

    public function profile(): void
    {
        $this->requireAuth();
        
        $user = $this->authService->getCurrentUser();
        
        $data = [
            'title' => 'User Profile',
            'user' => $user
        ];
        
        $this->viewResponse('users/profile', $data);
    }
}