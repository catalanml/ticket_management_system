<?php

namespace App\Controllers;

use App\Models\Task;
use App\Models\Category;
use App\Models\Priority;
use App\Models\User;
use Exception;

class TaskController
{
    private Task $taskModel;
    private Category $categoryModel;
    private Priority $priorityModel;
    private User $userModel;

    public function __construct()
    {
        $this->taskModel = new Task();
        $this->categoryModel = new Category();
        $this->priorityModel = new Priority();
        $this->userModel = new User();
    }


    public function index()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        require __DIR__ . '/../Views/tasks/tasks.php';
    }

    public function showForm()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $userId = $_SESSION['user_id'];

        $categories = $this->categoryModel->getAllCategories();
        $priorities = $this->priorityModel->getAllPriorities();
        $tasks = $this->taskModel->getAllTasks();

        require __DIR__ . '/../Views/tasks/create_task.php';
    }


    public function create()
    {
        session_start();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);

        $title = $input['title'] ?? '';
        $description = $input['description'] ?? '';
        $observation = $input['observation'] ?? null;
        $priorityId = $input['priority_id'] ?? null;
        $categoryId = $input['category_id'] ?? null;
        $deadlineDate = $input['deadline_date'] ?? null;
        $createdBy = $_SESSION['user_id'] ?? null;


        if (empty($title) || empty($description) || empty($priorityId) || empty($categoryId) || empty($deadlineDate)) {
            echo json_encode(['status' => 'error', 'message' => 'Todos los campos obligatorios deben estar llenos.']);
            return;
        }

        try {
            $this->taskModel->createTask([
                'title' => $title,
                'description' => $description,
                'observation' => $observation,
                'priority_id' => $priorityId,
                'category_id' => $categoryId,
                'deadline_date' => $deadlineDate,
                'user_id' => $createdBy,
                'created_by' => $createdBy
            ]);

            $lastId = $this->taskModel->getLastInsertedId();
            $newTask = $this->taskModel->getTaskById($lastId);

            echo json_encode([
                'status' => 'success',
                'message' => 'Tarea creada',
                'task' => $newTask
            ]);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo crear la tarea. Detalles: ' . $e->getMessage()]);
        }
    }


    public function edit()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        // Obtener el ID de la tarea desde los parámetros de la URL
        $taskId = $_GET['id'] ?? null;

        if (!$taskId) {
            http_response_code(400);
            echo "El ID de la tarea es obligatorio.";
            return;
        }

        // Consultar los datos de la tarea
        $task = $this->taskModel->getTaskById($taskId);

        if (!$task) {
            http_response_code(404);
            echo "Tarea no encontrada.";
            return;
        }

        // Obtener datos adicionales (categorías y prioridades)
        $categories = $this->categoryModel->getAllCategories();
        $priorities = $this->priorityModel->getAllPriorities();

        require __DIR__ . '/../Views/tasks/edit_task.php';
    }

    public function update()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        // Leer datos de la solicitud POST
        $input = json_decode(file_get_contents('php://input'), true);

        $id = $input['id'] ?? null;
        $title = $input['title'] ?? '';
        $description = $input['description'] ?? '';
        $priorityId = $input['priority_id'] ?? null;
        $categoryId = $input['category_id'] ?? null;
        $deadlineDate = $input['deadline_date'] ?? null;
        $editedBy = $_SESSION['user_id'];

        if (!$id || empty($title) || empty($description) || !$priorityId || !$categoryId || empty($deadlineDate)) {
            echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
            return;
        }

        try {
            // Actualizar la tarea
            $this->taskModel->updateTask([
                'id' => $id,
                'title' => $title,
                'description' => $description,
                'priority_id' => $priorityId,
                'category_id' => $categoryId,
                'deadline_date' => $deadlineDate,
                'edited_by' => $editedBy
            ]);

            echo json_encode(['status' => 'success', 'message' => 'Tarea actualizada con éxito.']);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la tarea. Detalles: ' . $e->getMessage()]);
        }
    }

    public function delete()
    {
        session_start();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);

        $id = $input['id'] ?? null;

        if (empty($id)) {
            echo json_encode(['status' => 'error', 'message' => 'El ID de la tarea es obligatorio.']);
            return;
        }

        try {
            $this->taskModel->deleteTask($id);
            echo json_encode(['status' => 'success', 'message' => 'Tarea eliminada con éxito.']);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar la tarea. Detalles: ' . $e->getMessage()]);
        }
    }

    public function detail()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $taskId = $_GET['id'] ?? null;

        if (!$taskId) {
            http_response_code(400);
            echo "El ID de la tarea es obligatorio.";
            return;
        }

        $task = $this->taskModel->getTaskById($taskId);

        if (!$task) {
            http_response_code(404);
            echo "Tarea no encontrada.";
            return;
        }

        $task['status'] = $this->taskModel->isTaskCompleted($task['id']) ? 'completed' : 'pending';
        $priority = $this->priorityModel->getPriorityById($task['priority_id']);
        $task['priority_name'] = $priority['name'];
        $task['priority_type'] = $priority['type'];
        $task['category_name'] = $this->categoryModel->getCategoryById($task['category_id'])['name'];
        $assignedUserId = $this->taskModel->getAssignedUsertoTask($task['id']);
        $task['assigned_user_id'] = (is_null($assignedUserId) ? null : $assignedUserId);

        $userId = $_SESSION['user_id'];

        require __DIR__ . '/../Views/tasks/detail.php';
    }

    public function assign()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Content-Type: application/json");
            echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado.']);
            exit;
        }

        $createdBy = $_SESSION['user_id'];
        $input = json_decode(file_get_contents('php://input'), true);

        $taskId = $input['task_id'] ?? null;
        $userId = $input['user_id'] ?? null;

        if (!$taskId || !$userId) {
            header("Content-Type: application/json");
            echo json_encode(['status' => 'error', 'message' => 'Tarea y usuario son obligatorios.']);
            exit;
        }

        try {
            $this->taskModel->assignUserToTask($taskId, $userId, $createdBy);
            header("Content-Type: application/json");
            echo json_encode(['status' => 'success', 'message' => 'Usuario asignado correctamente.']);
        } catch (\Exception $e) {
            header("Content-Type: application/json");
            echo json_encode(['status' => 'error', 'message' => 'No se pudo asignar el usuario. Detalles: ' . $e->getMessage()]);
        }
    }

    public function complete()
    {
        session_start();
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'No has iniciado sesión.']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        $taskId = $input['id'] ?? null;
        $status = $input['status'] ?? null;
        $observation = $input['observation'] ?? null;
        $userId = $_SESSION['user_id'];

        if (!$taskId || !$status) {
            echo json_encode(['status' => 'error', 'message' => 'El ID de la tarea y el estado son obligatorios.']);
            return;
        }

        try {

            $assignedTask = $this->taskModel->getAssignedUsertoTask($taskId);


            if (!$assignedTask || $assignedTask !== $userId) {
                echo json_encode(['status' => 'error', 'message' => 'No estás autorizado para completar esta tarea.']);
                return;
            }

            // Actualizar el estado de la tarea como completada
            $this->taskModel->updateTaskStatus($taskId, $userId, $status, $observation);

            echo json_encode(['status' => 'success', 'message' => 'Tarea completada con éxito.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al completar la tarea: ' . $e->getMessage()]);
        }
    }

    public function manageTasks()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $tasks = $this->taskModel->getAllTasks();
        $tasks = $this->taskModel->addTaskDetails($tasks);

        $users = $this->userModel->getAllUsers();

        require __DIR__ . '/../Views/tasks/manage_tasks.php';
    }
}
