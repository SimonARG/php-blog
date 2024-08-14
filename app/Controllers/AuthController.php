<?php

namespace App\Controllers;

use App\Models\User;
use App\Controllers\Controller;

class AuthController extends Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
    }

    public function login(): void
    {
        $this->helpers->view('users.login');

        return;
    }

    public function authenticate(array $request): void
    {
        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            $this->helpers->view('users.login');

            return;
        }

        $errors = [];

        $email = $request['email'];
        $password = $request['password'];

        $user = $this->user->getUserByEmailWithRole($email);

        if ($user) {
            if ($user['role'] == 'banned') {
                $this->helpers->setPopup('Cuenta banneada');

                header('Location: /login');

                return;
            }

            if (password_verify($password, $user['password'])) {

                $savedPostsArr = $this->user->getSavedPostsIds($user['id']);

                $savedPosts = [];

                if(!$savedPostsArr) {
                    $_SESSION['saved_posts'] = [];
                } else {
                    foreach ($savedPostsArr as $key => $post) {
                        array_push($savedPosts, $savedPostsArr[$key]['post']);
                    };

                    $_SESSION['saved_posts'] = $savedPosts;
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['name'];
                $_SESSION['role'] = $user['role'];

                $this->security->regenerateCsrf();

                $this->helpers->setPopup('Sesion iniciada');

                header('Location: /');

                return;
            } else {
                $errors['error'] = 'Credenciales invalidas';
            }
        } else {
            $errors['error'] = 'Credenciales invalidas';
        }

        // Return errors if any
        if (!empty($errors)) {
            $this->helpers->view('users.login', ['request' => $request, 'errors' => $errors]);

            return;
        }
    }


    public function logout(): void
    {
        // Empty session array
        $_SESSION = [];

        // Unset the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Formally destroy the session
        session_destroy();

        // Restart session for guest functionalities
        session_start();

        $this->helpers->setPopup('Sesión cerrada');

        // Regenerate session ID for security
        session_regenerate_id(true);

        header('Location: /');

        return;
    }
}
