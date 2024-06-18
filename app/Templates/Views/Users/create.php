<?php
$baseUrl = $GLOBALS['config']['base_url'];
?>

<div class="auth-container">
  <form class="auth" action="<?= $baseUrl ?>users/store" method="POST">
    <label for="name">Nombre de Usuario</label>
    <input pattern="^[A-Za-z0-9_\.\-]+" minlength="6" maxlength="17" required type="text" id="name" name="name"
    <?php if (isset($errors['name_error'])): ?>
      <?= "placeholder='" . $errors['name_error'] . "'" ?>
      <?= "class='ph-error'" ?>
    <?php else: ?>
      <?php if (isset($errors)): ?>
        <?= "value='" . $request['name'] . "'" ?>
      <?php else: ?>
        <?= "placeholder='Tu-Nombre'" ?>
      <?php endif; ?>
    <?php endif; ?>
    >
    <span>6-17 caracteres alfanumericos y . - _</span>
    
    <label for="email">Correo Electronico</label>
    <input required type="email" id="email" name="email"
    <?php if (isset($errors['email_error'])): ?>
      <?= "placeholder='" . $errors['email_error'] . "'" ?>
      <?= "class='ph-error'" ?>
    <?php else: ?>
      <?php if (isset($errors)): ?>
        <?= "value='" . $request['email'] . "'" ?>
      <?php else: ?>
        <?= "placeholder='ejemplo@gmail.com'" ?>
      <?php endif; ?>
    <?php endif; ?>
    >
    <span>E-mail valido para confirmacion</span>

    <label for="password">Contraseña</label>
    <input pattern="^(?=.*\d)(?=.*[A-Za-z]).{8,}" minlength="8" maxlength="30" required type="password" id="password" name="password"
    <?php if (isset($errors['password_error'])): ?>
      <?= "placeholder='" . $errors['password_error'] . "'" ?>
      <?= "class='ph-error'" ?>
    <?php elseif (isset($errors['password_r_error'])): ?>
      <?= "placeholder='" . $errors['password_r_error'] . "'" ?>
      <?= "class='ph-error'" ?>
    <?php else: ?>
      <?php if (isset($errors)): ?>
        <?= "value='" . $request['password'] . "'" ?>
      <?php endif; ?>
    <?php endif; ?>
    ></input>
    <span>8 o mas caracteres incluyendo una letra mayuscula, una minuscula, un numero y un signo</span>

    <label for="password-r">Repetir Contraseña</label>
    <input required type="password" id="password-r" name="password-r"
    <?php if (isset($errors['password_r_error'])): ?>
      <?= "placeholder='" . $errors['password_r_error'] . "'" ?>
      <?= "class='ph-error'" ?>
    <?php else: ?>
      <?php if (isset($errors)): ?>
        <?= "value='" . $request['password'] . "'" ?>
      <?php endif; ?>
    <?php endif; ?>
    ></input>
    
    <input class="btn" type="submit" value="Registrarse">
  </form>
</div>