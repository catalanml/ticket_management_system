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
        session_start();

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user'] = [
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'email' => $user['email']
            ];

            header("Location: /dashboard");
            exit;
        }

        $_SESSION['error'] = "Credenciales inválidas.";
        header("Location: /login");
        exit;
    }


    public function register()
    {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);


        if ($this->userModel->createUser(['firstname' => $firstname, 'lastname' => $lastname , 'email' => $email ,'password' => $password])) {
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
