<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function index(): void
    {
        $data = [
            'title' => 'Welcome to gRPC Chat',
            'message' => 'This is a real-time chat application built with gRPC'
        ];
        
        $this->viewResponse('home', $data);
    }

    public function about(): void
    {
        $data = [
            'title' => 'About gRPC Chat',
            'content' => 'Learn more about our gRPC-based chat application'
        ];
        
        $this->viewResponse('about', $data);
    }
}