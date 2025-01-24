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

    public function getPriorityById(int $id): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM priorities WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function createPriority(string $name, string $type, int $createdBy): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO priorities (name, type, created_by) VALUES (:name, :type, :created_by)");
        return $stmt->execute(['name' => $name, 'type' => $type, 'created_by' => $createdBy]);
    }

    public function updatePriority(int $id, string $name, string $type, int $editedBy): bool
    {
        $stmt = $this->pdo->prepare("UPDATE priorities SET name = :name, type = :type, edited_by = :edited_by WHERE id = :id");
        return $stmt->execute(['name' => $name,  'type' => $type,  'edited_by' => $editedBy, 'id' => $id]);
    }

    public function deletePriority(int $id): bool
    {
        $stmt = $this->pdo->prepare("UPDATE priorities SET deleted = 1 WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
