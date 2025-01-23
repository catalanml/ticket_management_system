<?php

namespace App\Controllers;

use App\Models\User;

class AuthController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function loginForm()
    {
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function registerForm()
    {
        require __DIR__ . '/../Views/auth/register.php';
    }

    public function login()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = $this->userModel->findByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            header("Location: /tasks");
            exit;
        }

        $_SESSION['error'] = "Invalid credentials!";
        header("Location: /login");
    }

    public function register()
    {

        file_put_contents('/tmp/debug.log', json_encode($_POST) . PHP_EOL, FILE_APPEND);

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);


        if ($this->userModel->createUser(['username' => $username, 'email' => $email ,'password' => $password])) {
            header("Location: /login");
            exit;
        }

        $_SESSION['error'] = "Registration failed!";
        header("Location: /register");
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: /login");
        exit;
    }
}
