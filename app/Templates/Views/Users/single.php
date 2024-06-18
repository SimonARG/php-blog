<?php 
$classes = [
    'user' => 'user',
    'admin' => 'admin',
    'poster' => 'poster',
    'restricted' => 'restricted',
    'banned' => 'banned',
    'mod' => 'mod'
];

$role = $user['role'];
$color = $classes[$role];
?>

<div class="single">
  <div class="user">
    <div class="role">
      <span class="<?= $color ?>">[<?= strtoupper($role) ?>]</span>
    </div>
    <h1><?= $user['name'] ?></h1>
    <h2><?= 'Registered since ' . $user['created_at'] ?></h2>

    <div class="user-avatar">
      <img src="<?= $baseUrl . 'imgs/avatars/' . htmlspecialchars($user['avatar']) ?>" alt="Your avatar">
    </div>
    <?php if ($_SESSION): ?>
      <?php if ($_SESSION['user_id'] == $user['id']): ?>
        <div class="avatar-label-holder">
          <label class="avatar-label btn" for="avatar">Change Avatar 🡅</label>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if ($user['updated_at']) : ?>
      <h2><?= 'Last edited at ' . $user['updated_at'] ?></h2>
    <?php endif ?>

    <div class="posts">
      <a><?= 'Posts: ' . $user['posts'] ?></a>
      <?php if ($lastPostId) : ?>
        <a href="/post/<?= $lastPostId['id'] ?>">See latest post</a>
      <?php endif ?>
    </div>

    <div class="comments">
      <div><?= 'Comments: ' . $user['comments'] ?></div>
      <?php if ($lastCommentPostId) : ?>
        <a href="<?= '/post/' . $lastCommentPostId['post_id'] . '#comment-1' ?>">See latest comment</a>
      <?php endif ?>
    </div>

    <?php if ($_SESSION): ?>
      <?php if ($_SESSION['user_id'] == $user['id']): ?>
        <form enctype="multipart/form-data" action="<?= $baseUrl . 'user/update/' . $user['id'] ?>" method="POST">
          <label for="name">Cambiar Nombre</label>
          <input type="text" id="name" name="name"
          <?php if (isset($errors['name_error'])): ?>
            <?= "placeholder='" . $errors['name_error'] . "'" ?>
            <?= "class='ph-error'" ?>
          <?php else: ?>
            <?php if (isset($errors)): ?>
              <?= "value='" . $old['name'] . "'" ?>
            <?php else: ?>
              <?= "value='" . $user['name'] . "'" ?>
            <?php endif; ?>
          <?php endif; ?>
          >

          <label for="email">Cambiar Email</label>
          <input type="email" id="email" name="email"
          <?php if (isset($errors['email_error'])): ?>
            <?= "placeholder='" . $errors['email_error'] . "'" ?>
            <?= "class='ph-error'" ?>
          <?php else: ?>
            <?php if (isset($errors)): ?>
              <?= "value='" . $old['email'] . "'" ?>
            <?php else: ?>
              <?= "value='" . $user['email'] . "'" ?>
            <?php endif; ?>
          <?php endif; ?>
          >

          <label for="new-password">Nueva Contraseña</label>
          <input type="password" id="new-password" name="new-password"
          <?php if (isset($errors['new_password_error'])): ?>
            <?= "placeholder='" . $errors['new_password_error'] . "'" ?>
            <?= "class='ph-error'" ?>
          <?php elseif (isset($errors['new_password_r_error'])): ?>
            <?= "placeholder='" . $errors['new_password_r_error'] . "'" ?>
            <?= "class='ph-error'" ?>
          <?php else: ?>
            <?php if (isset($errors)): ?>
              <?= "value='" . $old['new-password'] . "'" ?>
            <?php endif; ?>new-
          <?php endif; ?>
          >

          <label for="new-password-r">Repetir Nueva Contraseña</label>
          <input type="password" id="new-password-r" name="new-password-r"
          <?php if (isset($errors['new_password_r_error'])): ?>
            <?= "placeholder='" . $errors['new_password_r_error'] . "'" ?>
            <?= "class='ph-error'" ?>
          <?php else: ?>
            <?php if (isset($errors)): ?>
              <?= "value='" . $old['new-password-r'] . "'" ?>
            <?php endif; ?>
          <?php endif; ?>
          >

          <label for="password">Contraseña Actual</label>
          <input type="password" id="password" name="password"
          <?php if (isset($errors['password_error'])): ?>
            <?= "placeholder='" . $errors['password_error'] . "'" ?>
            <?= "class='ph-error'" ?>
          <?php endif; ?>
          >

          <input type="hidden" name="method" value="PATCH">
          <input class="avatar-input" type="file" hidden name="avatar" id="avatar">

          <div>
            <input class="btn" type="submit" value="Guardar Cambios">
          </div>
        </form>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>