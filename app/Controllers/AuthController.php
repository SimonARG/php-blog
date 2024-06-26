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
    
        $result = $this->user->getUserByEmailWithRole($email);
    
        if ($result) {
            if (password_verify($password, $result['password'])) {
    
                $savedPostsArr = $this->user->getSavedPostsIds($result['id']);
    
                $savedPosts = [];
    
                if(!$savedPostsArr) {
                    $_SESSION['saved_posts'] = [];
                } else {
                    foreach ($savedPostsArr as $key => $post) {
                        array_push($savedPosts, $savedPostsArr[$key]['post']);
                    };
    
                    $_SESSION['saved_posts'] = $savedPosts;
                }
    
                $_SESSION['user_id'] = $result['id'];
                $_SESSION['username'] = $result['name'];
                $_SESSION['role'] = $result['role'];
    
                return route('/', ['popup_content' => 'Sesion iniciada']);
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
        session_destroy();
        $_SESSION = [];

        return route('/', ['popup_content' => 'Sesion cerrada']);
    }
}