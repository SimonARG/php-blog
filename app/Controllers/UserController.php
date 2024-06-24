<?php

namespace App\Controllers;

use DateTime;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;

class UserController
{
    protected $userModel;
    protected $postModel;
    protected $commentModel;
    protected $baseUrl;

    public function __construct()
    {
        $this->userModel = new User();
        $this->postModel = new Post();
        $this->commentModel = new Comment();
        $this->baseUrl = $GLOBALS['config']['base_url'];
    }

    public function create()
    {
        return view('users.create');
    }

    public function store($request)
    {
        $errors = [];

        // Sanitize
        $name = htmlspecialchars($request['name']);
        $email = filter_var($request['email'], FILTER_SANITIZE_EMAIL);
        $password = htmlspecialchars($request['password']);
        $password_repeat = htmlspecialchars($request['password-r']);
    
        // Validate name
        if (strlen($name) < 5) {
            $errors['name_error'] = 'El nombre es demasiado corto';
        } elseif (strlen($name) > 18) {
            $errors['name_error'] = 'El nombre es demasiado largo';
        } elseif (!preg_match('/^[A-Za-z0-9_.-]+$/', $name)) {
            $errors['name_error'] = 'El nombre contiene caracteres prohibidos';
        }
    
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email_error'] = 'El e-mail no es valido';
        }
    
