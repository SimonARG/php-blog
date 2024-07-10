<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\Helpers;

class AuthController
{
    protected $user;
    protected $helpers;

    public function __construct()
    {
        $this->user = new User();
        $this->helpers = new Helpers();
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