<?php

namespace App\Services;

use App\Helpers\Helpers;

class BlogService
{
    protected $helpers;

    public function __construct()
    {
        $this->helpers = new Helpers();
    }

    public function handleImg(array $thumb) : array
    {
        $image = NULL;
        if ($_FILES['bg-image']) {
            $image = $_FILES['bg-image'];
        } else {
            $image = $_FILES['icon'];
        }
        $storageDir = "imgs/blog/"; // Set storage directory
        $file = $storageDir . basename($thumb["name"]); // Get file URI
        $extension = strtolower(pathinfo($file,PATHINFO_EXTENSION)); // Get file extension
        $size = $image['size']; // / Get file size
        $imageInfo = getimagesize($image['tmp_name']); // Get image info

        // Define allowed mime types and maximum file size
        $allowedMimes = ['jpeg', 'png', 'jfif', 'avif', 'webp', 'jpg', 'gif'];
        $maxFileSize = 40 * 1024 * 1024; // 40 MB in bytes

        // Generate secure name
        $newImgName = random_int(1000000000000000, 9999999999999999);

        // Validate
        $errors = [];
        if ($size > $maxFileSize) {
            $errors['thumb_error'] = 'La imagen pesa mas que 40MB';
            return [
                'new_thumb_name' => $newImgName,
                'errors' => $errors];
        } else if (!in_array($extension, $allowedMimes)) {
            $errors['thumb_error'] = 'Solo se permiten imagenes jpeg, png, jfif, avif, webp, gif y jpg';
            return [
                'new_thumb_name' => $newImgName,
                'errors' => $errors];
        } else if ($imageInfo === false) {
            $errors['thumb_error'] = 'La imagen no es valida';
            return [
                'new_thumb_name' => $newImgName,
                'errors' => $errors];
        }

        // Move the file to storage
        move_uploaded_file($image["tmp_name"], $storageDir . $newImgName . '.' . $extension);

        // Get the root directory of the project
        $rootDir = dirname(dirname(__DIR__));

        // Process image
        $sourcePath = $rootDir . '/public/imgs/blog/' . $newImgName . '.' . $extension;
        $destinationPath = $rootDir . '/public/imgs/blog/' . $newImgName . '2.webp';

        $imgError = $this->helpers->processImage($sourcePath, $destinationPath);

        if ($imgError) {
            return [
                'new_img_name' => $newImgName,
                'errors' => $errors,
                'img_error' => $imgError
            ];
        }

        return [
            'new_img_name' => $newImgName,
            'errors' => $errors
        ];
    }
}