        // Validate password
        if (strlen($password) < 8) {
            $errors['password_error'] = 'La contraseña es demasiado corta';
        } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).+$/', $password)) {
            $errors['password_error'] = 'La contraseña no cumple los requisitos';
        }
    
        // Validate password confirmation
        if ($password != $password_repeat) {
            $errors['password_r_error'] = 'Las contraseñas no coinciden';
        }
    
        // Return errors if any
        if (!empty($errors)) {
            return view('users.create', ['request' => $request, 'errors' => $errors]);
        }

        $password = password_hash($password, PASSWORD_DEFAULT);

        $data['name'] = $name;
        $data['email'] = $email; 
        $data['password'] = $password; 
    
        $this->userModel->create($data);

        $result = $this->userModel->getUserByEmail($email);

        $this->userModel->setRole($result['id'], 'user');

        $user = $this->userModel->getUserById($result['id']);

        session_start();

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        $message = 'Cuenta ' . $user['name'] . ' creada';

        return route('/', ['popup_content' => $message]);
    }

    public function show($id)
    {
        $user = $this->userModel->getUserById($id);

        $lastPostId = $this->userModel->getLatestUserPostId($id);

        $lastCommentId = $this->userModel->getLatestUserCommentId($id);
        
        $lastCommentPostId = $this->commentModel->getPostIdForComment($lastCommentId['id']);

        $savedPosts = $this->userModel->getSavedPostsCount($id)['posts'];

        $userDate = new DateTime($user['created_at']);
        $userStrdate = $userDate->format('Y/m/d H:i');
        $user['created_at'] = $userStrdate;

        if (isset($user['updated_at'])) {
            $userUpDate = new DateTime($user['updated_at']);
            $userUpStrdate = $userUpDate->format('Y/m/d H:i');
            $user['updated_at'] = $userUpStrdate;
        }

        return view('users.single', [
            'user' => $user,
            'lastPostId' => $lastPostId,
            'lastCommentPostId' => $lastCommentPostId,
            'savedPosts' => $savedPosts
        ]);
    }

    public function update($id, $request)
    {
        $user = $this->userModel->getUserById($id);

        $errors = [];

        // Sanitize
        $name = htmlspecialchars($request['name']);
        $email = filter_var($request['email'], FILTER_SANITIZE_EMAIL);
        $password = htmlspecialchars($request['password']);
        $newPassword = htmlspecialchars($request['new-password']);
        $newPasswordRepeat = htmlspecialchars($request['new-password-r']);
        $avatar = $_FILES['avatar']['name'] ? $_FILES['avatar'] : NULL;

        // Get values for view
        $lastPostId = $this->userModel->getLatestUserPostId($id);

        $lastCommentId = $this->userModel->getLatestUserCommentId($id);
        
        $lastCommentPostId = $this->commentModel->getPostIdForComment($lastCommentId['id']);

        $userDate = new DateTime($user['created_at']);
        $userStrdate = $userDate->format('Y/m/d H:i');
        $user['created_at'] = $userStrdate;

        if (isset($user['updated_at'])) {
            $userUpDate = new DateTime($user['updated_at']);
            $userUpStrdate = $userUpDate->format('Y/m/d H:i');
            $user['updated_at'] = $userUpStrdate;
        }

        // Perform current password validation
        if (!password_verify($password, $user['password'])) {
            $errors['password_error'] = 'Contraseña incorrecta';

            return view('users.single', [
                'user' => $user,
                'lastPostId' => $lastPostId,
                'lastCommentPostId' => $lastCommentPostId,
                'errors' => $errors,
                'old' => $request
            ]);
        }
    
        // Validate name
        if (strlen($name) < 5) {
            $errors['name_error'] = 'El nombre es demasiado corto';
        } elseif (strlen($name) > 18) {
            $errors['name_error'] = 'El nombre es demasiado largo';
        } elseif (!preg_match('/^[A-Za-z0-9_.-]+$/', $name)) {
            $errors['name_error'] = 'El nombre contiene caracteres prohibidos';
        }
    
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email_error'] = 'El e-mail no es valido';
        }
    
        // Validate new password
        if ($newPassword) {
            if (strlen($newPassword) < 8) {
                $errors['new_password_error'] = 'La contraseña es demasiado corta';
            } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).+$/', $newPassword)) {
                $errors['new_password_error'] = 'La contraseña no cumple los requisitos';
            }

            $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        }
    
        // Validate new password confirmation
        if ($newPassword != $newPasswordRepeat) {
            $errors['new_password_r_error'] = 'Las contraseñas no coinciden';
        }

        // Validate avatar
        if (!$avatar) {
            $dbEntry['avatar'] = $user['avatar'];
        } else {
            $storage_dir = "imgs/avatars/";
            $file = $storage_dir . basename($avatar["name"]);
            $extension = strtolower(pathinfo($file,PATHINFO_EXTENSION));
            $size = $_FILES['avatar']['size'];
            $imageInfo = getimagesize($_FILES['avatar']['tmp_name']);

            // Define allowed mime types and maximum file size
            $allowedMimeTypes = ['jpeg', 'png', 'jfif', 'avif', 'webp', 'jpg'];
            $maxFileSize = 40 * 1024 * 1024; // 40 MB in bytes

            if ($size > $maxFileSize) {
                $errors['avatar_error'] = 'La imagen pesa mas que 40MB';
            } else if (!in_array($extension, $allowedMimeTypes)) {
                $errors['avatar_error'] = 'Solo se permiten imagenes jpeg, png, jfif, avif, webp y jpg';
            } else if ($imageInfo === false) {
                $errors['avatar_error'] = 'La imagen no es valida';
            }

            $new_avatar_name = random_int(1000000000000000, 9999999999999999);
            $dbEntry['avatar'] = $new_avatar_name . '2.webp';

            move_uploaded_file($_FILES["avatar"]["tmp_name"], $storage_dir . $new_avatar_name . '.' . $extension);

            $sourcePath = 'D:/Programs/Apache/Apache24/htdocs/blog/public/imgs/avatars/' . $new_avatar_name . '.' . $extension;
            $destinationPath = 'D:/Programs/Apache/Apache24/htdocs/blog/public/imgs/avatars/' . $new_avatar_name . '2.webp';
    
            $imgError = processImage($sourcePath, $destinationPath);
        }
    
        // Return errors if any
        if (!empty($errors)) {
            return view('users.single', [
                'user' => $user,
                'lastPostId' => $lastPostId,
                'lastCommentPostId' => $lastCommentPostId,
                'errors' => $errors,
                'old' => $request
            ]);
        }

        $dbEntry['name'] = $name;
        $dbEntry['email'] = $email;
        $newPassword ?? $dbEntry['password'] = $newPassword;

        $this->userModel->update($dbEntry, $id);
        $user = $this->userModel->getUserById($id);

        $message = 'Perfil editado';

        return view('users.single', [
            'user' => $user,
            'lastPostId' => $lastPostId,
            'lastCommentPostId' => $lastCommentPostId,
            'popupContent' => $message
        ]);
    }

    public function save($request)
    {        

        $postId = $request['post_id'];
        $userId = $request['user_id'];
        $currPage = $request['curr_page'];

        $result = $this->postModel->save($postId, $userId);

        if(!$result) {
            $message = 'Error al guardar post';
        } else {
            $_SESSION['saved_posts'][] = $postId;

            $message = 'Post guardado';
        }

        return header('Location: ' . $this->baseUrl . '?page=' . $currPage . "&popup_content=" . $message);
    }

    public function deleteSaved($request)
    {
        $postId = $request['post_id'];
        $userId = $request['user_id'];
        $currPage = $request['curr_page'];
        $totalPages = $request['total_pages'];

        $result = $this->postModel->deleteSaved($postId, $userId);

        if(!$result) {
            $message = 'Error';
        } else {
            if (($key = array_search($postId, $_SESSION['saved_posts'])) !== false) {
                unset($_SESSION['saved_posts'][$key]);
            }
            $message = 'Post removido de guardados';
        }

        if ($totalPages > 1) {
            return header('Location: ' . $this->baseUrl . 'search/user/saved/' . $userId . '/?page=' . $currPage . "&popup_content=" . $message);
        } else {
            return header('Location: ' . $this->baseUrl . 'search/user/saved/' . $userId . "?popup_content=" . $message);
        }
    }
} 