<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;

class UserController extends Controller
{
    protected $user;
    protected $post;
    protected $comment;

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
        $this->post = new Post();
        $this->comment = new Comment();
    }

    public function create() : void
    {
        $this->helpers->view('users.create');
    }

    public function store(array $request) : void
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
            $this->helpers->view('users.create', ['request' => $request, 'errors' => $errors]);
        }

        $password = password_hash($password, PASSWORD_DEFAULT);

        $data['name'] = $name;
        $data['email'] = $email; 
        $data['password'] = $password; 
    
        $this->user->create($data);

        $user = $this->user->getUserByEmail($email);

        $users = $this->user->getUserCount();

        if ($users > 0) {
            $this->user->setRole($user['id'], 'user');
        } else {
            $this->user->setRole($user['id'], 'admin');
        }

        $user = $this->user->getUserById($user['id']);

        $_SESSION['saved_posts'] = [];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        $this->security->generateCsrf();

        $this->helpers->setPopup('Cuenta ' . $user['name'] . ' creada y sesión iniciada');

        header('Location: /' . 'user/' . ($user['id']));
    }

    public function show(int $id) : void
    {
        $user = $this->user->getUserById($id);

        $lastPostId = $this->user->getLatestUserPostId($id);

        $lastCommentId = $this->user->getLatestUserCommentId($id);
        
        $lastCommentPostId = $this->comment->getPostIdForComment($lastCommentId['id']);

        $savedPosts = $this->user->getSavedPostsCount($id)['posts'];

        $user = $this->helpers->formatDates($user);

        $this->helpers->view('users.single', [
            'user' => $user,
            'lastPostId' => $lastPostId,
            'lastCommentPostId' => $lastCommentPostId,
            'savedPosts' => $savedPosts
        ]);
    }

    public function update(int $id, array $request) : void
    {
        $this->security->verifyCsrf($request['csrf'] ?? '');

        if(!$this->security->verifyIdentity($id)) {
            $this->helpers->setPopup('Solo puedes editar tu propio perfil');

            header('Location: /user/' . $_SESSION['user_id']);
        }

        $user = $this->user->getUserById($id);

        $errors = [];

        // Sanitize
        $name = htmlspecialchars($request['name']);
        $email = filter_var($request['email'], FILTER_SANITIZE_EMAIL);
        $password = htmlspecialchars($request['password']);
        $newPassword = htmlspecialchars($request['new-password']);
        $newPasswordRepeat = htmlspecialchars($request['new-password-r']);
        $avatar = $_FILES['avatar']['name'] ? $_FILES['avatar'] : NULL;

        // Get values for view
        $lastPostId = $this->user->getLatestUserPostId($id);

        $lastCommentId = $this->user->getLatestUserCommentId($id);
        
        $lastCommentPostId = $this->comment->getPostIdForComment($lastCommentId['id']);

        $savedPosts = $this->user->getSavedPostsCount($id)['posts'];

        $user = $this->helpers->formatDates($user);

        // Perform current password validation
        if (!$this->security->isElevatedUser()) {
            if (!password_verify($password, $user['password'])) {
                $errors['password_error'] = 'Contraseña incorrecta';

                $this->helpers->view('users.single', [
                    'user' => $user,
                    'lastPostId' => $lastPostId,
                    'lastCommentPostId' => $lastCommentPostId,
                    'errors' => $errors,
                    'old' => $request,
                    'savedPosts' => $savedPosts
                ]);

                return;
            }
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
    
            $imgError = $this->helpers->processImage($sourcePath, $destinationPath);
        }
    
        // Return errors if any
        if (!empty($errors)) {
            $this->helpers->view('users.single', [
                'user' => $user,
                'lastPostId' => $lastPostId,
                'lastCommentPostId' => $lastCommentPostId,
                'errors' => $errors,
                'old' => $request,
                'savedPosts' => $savedPosts
            ]);

            return;
        }

        $dbEntry['name'] = $name;
        $dbEntry['email'] = $email;
        $newPassword ?? $dbEntry['password'] = $newPassword;

        $this->user->update($dbEntry, $id);
        $user = $this->user->getUserById($id);

        $this->helpers->setPopup('Perfil editado');

        $this->helpers->view('users.single', [
            'user' => $user,
            'lastPostId' => $lastPostId,
            'lastCommentPostId' => $lastCommentPostId,
            'savedPosts' => $savedPosts
        ]);
    }

    public function save(array $request) : void
    {
        $this->security->verifyCsrf($request['csrf'] ?? '');

        $postId = $request['post_id'];
        $userId = $request['user_id'];

        // Check if post is already saved and perform action
        if (in_array($postId, $_SESSION['saved_posts'])) {
            $this->helpers->setPopup('El post ya esta guardado');
        } else {
            $this->post->save($postId, $userId);

            $_SESSION['saved_posts'][] = $postId;

            $this->helpers->setPopup('Post guardado');
        }

        // Return to index or single with message
        if (isset($request['curr_page'])) {
            $currPage = $request['curr_page'];

            header('Location: /?page=' . $currPage);
        } else {
            header('Location: /post/' . $postId);
        }
    }

    public function deleteSaved(array $request) : void
    {
        $this->security->verifyCsrf($request['csrf'] ?? '');

        $postId = $request['post_id'];
        $userId = $request['user_id'];
        $request['total_posts'] ? $savedPosts = $request['total_posts'] : $savedPosts = NULL;
    
        $result = $this->post->deleteSaved($postId, $userId);
    
        if (!$result) {
            $this->helpers->setPopup('Error al remover el post');
        } else {
            if (($key = array_search($postId, $_SESSION['saved_posts'])) !== false) {
                unset($_SESSION['saved_posts'][$key]);
                $_SESSION['saved_posts'] = array_values($_SESSION['saved_posts']);
            }
            $this->helpers->setPopup('Post removido de guardados');
        }

        if (isset($savedPosts) && $savedPosts == 1) {
            header('Location: /');

            return;
        }

        if (isset($savedPosts) && $savedPosts == 7) {
            header('Location: /search/user/saved/' . $userId);

            return;
        }
    
        if (isset($request['curr_page'])) {
            $currPage = $request['curr_page'];
            $totalPages = $request['total_pages'];

            if ($totalPages > 1) {
                header('Location: /search/user/saved/' . $userId . '?page=' . $currPage);

                return;
            } else {
                header('Location: /search/user/saved/' . $userId);

                return;
            }
        }

        header('Location: /post/' . $postId);
    }

    public function changeRole(int $id, array $request) : void
    {
        $this->security->verifyCsrf($request['csrf'] ?? '');
        
        $newRole = $request['role'];

        $url = $request['curr-url'];

        $position = strpos($url, '?');

        if ($position !== false) {
            $url = substr($url, 0, $position);
        } else {
            $url = $url;
        }

        $result = $this->user->changeRole($id, $newRole);

        if ($result) {
            $this->helpers->setPopup('User role changed');
        } else {
            $this->helpers->setPopup('Error');
        }

        header('Location: ' . $url);
    }
}