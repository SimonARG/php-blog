<?php
$baseUrl = $GLOBALS['config']['base_url'];
?>

<div class="sidebar">
  <div class="side-btns">
    <?php if (!$_SESSION): ?>
      <a href="<?= $baseUrl ?>login">Iniciar Sesion</a>
      <a href="<?= $baseUrl ?>register">Crear Cuenta</a>
    <?php endif; ?>

    <?php if ($_SESSION): ?>
      <a href="<?= $baseUrl ?>post/new">Nuevo Post</a>

      <a href="<?= $baseUrl . 'user/' . $_SESSION['user_id'] ?>">Mi Perfil</a>

      <form action="<?= $baseUrl ?>logout" method="POST">
        <input class="form-btn" type="submit" value="Cerrar Sesion">
      </form>
    <?php endif; ?>
  </div>
  
  <form class="search" action="<?= $baseUrl . 'search' ?>" method="GET">
      <input type="text" autocomplete="off" name="query" class="searchbar" placeholder="Buscar...">

      <input class="search-btn" type="submit" value="🔎︎">
  </form>
</div>