<div class="sidebar pop">
  <div class="side-btns">
    <?php if ($guest): ?>
      <a href="/login">Iniciar Sesion</a>
      <a href="/register">Crear Cuenta</a>
    <?php endif; ?>

    <?php if (!$guest): ?>
      <?php if (!$restricted): ?>
        <a href="/post/new">Nuevo Post</a>
      <?php endif; ?>

      <a href="<?= '/user/' . $_SESSION['user_id'] ?>">Mi Perfil</a>

      <form action="/logout" method="POST">
        <input class="form-btn" type="submit" value="Cerrar Sesion">
      </form>

      <?php if ($elevated): ?>
        <a href="/admin/reports">Mod Panel</a>
      <?php endif; ?>

      <?php if ($admin): ?>
        <a href="/admin/settings">Configuracion</a>
      <?php endif; ?>
    <?php endif; ?>
  </div>
  
  <form class="search" action="/search" method="GET">
      <input type="text" autocomplete="off" name="query" class="searchbar" placeholder="Buscar...">

      <input class="search-btn" type="submit" value="🔎︎">
  </form>
</div>