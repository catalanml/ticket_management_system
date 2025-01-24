<?php

namespace App\Controllers;

use App\Core\Model;
use App\Models\Priority;

class PriorityController
{
    private Priority $PriorityModel;

    public function __construct()
    {
        $this->PriorityModel = new Priority();
    }

    /**
     * Mostrar la vista principal de prioridades.
     */
    public function index()
    {
        session_start();
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            header("Location: /login");
            exit;
        }

        $priorities = $this->PriorityModel->getAllPriorities();
        require __DIR__ . '/../Views/priorities/priorities.php';
    }

    /**
     * Crear una nueva Prioridad.
     */
    public function create()
    {
        session_start();
        $this->sendJsonHeaders();

        $input = $this->getJsonInput();

        $name = $input['name'] ?? '';
        $type = $input['prioritytype'] ?? '';
        $createdBy = (int)($_SESSION['user_id'] ?? 0);

        if (empty($name)) {
            $this->sendJsonResponse(['status' => 'error', 'message' => 'El nombre de la prioridad es obligatorio.']);
            return;
        }


        if (empty($type)) {
            $this->sendJsonResponse(['status' => 'error', 'message' => 'El tipo de la prioridad es obligatorio.']);
            return;
        }

        if ($createdBy === 0) {
            $this->sendJsonResponse(['status' => 'error', 'message' => 'Usuario no autenticado.']);
            return;
        }

        try {
            $this->PriorityModel->createPriority($name, $type, $createdBy);
            $lastId = $this->PriorityModel->getLastInsertedId();
            $this->sendJsonResponse([
                'status' => 'success',
                'message' => 'Prioridad creada con éxito.',
                'priority' => ['id' => $lastId, 'name' => $name]
            ]);
        } catch (\PDOException $e) {

            $this->sendJsonResponse([
                'status' => 'error',
                'message' => 'No se pudo crear la prioridad. Detalles: ' . $e->getMessage()
            ]);
        } catch (\Exception $e) {
            $this->sendJsonResponse([
                'status' => 'error',
                'message' => 'Ocurrió un error inesperado. Detalles: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Editar una prioridad existente.
     */
    public function edit()
    {
        session_start();
        $this->sendJsonHeaders();

        $input = $this->getJsonInput();
        $id = (int)($input['id'] ?? 0);
        $name = $input['name'] ?? '';
        $type = $input['prioritytype'] ?? '';
        $editedBy = (int)($_SESSION['user_id'] ?? 0);


        try {
            $this->PriorityModel->updatePriority($id, $name, $type,  $editedBy);
            $this->sendJsonResponse([
                'status' => 'success',
                'message' => 'Prioridad actualizada con éxito.'
            ]);
        } catch (\Exception $e) {
            $this->sendJsonResponse([
                'status' => 'error',
                'message' => 'No se pudo actualizar la prioridad. Detalles: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar una Prioridad.
     */
    public function delete()
    {
        session_start();
        $this->sendJsonHeaders();

        $input = $this->getJsonInput();
        $id = (int)($input['id'] ?? 0);

        if ($id === 0) {
            $this->sendJsonResponse(['status' => 'error', 'message' => 'El ID de la prioridad es obligatorio.']);
            return;
        }

        try {
            $this->PriorityModel->deletePriority($id);
            $this->sendJsonResponse([
                'status' => 'success',
                'message' => 'Prioridad eliminada con éxito.'
            ]);
        } catch (\PDOException $e) {
            $this->sendJsonResponse([
                'status' => 'error',
                'message' => 'No se pudo eliminar la prioridad. Detalles: ' . $e->getMessage()
            ]);
        } catch (\Exception $e) {
            $this->sendJsonResponse([
                'status' => 'error',
                'message' => 'Ocurrió un error inesperado. Detalles: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Configurar las cabeceras para JSON.
     */
    private function sendJsonHeaders()
    {
        header('Content-Type: application/json');
    }

    /**
     * Obtener los datos JSON del cuerpo de la solicitud.
     *
     * @return array
     */
    private function getJsonInput(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }

    /**
     * Enviar una respuesta JSON al cliente.
     *
     * @param array $response
     */
    private function sendJsonResponse(array $response)
    {
        echo json_encode($response);
        exit;
    }
}
