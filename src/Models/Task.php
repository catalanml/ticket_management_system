<?php

namespace App\Models;


use App\Core\Model;
use App\Models\User;
use App\Models\Priority;
use App\Models\Category;

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

    public function addTaskDetails($tasks)
    {

        $priorityModel = new Priority();
        $categoryModel = new Category();
        $userModel = new User();


        foreach ($tasks as $key => $task) {

            $tasks[$key]['status'] = $this->isTaskCompleted($task['id']) ? 'completed' : 'pending';

            $priority = $priorityModel->getPriorityById($task['priority_id']);
            $tasks[$key]['priority_name'] = $priority['name'];
            $tasks[$key]['priority_type'] = $priority['type'];

            $category = $categoryModel->getCategoryById($task['category_id']);
            $tasks[$key]['category_name'] = $category['name'];

            $assignedUserId = $this->getAssignedUsertoTask($task['id']);
            $tasks[$key]['assigned_user_id'] =  $assignedUserId;
            $tasks[$key]['assigned_user_name'] = '';

            if ($assignedUserId) {
                $assignedUser = $userModel->getUserById($assignedUserId);
                $tasks[$key]['assigned_user_name'] = ($assignedUser['firstname'] . ' ' . $assignedUser['lastname']);
            }
        }

        return $tasks;
    }

    public function getLastInsertedId(): int
    {
        return (int)$this->pdo->lastInsertId();
    }

    public function getAssignedUsertoTask(int $taskId): ?int
    {
        $stmt = $this->pdo->prepare("
            SELECT user_id 
            FROM user_task_assignments 
            WHERE task_id = :task_id 
            AND deleted = 0
            LIMIT 1
        ");

        $stmt->execute(['task_id' => $taskId]);

        $userId = $stmt->fetchColumn();

        return $userId ?: null;
    }

    public function updateTaskStatus(int $taskId,  int $userId, string $status, ?string $observation = null): void
    {

        $stmt = $this->pdo->prepare("
            UPDATE tasks
                SET observation = :observation, 
                edited_time = NOW()
            WHERE id = :id
        ");

        $stmt->execute([
            'observation' => $observation,
            'id' => $taskId,
        ]);

        $stmt = $this->pdo->prepare("
            UPDATE user_task_assignments
            SET completed = 1
            WHERE task_id = :task_id AND user_id = :user_id and deleted = 0");

        $stmt->execute([
            'task_id' => $taskId,
            'user_id' => $userId,
        ]);
    }



    public function getTaskById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("
            SELECT t.*
            FROM tasks t
            WHERE t.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function isTaskCompleted($taskId): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT t.id 
            FROM tasks t 
            JOIN user_task_assignments ut ON t.id = ut.task_id AND ut.completed = 1 
            WHERE t.id = :task_id
        ");

        $stmt->execute(['task_id' => $taskId]);

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

    public function updateTask(array $taskData): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE tasks
            SET 
                title = :title,
                description = :description,
                priority_id = :priority_id,
                category_id = :category_id,
                deadline_date = :deadline_date,
                edited_by = :edited_by,
                edited_time = NOW()
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $taskData['id'],
            'title' => $taskData['title'],
            'description' => $taskData['description'],
            'priority_id' => $taskData['priority_id'],
            'category_id' => $taskData['category_id'],
            'deadline_date' => $taskData['deadline_date'],
            'edited_by' => $taskData['edited_by']
        ]);
    }


    public function deleteTask(int $id): bool
    {
        $stmt = $this->pdo->prepare("UPDATE tasks SET deleted = 1 WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getAssignedTasks($userId): array
    {

        $query = $this->pdo->prepare("
            SELECT t.*
            FROM tasks t
            INNER JOIN user_task_assignments ut ON 
                        ut.task_id = t.id 
                        AND ut.user_id = :user_id
                        AND ut.deleted = 0
    
        ");

        $query->execute(['user_id' => $userId]);

        return $query->fetchAll();
    }

    public function assignUserToTask(int $taskId, int $userId, int $createdBy)
    {
        $this->pdo->beginTransaction();

        try {

            $stmt = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM user_task_assignments
            WHERE task_id = :task_id AND deleted = 0
        ");
            $stmt->execute(['task_id' => $taskId]);
            $existingAssignmentCount = $stmt->fetchColumn();


            if ($existingAssignmentCount > 0) {
                $stmt = $this->pdo->prepare("
                UPDATE user_task_assignments
                SET deleted = 1, edited_time = NOW()
                WHERE task_id = :task_id AND deleted = 0
            ");
                $stmt->execute(['task_id' => $taskId]);
            }

            $stmt = $this->pdo->prepare("
            INSERT INTO user_task_assignments (user_id, task_id, created_by)
            VALUES (:user_id, :task_id, :created_by)
        ");
            $stmt->execute([
                'user_id' => $userId,
                'task_id' => $taskId,
                'created_by' => $createdBy,
            ]);

            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
