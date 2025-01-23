<?php

namespace App\Models;

use App\Core\Model;

class Priority extends Model
{
    public function getAllpriorities(): array
    {
        $query = $this->pdo->query("SELECT * FROM priorities WHERE deleted = 0");
        return $query->fetchAll();
    }

    public function getLastInsertedId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }

    public function createPriority(string $name, int $createdBy): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO priorities (name, created_by) VALUES (:name, :created_by)");
        return $stmt->execute(['name' => $name, 'created_by' => $createdBy]);
    }

    public function updatePriority(int $id, string $name, int $editedBy): bool
    {
        $stmt = $this->pdo->prepare("UPDATE priorities SET name = :name, edited_by = :edited_by WHERE id = :id");
        return $stmt->execute(['name' => $name, 'edited_by' => $editedBy, 'id' => $id]);
    }

    public function deletePriority(int $id): bool
    {
        $stmt = $this->pdo->prepare("UPDATE priorities SET deleted = 1 WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
