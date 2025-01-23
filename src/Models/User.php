<?php


namespace App\Models;

use App\Core\Model;

class User extends Model
{
    public function findByEmail(string $username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $username]);
        return $stmt->fetch();
    }

    public function createUser(array $data): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (firstname, lastname, email , password) VALUES (:firstname, :lastname, :email, :password)");
        return $stmt->execute($data);
    }
}