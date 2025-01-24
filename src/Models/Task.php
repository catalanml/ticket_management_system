<?php

namespace App\Models;


use App\Core\Model;
use App\Models\User;

class Task extends Model
{
    public function getAllTasks(): array
    {
        $stmt = $this->pdo->query("
            SELECT *
            FROM tasks t
            WHERE t.deleted = 0
        ");
        return $stmt->fetchAll();
    }

    public function getLastInsertedId(): int
    {
        return (int)$this->pdo->lastInsertId();
    }

    public function getAssignedUsertoTask(int $taskId): ?array
    {

        $response = [];


        $stmt = $this->pdo->prepare("
                        SELECT user_id 
                        FROM user_task_assignments 
                        WHERE task_id = :task_id 
                        and deleted = 0");
        $stmt->execute(['task_id' => $taskId]);

        $userId = $stmt->fetch();

        if (!$userId) {
            return [];
        }

        $userModel = new User();
        $response['assigned_user'] = $userModel->getUserById($userId['user_id']);


        return $response;
    }
    
    public function getTaskById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("
            SELECT t.id, t.title, t.description, t.priority_id, t.category_id, t.user_id, t.deadline_date
            FROM tasks t
            WHERE t.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(); 
    }

    public function isTaskCompleted(): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT t.id 
            FROM tasks t 
            JOIN user_task_assignments uta ON t.id = uta.task_id and uta.completed = 1
           ");
        $stmt->execute();

        $record = $stmt->fetch();
        return $record ? true : false;
    }


    public function createTask(array $data): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO tasks (title, description, observation, priority_id, category_id, user_id, created_by, deadline_date) 
            VALUES (:title, :description, :observation, :priority_id, :category_id, :user_id, :created_by, :deadline_date)
        ");
        return $stmt->execute($data);
    }

    public function updateTask(array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE tasks
            SET title = :title, description = :description, observation = :observation,
                priority_id = :priority_id, category_id = :category_id, edited_by = :edited_by, deadline_date = :deadline_date          
            WHERE id = :id
        ");
        return $stmt->execute($data);
    }

    public function deleteTask(int $id): bool
    {
        $stmt = $this->pdo->prepare("UPDATE tasks SET deleted = 1 WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getAssignedTasks($userId): array
    {
        $query = $this->pdo->prepare("
            SELECT t.id, t.title, p.name AS priority
            FROM tasks t
            JOIN priorities p ON t.priority_id = p.id
            JOIN user_task_assignments ut ON ut.task_id = t.id
            WHERE ut.user_id = :user_id AND t.deleted = 0
        ");
        $query->execute(['user_id' => $userId]);
        return $query->fetchAll();
    }
}
