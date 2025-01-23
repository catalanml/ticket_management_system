<?php

namespace App\Controllers;

use App\Models\Task;

class TaskController
{
    private Task $taskModel;

    public function __construct()
    {
        $this->taskModel = new Task();
    }

    public function index()
    {
        $tasks = $this->taskModel->getAllTasks();
        require __DIR__ . '/../Views/tasks/index.php';
    }
}
