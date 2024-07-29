<div class="sidebar pop">
  <div class="side-btns">
    <?php if (!$_SESSION): ?>
      <a href="/login">Iniciar Sesion</a>
      <a href="/register">Crear Cuenta</a>
    <?php endif; ?>

    <?php if ($_SESSION): ?>
      <a href="/post/new">Nuevo Post</a>

      <a href="<?= '/user/' . $_SESSION['user_id'] ?>">Mi Perfil</a>

      <form action="/logout" method="POST">
        <input class="form-btn" type="submit" value="Cerrar Sesion">
      </form>

      <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'mod'): ?>
        <a href="/admin/reports">Admin Panel</a>
      <?php endif; ?>
    <?php endif; ?>
  </div>
  
  <form class="search" action="/search" method="GET">
      <input type="text" autocomplete="off" name="query" class="searchbar" placeholder="Buscar...">

      <input class="search-btn" type="submit" value="🔎︎">
  </form>
</div>