<?php

namespace App\Models;

use App\Core\Model;

class Task extends Model
{
    public function getAllTasks(): array
    {
        $query = $this->db->query("SELECT * FROM tasks WHERE deleted = 0");
        return $query->fetchAll();
    }

    public function createTask(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO tasks (title, description, user_id, category_id, priority_id, created_by) 
                                    VALUES (:title, :description, :user_id, :category_id, :priority_id, :created_by)");
        return $stmt->execute($data);
    }
}
