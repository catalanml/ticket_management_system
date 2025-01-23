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

        $user = $_SESSION['user'] ?? ['firstname' => 'Usuario', 'lastname' => ''];
        require __DIR__ . '/../Views/dashboard.php';
    }

    public function showMyTask($assignedUserId)
    {
        session_start();

        $tasks = $this->taskModel->getAssignedTasks($assignedUserId);

        // Datos del usuario logueado
        $user = $_SESSION['user'] ?? ['firstname' => 'Usuario', 'lastname' => ''];
        require __DIR__ . '/../Views/dashboard.php'; // Cambia a la vista que prefieras
    }
}
