<?php

namespace App\Controllers;

use App\Models\Task;


class DashboardController
{
    private Task $taskModel;


    public function __construct()
    {
        $this->taskModel = new Task();
    }



    public function show()
    {
        session_start();
        $userId = $_SESSION['user_id'] ?? null;


        if (!$userId) {
            header("Location: /login");
            exit;
        }

        $tasks = $this->taskModel->getAllTasks();

        $tasks = $this->taskModel->addTaskDetails($tasks);

        $user = $_SESSION['user'] ?? ['firstname' => 'Usuario', 'lastname' => ''];
        require __DIR__ . '/../Views/dashboard.php';
    }

    public function showMyTask($assignedUserId)
    {
        session_start();

        $tasks = $this->taskModel->getAssignedTasks($assignedUserId);
        $tasks = $this->taskModel->addTaskDetails($tasks);

        $user = $_SESSION['user'];

        require __DIR__ . '/../Views/dashboard.php';
    }
}
