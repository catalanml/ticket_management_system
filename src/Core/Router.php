<?php

namespace App\Core;

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\CategoryController;
use App\Controllers\PriorityController;

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
        } elseif ($path === '/dashboard') {
            $controller = new DashboardController();
            $controller->show();
        } elseif ($path === '/categories') {
            $controller = new CategoryController();
            $controller->index();
        } elseif ($path === '/categories/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new CategoryController();
            $controller->create();
        } elseif ($path === '/categories/edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new CategoryController();
            $controller->edit();
        } elseif ($path === '/categories/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new CategoryController();
            $controller->delete();
        } elseif ($path === '/priorities') {
            $controller = new PriorityController();
            $controller->index();
        } elseif ($path === '/priorities/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new PriorityController();
            $controller->create();
        } elseif ($path === '/priorities/edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new PriorityController();
            $controller->edit();
        } elseif ($path === '/priorities/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new PriorityController();
            $controller->delete();
        }



    }

}
