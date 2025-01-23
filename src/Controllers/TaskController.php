<?php

namespace App\Controllers;

use App\Models\Task;
use App\Models\Category;
use App\Models\Priority;

class TaskController
{
    private Task $taskModel;
    private Category $categoryModel;
    private Priority $priorityModel;

    public function __construct()
    {
        $this->taskModel = new Task();
        $this->categoryModel = new Category();
        $this->priorityModel = new Priority();
    }

    /**
     * Mostrar la vista principal de tareas.
     */
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

    /**
     * Crear una nueva tarea.
     */
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
        $createdBy = $_SESSION['user_id'] ?? null;

        if (empty($title) || empty($description) || empty($priorityId) || empty($categoryId)) {
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
                'user_id' => $createdBy, // Asociamos la tarea al usuario actual
                'created_by' => $createdBy
            ]);

            $lastId = $this->taskModel->getLastInsertedId();
            $newTask = $this->taskModel->getTaskById($lastId);

            echo json_encode([
                'status' => 'success',
                'message' => 'Tarea creada con éxito.',
                'task' => $newTask
            ]);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo crear la tarea. Detalles: ' . $e->getMessage()]);
        }
    }

    /**
     * Editar una tarea existente.
     */
    public function edit()
    {
        session_start();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);

        $id = $input['id'] ?? null;
        $title = $input['title'] ?? '';
        $description = $input['description'] ?? '';
        $observation = $input['observation'] ?? null;
        $priorityId = $input['priority_id'] ?? null;
        $categoryId = $input['category_id'] ?? null;
        $editedBy = $_SESSION['user_id'] ?? null;

        if (empty($id) || empty($title) || empty($description) || empty($priorityId) || empty($categoryId)) {
            echo json_encode(['status' => 'error', 'message' => 'Todos los campos obligatorios deben estar llenos.']);
            return;
        }

        try {
            $this->taskModel->updateTask([
                'id' => $id,
                'title' => $title,
                'description' => $description,
                'observation' => $observation,
                'priority_id' => $priorityId,
                'category_id' => $categoryId,
                'edited_by' => $editedBy
            ]);

            echo json_encode(['status' => 'success', 'message' => 'Tarea actualizada con éxito.']);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la tarea. Detalles: ' . $e->getMessage()]);
        }
    }

    /**
     * Eliminar una tarea.
     */
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
}
