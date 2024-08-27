<?php

namespace App\Services;

class CommentService
{
    public function __construct()
    {
    }

    public function sanitize(array $data): array
    {
        foreach ($data as $key => $value) {
            // Remove HTML tags
            $value = strip_tags($value);

            // Convert special characters to HTML entities
            $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            $data[$key] = $value;
        }

        return $data;
    }

    public function sanitizeAndValidate(array $data): array
    {
        $sanitizedData = $this->sanitize($data);

        $errors = [];

        if (!is_string($data['body'])) {
            $errors['body_error'] = 'Error de seguridad';
        } elseif (strlen($data['body']) < 1) {
            $errors['body_error'] = 'El comentario es demasiado corto';
        } elseif (strlen($data['body']) > 1600) {
            $errors['body_error'] = 'El comentario es demasiado largo';
        }

        return [
            'sanitized_request' => $sanitizedData,
            'errors' => $errors,
        ];
    }
}
