<?php

namespace App\Controllers;

use App\Models\Blog;
use App\Models\User;
use App\Helpers\Helpers;
use App\Helpers\Security;
use App\Services\AuthService;
use App\Controllers\Controller;

class AuthController extends Controller
{
    protected $user;
    protected $service;

    public function __construct(Security $security, Helpers $helpers, Blog $blog, User $user, AuthService $authService)
    {
        parent::__construct($security, $helpers, $blog);

        $this->user = $user;
        $this->service = $authService;
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

        // Sanitize & validate
        $result = $this->service->sanitizeAndValidate($request);

        $cleanRequest = $result['sanitized_request'];
        $errors = $result['errors'];

        $password = $cleanRequest['password'];
        $email = $cleanRequest['email'];

        if (!empty($errors)) {
            $this->helpers->view('users.login', [
                'request' => $request,
                'errors' => $errors
            ]);

            return;
        }

        $user = $this->user->getUserByEmailWithRole($email);

        // If user is not found, return with errors
        if (!$user) {
            $errors['login'] = 'Credenciales invalidas';

            $this->helpers->view('users.login', [
                'request' => $request,
                'errors' => $errors
            ]);

            return;
        }

        // If password doesn't match / else
        if (!password_verify($password, $user['password'])) {
            $errors['login'] = 'Credenciales invalidas';
        } else {
            // If account is soft-deleted
            if (!empty($user['deleted_at'])) {
                $this->helpers->setPopup('La cuenta ' . $user['name'] . ' ha sido eliminada. Para restaurarla, contacte a un administrador.');

                header('Location: /login');

                return;
            }

            // If user is banned
            if ($user['role'] == 'banned') {
                $this->helpers->setPopup('La cuenta ' . $user['name'] . ' se encuentra banneada');

                header('Location: /login');

                return;
            }

            $this->service->getSavedPosts($user);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            $this->security->regenerateCsrf();

            $this->helpers->setPopup('Sesion iniciada');

            header('Location: /');

            return;
        }

        // Return errors if any
        if (!empty($errors)) {
            $this->helpers->view('users.login', [
                'request' => $request,
                'errors' => $errors
            ]);

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

        // Regenerate session CSRF token for guest
        $this->security->regenerateCsrf();

        $this->helpers->setPopup('Sesión cerrada');

        // Regenerate session ID for security
        session_regenerate_id(true);

        header('Location: /');

        return;
    }
}
