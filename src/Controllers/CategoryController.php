<?php

namespace App\Controllers;

use App\Models\Category;

class CategoryController
{
    private Category $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
    }


    public function index()
    {
        session_start();
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            header("Location: /login");
            exit;
        }

        $categories = $this->categoryModel->getAllCategories();
        require __DIR__ . '/../Views/categories/categories.php';
    }


    public function create()
    {
        session_start();
        $this->sendJsonHeaders();

        $input = $this->getJsonInput();

        $name = $input['name'] ?? '';
        $createdBy = (int)($_SESSION['user_id'] ?? 0);

        if (empty($name)) {
            $this->sendJsonResponse(['status' => 'error', 'message' => 'El nombre de la categoría es obligatorio.']);
            return;
        }

        if ($createdBy === 0) {
            $this->sendJsonResponse(['status' => 'error', 'message' => 'Usuario no autenticado.']);
            return;
        }

        try {
            $this->categoryModel->createCategory($name, $createdBy);
            $lastId = $this->categoryModel->getLastInsertedId();
            $this->sendJsonResponse([
                'status' => 'success',
                'message' => 'Categoría creada con éxito.',
                'category' => ['id' => $lastId, 'name' => $name]
            ]);
        } catch (\PDOException $e) {

            $this->sendJsonResponse([
                'status' => 'error',
                'message' => 'No se pudo crear la categoría. Detalles: ' . $e->getMessage()
            ]);
        } catch (\Exception $e) {
            $this->sendJsonResponse([
                'status' => 'error',
                'message' => 'Ocurrió un error inesperado. Detalles: ' . $e->getMessage()
            ]);
        }
    }

 
    public function edit()
    {
        session_start();
        $this->sendJsonHeaders();

        $input = $this->getJsonInput();
        $id = (int)($input['id'] ?? 0);
        $name = $input['name'] ?? '';
        $editedBy = (int)($_SESSION['user_id'] ?? 0);

        if ($id === 0 || empty($name)) {
            $this->sendJsonResponse(['status' => 'error', 'message' => 'El ID y el nombre de la categoría son obligatorios.']);
            return;
        }

        try {
            $this->categoryModel->updateCategory($id, $name, $editedBy);
            $this->sendJsonResponse([
                'status' => 'success',
                'message' => 'Categoría actualizada con éxito.'
            ]);
        } catch (\Exception $e) {
            $this->sendJsonResponse([
                'status' => 'error',
                'message' => 'No se pudo actualizar la categoría. Detalles: ' . $e->getMessage()
            ]);
        }
    }

    public function delete()
    {
        session_start();
        $this->sendJsonHeaders();

        $input = $this->getJsonInput();
        $id = (int)($input['id'] ?? 0);

        if ($id === 0) {
            $this->sendJsonResponse(['status' => 'error', 'message' => 'El ID de la categoría es obligatorio.']);
            return;
        }

        try {
            $this->categoryModel->deleteCategory($id);
            $this->sendJsonResponse([
                'status' => 'success',
                'message' => 'Categoría eliminada con éxito.'
            ]);
        } catch (\PDOException $e) {
            $this->sendJsonResponse([
                'status' => 'error',
                'message' => 'No se pudo eliminar la categoría. Detalles: ' . $e->getMessage()
            ]);
        } catch (\Exception $e) {
            $this->sendJsonResponse([
                'status' => 'error',
                'message' => 'Ocurrió un error inesperado. Detalles: ' . $e->getMessage()
            ]);
        }
    }


    private function sendJsonHeaders()
    {
        header('Content-Type: application/json');
    }


    private function getJsonInput(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }


    private function sendJsonResponse(array $response)
    {
        echo json_encode($response);
        exit;
    }
}
