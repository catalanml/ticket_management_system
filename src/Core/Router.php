<?php

namespace App\Core;

use App\Controllers\AuthController;

class Router
{
    public function handleRequest()
    {
        $path = $_SERVER['REQUEST_URI'];

        if ($path === '/register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AuthController();
            $controller->register();
        } elseif ($path === '/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AuthController();
            $controller->login();
        } elseif ($path === '/logout') {
            $controller = new AuthController();
            $controller->logout();
        }

        if ($path === '/' || $path === '/login') {
            $controller = new AuthController();
            $controller->loginForm();
        } elseif ($path === '/register') {
            $controller = new AuthController();
            $controller->registerForm();
        }
    }

}
