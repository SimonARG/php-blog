<?php
$classes = [
  'user' => 'user',
  'admin' => 'admin',
  'poster' => 'poster',
  'restricted' => 'restricted',
  'banned' => 'banned',
  'mod' => 'mod'
];

$roleIds = [
  'user' => '4',
  'admin' => '1',
  'poster' => '3',
  'restricted' => '5',
  'banned' => '6',
  'mod' => '2'
];

$role = $user['role'];
$color = $classes[$role];

$currUrl = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>

<div class="single">
  <div class="user">
    <div class="role">
      <div class="<?= $color ?>">[<?= strtoupper($role) ?>]
        <?php if ($elevated): ?>
          <div class="role-arrow arrow">⯆</div>
        <?php endif; ?>
      </div>

      <?php if ($canReport): ?>
        <?php require __DIR__ . '/../../Layouts/Components/report.php'; ?>
      <?php endif; ?>

      <?php if ($elevated): ?>
        <form class="change-role" autocomplete="off" action="<?= '/user/role/' . $user['id'] ?>" method="POST">
          <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">

          <ul>
            <?php foreach ($classes as $key => $newRole): ?>
              <?php if (!($newRole == $role)): ?>
                <li class="<?= $newRole ?>">[<?= strtoupper($newRole) ?>]<div class="<?= $newRole ?>"><input type="radio" name="role" value="<?= $roleIds[$newRole] ?>"></div></li>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>
          <input class="btn" type="submit" value="Cambiar rol">
        </form>
      <?php endif; ?>
    </div>
    <h1><?= $user['name'] ?></h1>
    <h2><?= 'Registrado desde ' . $user['created_at'] ?></h2>

    <form action="/user/delete/<?= $user['id'] ?>" method="POST">
      <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">

      <input type="submit" value="Eliminar perfil">
    </form>

    <?php if (!($banned || $guest)): ?>
      <div class="report-holder">
        <div class="report-btn btn">Reportar usuario</div>
      </div>
    <?php endif; ?>

    <div class="user-avatar">
      <img src="<?= '/imgs/avatars/' . htmlspecialchars($user['avatar']) ?>" alt="Your avatar">
    </div>

    <?php if (!($guest) && ($_SESSION['user_id'] == $user['id'] || $elevated)): ?>
      <div class="avatar-label-holder">
        <label class="avatar-label btn" for="avatar">Cambiar avatar 🡅</label>
      </div>
    <?php endif; ?>

    <?php if ($user['updated_at']): ?>
      <h2><?= 'Actualizado en ' . $user['updated_at'] ?></h2>
    <?php endif ?>

    <div class="posts">
      <a href="<?= '/search/user/posts/' . $user['id'] ?>"><?= 'Posts: ' . $user['posts'] ?></a>
      <?php if ($lastPostId): ?>
        <a href="/post/<?= $lastPostId['id'] ?>">Ver ultimo post</a>
      <?php endif ?>
    </div>

    <?php if (!($guest) && $_SESSION['user_id'] == $user['id'] && $savedPosts > 0): ?>
      <a class="saved-posts" href="<?= '/search/user/saved/' . $user['id'] ?>"><?= 'Posts guardados: ' . $savedPosts ?></a>
    <?php endif; ?>

    <div class="comments">
      <div><?= 'Comentarios: ' . $user['comments'] ?></div>
      <?php if ($lastCommentPostId): ?>
        <a href="<?= '/post/' . $lastCommentPostId['post_id'] . '#comment-1' ?>">Ver ultimo comentario</a>
      <?php endif ?>
    </div>

    <?php if (!($guest) && ($_SESSION['user_id'] == $user['id'] || $elevated)): ?>
      <form class="user" autocomplete="off" enctype="multipart/form-data" action="<?= '/user/update/' . $user['id'] ?>" method="POST">
        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">

        <label for="name">Cambiar Nombre</label>
        <input type="text" id="name" name="name" <?php if (isset($errors['name_error'])): ?> placeholder="<?= $errors['name_error'] ?>" class="ph-error" <?php else : ?> value="<?= isset($errors) ? $old['name'] : $user['name'] ?>" <?php endif; ?>>

        <label for="email">Cambiar Email</label>
        <input type="email" id="email" name="email" <?php if (isset($errors['email_error'])): ?> placeholder="<?= $errors['email_error'] ?>" class="ph-error" <?php else : ?> value="<?= isset($errors) ? $old['email'] : $user['email'] ?>" <?php endif; ?>>

        <label for="new-password">Nueva Contraseña</label>
        <input type="password" id="new-password" name="new-password" <?php if (isset($errors['new_password_error'])): ?> placeholder="<?= $errors['new_password_error'] ?>" class="ph-error" <?php elseif (isset($errors['new_password_r_error'])): ?> placeholder="<?= $errors['new_password_r_error'] ?>" class="ph-error" <?php elseif (isset($errors)): ?> value="<?= $old['new-password'] ?>" <?php endif; ?>>

        <label for="new-password-r">Repetir Nueva Contraseña</label>
        <input type="password" id="new-password-r" name="new-password-r" <?php if (isset($errors['new_password_r_error'])): ?> placeholder="<?= $errors['new_password_r_error'] ?>" class="ph-error" <?php elseif (isset($errors)): ?> value="<?= $old['new-password-r'] ?>" <?php endif; ?>>

        <hr>

        <label for="password">Contraseña Actual</label>
        <input required minlength="8" type="password" id="password" name="password" <?php if (isset($errors['password_error'])): ?> placeholder="<?= $errors['password_error'] ?>" class="ph-error" <?php endif; ?>>

        <input type="hidden" name="method" value="PATCH">
        <input class="avatar-input" type="file" hidden name="avatar" id="avatar">

        <div>
          <input class="btn" type="submit" value="Guardar Cambios">
        </div>
      </form>
    <?php endif; ?>
  </div>
</div>