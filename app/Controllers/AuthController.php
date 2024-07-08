<?php

namespace App\Controllers;

use App\Models\User;

class AuthController
{
    protected $user;
    protected $baseUrl;

    public function __construct()
    {
        $this->user = new User();
        $this->baseUrl = $GLOBALS['config']['base_url'];
    }

    public function login()
    {
        return view('users.login');
    }

    public function authenticate($request)
    {
        $errors = [];
    
        $email = $request['email'];
        $password = $request['password'];
    
        $user = $this->user->getUserByEmailWithRole($email);
    
        if ($user) {
            if ($user['role'] == 'banned') {
                $_SESSION['popup_content'] = 'Usuario banneado';
    
                return  header('Location: ' . $this->baseUrl . 'login');
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

                $_SESSION['popup_content'] = 'Sesion iniciada';
    
                return header('Location: /');
            } else {
                $errors['error'] = 'Credenciales invalidas';
            }
        } else {
            $errors['error'] = 'Credenciales invalidas';
        }
    
        // Return errors if any
        if (!empty($errors)) {
            return view('users.login', ['request' => $request, 'errors' => $errors]);
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