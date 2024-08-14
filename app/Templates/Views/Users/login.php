<div class="auth-container login">
  <form class="auth" action="/auth" method="POST">
    <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf'] ?? '' ?>">
    
    <label for="email">Correo Electronico</label>
    <input type="text" id="email" name="email"
    <?php if (isset($errors['error'])): ?>
      <?= "placeholder='" . $errors['error'] . "'" ?>
      <?= "class='ph-error'" ?>
    <?php else: ?>
      <?= "placeholder='ejemplo@gmail.com'" ?>
    <?php endif; ?>
    >

    <label for="password">Contraseña</label>
    <input type="password" id="password" name="password"
    <?php if (isset($errors['error'])): ?>
      <?= "placeholder='" . $errors['error'] . "'" ?>
      <?= "class='ph-error'" ?>
    <?php endif; ?>
    >

    <input class="btn" type="submit" value="Iniciar Sesion">
  </form>
</div>