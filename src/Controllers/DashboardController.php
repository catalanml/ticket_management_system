<?php

namespace App\Controllers;

use App\Models\Task;
use App\Models\Priority;


class DashboardController
{
    private Task $taskModel;
    private Priority $priorityModel;

    public function __construct()
    {
        $this->taskModel = new Task();
        $this->priorityModel = new Priority();
    }

    public function addTaskDetails($tasks)
    {
        foreach ($tasks as $key => $task) {
            $tasks[$key]['status'] = $this->taskModel->isTaskCompleted($task['id']) ? 'completed' : 'pending';

            $priority = $this->priorityModel->getPriorityById($task['priority_id']);
            $tasks[$key]['priority_name'] = $priority['name'];
            $tasks[$key]['priority_type'] = $priority['type'];

            $tasks[$key]['status'] = $this->taskModel->isTaskCompleted($task['id']) ? 'completed' : 'pending';
        }

        return $tasks;
    }

    /**
     * Mostrar todas las tareas asignadas al usuario.
     */
    public function show()
    {
        session_start();
        $userId = $_SESSION['user_id'] ?? null;


        if (!$userId) {
            header("Location: /login");
            exit;
        }

        $tasks = $this->taskModel->getAllTasks();

        $tasks = $this->addTaskDetails($tasks);

        $user = $_SESSION['user'] ?? ['firstname' => 'Usuario', 'lastname' => ''];
        require __DIR__ . '/../Views/dashboard.php';
    }

    public function showMyTask($assignedUserId)
    {
        session_start();

        $tasks = $this->taskModel->getAssignedTasks($assignedUserId);

        $tasks = $this->addTaskDetails($tasks);


        $user = $_SESSION['user'] ?? ['firstname' => 'Usuario', 'lastname' => ''];
        require __DIR__ . '/../Views/dashboard.php'; // Cambia a la vista que prefieras
    }
}
