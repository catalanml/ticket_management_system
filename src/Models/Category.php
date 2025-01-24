<?php

namespace App\Models;

use App\Core\Model;
use App\Models\Task;

class Category extends Model
{
    public function getAllCategories(): array
    {
        $query = $this->pdo->query("SELECT * FROM categories WHERE deleted = 0");
        return $query->fetchAll();
    }

    public function getCategoryById(int $id): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getLastInsertedId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }

    public function createCategory(string $name, int $createdBy): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO categories (name, created_by) VALUES (:name, :created_by)");
        return $stmt->execute(['name' => $name, 'created_by' => $createdBy]);
    }

    public function updateCategory(int $id, string $name, int $editedBy): bool
    {
        $stmt = $this->pdo->prepare("UPDATE categories SET name = :name, edited_by = :edited_by WHERE id = :id");
        return $stmt->execute(['name' => $name, 'edited_by' => $editedBy, 'id' => $id]);
    }

    public function deleteCategory(int $id): bool
    {
        $stmt = $this->pdo->prepare("UPDATE categories SET deleted = 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);

        //get all tasks associated with the category
        $taskModel = new Task();
        $tasks = $taskModel->getTaskByCategoryId($id);

        //delete all tasks associated with the category
        foreach ($tasks as $task) {
            $taskModel->deleteTask($task['id']);
        }

        return true;
    }
}
