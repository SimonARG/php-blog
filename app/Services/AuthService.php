<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\Helpers;

class AuthService
{
    protected $helpers;
    protected $user;

    public function __construct(Helpers $helpers, User $user)
    {
        $this->helpers = $helpers;
        $this->user = $user;
    }

    public function sanitize(array $request): array
    {
        $email = filter_var($request['email'], FILTER_SANITIZE_EMAIL);
        $password = htmlspecialchars($request['password']);

        $sanitizedRequest = [
            'email' => $email,
            'password' => $password,
        ];

        return $sanitizedRequest;
    }

    public function sanitizeAndValidate(array $request): array
    {
        $errors = [];
        
        if (empty($request['password'])) {
            $errors['password'] = 'La contraseña es necesaria';
        }

        if (empty($request['email'])) {
            $errors['email'] = 'El E-Mail es necesario';
        }

        $sanitizedRequest = $this->sanitize($request);

        if (!filter_var($sanitizedRequest['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'E-Mail inválido';
        }

        return [
            'sanitized_request' => $sanitizedRequest,
            'errors' => $errors,
        ];
    }

    public function getSavedPosts(array $user)
    {
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
    }
}
