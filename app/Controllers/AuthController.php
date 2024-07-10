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

    public function login()
    {
        return $this->helpers->view('users.login');
    }

    public function authenticate($request)
    {
        $errors = [];
    
        $email = $request['email'];
        $password = $request['password'];
    
        $user = $this->user->getUserByEmailWithRole($email);
    
        if ($user) {
            if ($user['role'] == 'banned') {
                $this->helpers->setPopup('Cuenta banneada');
    
                return  header('Location: /login');
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

                $this->security->generateCsrf();

                $this->helpers->setPopup('Sesion iniciada');
    
                return header('Location: /');
            } else {
                $errors['error'] = 'Credenciales invalidas';
            }
        } else {
            $errors['error'] = 'Credenciales invalidas';
        }
    
        // Return errors if any
        if (!empty($errors)) {
            return $this->helpers->view('users.login', ['request' => $request, 'errors' => $errors]);
        }
    }
    

    public function logout()
    {
        session_start();
        $_SESSION = [];
        session_destroy();

        return header('Location: /');
    }
}