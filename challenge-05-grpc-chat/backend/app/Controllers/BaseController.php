<?php

namespace App\Controllers;

use App\Core\Controller;

class BaseController extends Controller
{
    protected function jsonResponse(array $data, int $statusCode = 200): void
    {
        $this->json($data, $statusCode);
    }

    protected function viewResponse(string $view, array $data = []): void
    {
        $this->render($view, $data);
    }

    protected function redirectResponse(string $url): void
    {
        $this->redirect($url);
    }

    protected function getCurrentUserId(): ?int
    {
        // In a real implementation, you would get this from the session or token
        return $_SESSION['user_id'] ?? null;
    }

    protected function requireAuth(): void
    {
        if (!$this->getCurrentUserId()) {
            $this->redirectResponse('/login');
            exit;
        }
    }

    protected function getRequestParam(string $key, $default = null)
    {
        return $_REQUEST[$key] ?? $default;
    }

    protected function getPostData(): array
    {
        return $_POST;
    }

    protected function getQueryParams(): array
    {
        return $_GET;
    }
}