<?php


namespace App\Models;

use App\Core\Model;

class User extends Model
{
    public function findByUsername(string $username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE name = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function createUser(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO users (name, email , password) VALUES (:username, :email, :password)");
        return $stmt->execute($data);
    }
}