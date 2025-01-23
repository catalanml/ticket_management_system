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

        $tasks = $this->taskModel->getAssignedTasks($userId);

        $user = $_SESSION['user'] ?? ['firstname' => 'Usuario', 'lastname' => ''];
        require __DIR__ . '/../Views/dashboard.php';
    }
}