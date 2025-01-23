<?php

namespace App\Models;namespace App\Models;

use App\Core\Model;

class Task extends Model
{
    public function getAllTasks(): array
    {
        $query = $this->pdo->query("SELECT * FROM tasks WHERE deleted = 0");
        return $query->fetchAll();
    }

    public function createTask(array $data): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO tasks (title, description, user_id, category_id, priority_id, created_by) 
                                    VALUES (:title, :description, :user_id, :category_id, :priority_id, :created_by)");
        return $stmt->execute($data);
    }

    public function getAssignedTasks($userId)
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
