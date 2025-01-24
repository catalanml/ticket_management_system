<?php

namespace App\Core;

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\CategoryController;
use App\Controllers\PriorityController;
use App\Controllers\TaskController;

class Router
{
    private array $routes = [];

    public function __construct()
    {
        $this->routes = [
            // Auth Routes
            ['method' => 'GET', 'path' => '/', 'controller' => [AuthController::class, 'loginForm']],
            ['method' => 'GET', 'path' => '/login', 'controller' => [AuthController::class, 'loginForm']],
            ['method' => 'POST', 'path' => '/login', 'controller' => [AuthController::class, 'login']],
            ['method' => 'GET', 'path' => '/register', 'controller' => [AuthController::class, 'registerForm']],
            ['method' => 'POST', 'path' => '/register', 'controller' => [AuthController::class, 'register']],
            ['method' => 'GET', 'path' => '/logout', 'controller' => [AuthController::class, 'logout']],

            // Dashboard Route
            ['method' => 'GET', 'path' => '/dashboard', 'controller' => [DashboardController::class, 'show']],

            // Category Routes
            ['method' => 'GET', 'path' => '/categories', 'controller' => [CategoryController::class, 'index']],
            ['method' => 'POST', 'path' => '/categories/create', 'controller' => [CategoryController::class, 'create']],
            ['method' => 'POST', 'path' => '/categories/edit', 'controller' => [CategoryController::class, 'edit']],
            ['method' => 'POST', 'path' => '/categories/delete', 'controller' => [CategoryController::class, 'delete']],

            // Priority Routes
            ['method' => 'GET', 'path' => '/priorities', 'controller' => [PriorityController::class, 'index']],
            ['method' => 'POST', 'path' => '/priorities/create', 'controller' => [PriorityController::class, 'create']],
            ['method' => 'POST', 'path' => '/priorities/edit', 'controller' => [PriorityController::class, 'edit']],
            ['method' => 'POST', 'path' => '/priorities/delete', 'controller' => [PriorityController::class, 'delete']],

            // Task Routes
            ['method' => 'GET', 'path' => '/tasks', 'controller' => [TaskController::class, 'index']],
            ['method' => 'GET', 'path' => '/tasks/createForm', 'controller' => [TaskController::class, 'showForm']],
            ['method' => 'POST', 'path' => '/tasks/create', 'controller' => [TaskController::class, 'create']],
            ['method' => 'POST', 'path' => '/tasks/edit', 'controller' => [TaskController::class, 'edit']],
            ['method' => 'POST', 'path' => '/tasks/delete', 'controller' => [TaskController::class, 'delete']],
            ['method' => 'GET', 'path' => '/tasks/detail', 'controller' => [TaskController::class, 'detail']],
            ['method' => 'POST', 'path' => '/tasks/assign', 'controller' => [TaskController::class, 'assign']],
            ['method' => 'POST', 'path' => '/tasks/complete', 'controller' => [TaskController::class, 'complete']],
            ['method' => 'GET', 'path' => '/tasks/manageTasks', 'controller' => [TaskController::class, 'manageTasks']], 
            ['method' => 'GET', 'path' => '/tasks/getAssignedUsers', 'controller' => [TaskController::class, 'getAssignedUsers']],

        ];
    }

    public function handleRequest()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        $queryString = $_SERVER['QUERY_STRING'] ?? '';


        if ($uri === '/dashboard' && $method === 'GET') {
            parse_str($queryString, $queryParams);
            if (isset($queryParams['assignedUserId'])) {
                $controller = new DashboardController();
                $controller->showMyTask($queryParams['assignedUserId']);
                return;
            }
        }

        foreach ($this->routes as $route) {
            if ($route['path'] === $uri && $route['method'] === $method) {
                [$controllerClass, $method] = $route['controller'];
                $controller = new $controllerClass();
                $controller->$method();
                return;
            }
        }

        http_response_code(404);
        echo "404 - Not Found";
    }
}
