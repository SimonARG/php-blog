<?php

namespace App\Services;

use App\Helpers\Helpers;

class PostService
{
    protected $helpers;

    public function __construct()
    {
        $this->helpers = new Helpers();
    }

    public function sanitize(array $request) : array
    {
        $title = htmlspecialchars($request['title']);
        $subtitle = htmlspecialchars($request['subtitle']);
        $body = $request['body'];
        $userId = htmlspecialchars($request['user_id']);

        $sanitizedRequest = [
            'title' => $title,
            'subtitle' => $subtitle,
            'body' => $body,
            'user_id' => $userId];

        return $sanitizedRequest;
    }

    public function sanitizeAndValidate(array $request) : array
    {
        $sanitizedRequest = $this->sanitize($request);

        // Validate title
        $errors = [];
        
        if (strlen($sanitizedRequest['title']) < 4) {
            $errors['title_error'] = 'El titulo es demasiado corto';
        } elseif (strlen($sanitizedRequest['title']) > 40) {
            $errors['title_error'] = 'El titulo es demasiado largo';
        }

        // Validate subtitle
        if (strlen($sanitizedRequest['subtitle']) < 4) {
            $errors['subtitle_error'] = 'El subtitulo es demasiado corto';
        } elseif (strlen($sanitizedRequest['subtitle']) > 50) {
            $errors['subtitle_error'] = 'El subtitulo es demasiado largo';
        }

        // Validate body
        if (strlen($sanitizedRequest['body']) < 10) {
            $errors['body_error'] = 'El cuerpo es demasiado corto';
        } elseif (strlen($sanitizedRequest['body']) > 40000) {
            $errors['body_error'] = 'El cuerpo es demasiado largo';
        }

        // Validate user ID
        if (!is_numeric($sanitizedRequest['user_id'])) {
            return 0;
        }

        return [
            'sanitized_request' => $sanitizedRequest,
            'errors' => $errors
        ];
    }

    public function handleThumb(array $thumb) : array
    {
        $storageDir = "imgs/thumbs/"; // Set storage directory
        $file = $storageDir . basename($thumb["name"]); // Get file URI
        $extension = strtolower(pathinfo($file,PATHINFO_EXTENSION)); // Get file extension
        $size = $_FILES['thumb']['size']; // / Get file size
        $imageInfo = getimagesize($_FILES['thumb']['tmp_name']); // Get image info

        // Define allowed mime types and maximum file size
        $allowedMimes = ['jpeg', 'png', 'jfif', 'avif', 'webp', 'jpg'];
        $maxFileSize = 40 * 1024 * 1024; // 40 MB in bytes

        // Generate secure name
        $newThumbName = random_int(1000000000000000, 9999999999999999);

        // Validate
        $errors = [];
        if ($size > $maxFileSize) {
            $errors['thumb_error'] = 'La imagen pesa mas que 40MB';
            return [
                'new_thumb_name' => $newThumbName,
                'errors' => $errors];
        } else if (!in_array($extension, $allowedMimes)) {
            $errors['thumb_error'] = 'Solo se permiten imagenes jpeg, png, jfif, avif, webp y jpg';
            return [
                'new_thumb_name' => $newThumbName,
                'errors' => $errors];
        } else if ($imageInfo === false) {
            $errors['thumb_error'] = 'La imagen no es valida';
            return [
                'new_thumb_name' => $newThumbName,
                'errors' => $errors];
        }

        // Move the file to storage
        move_uploaded_file($_FILES["thumb"]["tmp_name"], $storageDir . $newThumbName . '.' . $extension);

        // Get the root directory of the project
        $rootDir = dirname(dirname(__DIR__));

        // Process image
        $sourcePath = $rootDir . '/public/imgs/thumbs/' . $newThumbName . '.' . $extension;
        $destinationPath = $rootDir . '/public/imgs/thumbs/' . $newThumbName . '2.webp';

        $imgError = $this->helpers->processImage($sourcePath, $destinationPath);

        return [
            'new_thumb_name' => $newThumbName,
            'errors' => $errors
        ];
    }
}