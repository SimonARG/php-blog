<div class="links-container">
  <?php if (!isset($links)) : ?>
    No hay nada por aqui!
  <?php else : ?>
    <?php foreach ($links as $key => $link) : ?>
      <div class="link">
        <?php if ($admin): ?>
            <form class="delete-link" method="POST" action="/admin/links/delete/<?= $link['id'] ?>">
              <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
              
              <button type="submit"><span class="material-symbols-rounded btn">delete</span></button>
            </form>

            <span class="material-symbols-rounded btn edit">edit_square</span>
        <?php endif; ?>

        <a href="<?= $link['url'] ?>" target="_blank"><?= $link['title'] ?></a>

        <?php if ($admin): ?>
            <form class="update" method="POST" action="/admin/links/update/<?= $link['id'] ?>">
              <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
              
              <span class="material-symbols-rounded btn close-edit">close</span>

              <h1>Editar Link</h1>

              <div>
                  <label for="name">Nombre</label>
                  <input required id="name" name="name" type="text" placeholder="Página" value="<?= $link['title'] ?>">
              </div>

              <div>
                  <label for="url">URL</label>
                  <input required id="url" name="url" type="text" placeholder="https://www.sitioweb.com/perfil" value="<?= $link['url'] ?>">
              </div>

              <input class="btn" type="submit" value="Editar">
            </form>
        <?php endif; ?>
      </div>
    <hr>
    <?php endforeach; ?>
  <?php endif; ?>

  <?php if ($admin): ?>
    <span class="material-symbols-rounded add-link btn">add_box</span>
  <?php endif; ?>
</div>

<?php if ($admin): ?>
    <form class="new-link" method="POST" action="/admin/links/store">
      <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
      
      <span class="material-symbols-rounded btn">close</span>

      <h1>Nuevo Link</h1>

      <div>
          <label for="name">Nombre</label>
          <input required id="name" name="name" type="text" placeholder="Página">
      </div>

      <div>
          <label for="url">URL</label>
          <input required id="url" name="url" type="text" placeholder="https://www.sitioweb.com/perfil">
      </div>

      <input class="btn" type="submit" value="Crear">
    </form>
<?php endif; ?>