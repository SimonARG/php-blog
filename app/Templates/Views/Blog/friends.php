<div class="friends-container">
  <?php if (!isset($friends)): ?>
    No hay nada por aqui!
  <?php else: ?>
    <?php foreach ($friends as $key => $friend): ?>
      <div class="friend">
        <?php if ($admin): ?>
          <form class="delete-friend" method="POST" action="/admin/friends/delete/<?= $friend['id'] ?>">
            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">

            <button type="submit"><span class="material-symbols-rounded btn">delete</span></button>
          </form>

          <span class="material-symbols-rounded btn edit">edit_square</span>

          <form class="update" method="POST" action="/admin/contact/update/<?= $friend['id'] ?>">
            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">

            <span class="material-symbols-rounded btn close-edit">close</span>
            <h1>Editar Blog</h1>

            <div>
              <label for="name">Nombre</label>
              <input required id="name" name="name" type="text" placeholder="Página" value="<?= $friend['title'] ?>">
            </div>

            <div>
              <label for="url">URL</label>
              <input required id="url" name="url" type="text" placeholder="https://www.sitioweb.com/perfil" value="<?= $friend['url'] ?>">
            </div>

            <div>
              <label for="comment">Comentario</label>
              <input required id="comment" name="comment" type="text" placeholder="..." value="<?= $friend['comment'] ?>">
            </div>

            <input class="btn" type="submit" value="Editar">
          </form>
        <?php endif; ?>

        <a href="<?= $friend['url'] ?>" target="_blank"><?= $friend['title'] ?></a>
        <div><?= $friend['comment'] ?></div>
        <hr>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
  <?php if ($admin): ?>
    <span class="material-symbols-rounded add-friend btn">add_box</span>
  <?php endif; ?>
</div>

<?php if ($admin): ?>
  <form class="new-friend" method="POST" action="/admin/friends/store">
    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">

    <span class="material-symbols-rounded btn">close</span>
    <h1>Nuevo Blog</h1>

    <div>
      <label for="name">Nombre</label>
      <input required id="name" name="name" type="text" placeholder="Blog">
    </div>

    <div>
      <label for="url">URL</label>
      <input required id="url" name="url" type="text" placeholder="https://www.blog.com">
    </div>

    <div>
      <label for="comment">Comentario</label>
      <input required id="comment" name="comment" type="text" placeholder="...">
    </div>

    <input class="btn" type="submit" value="Crear">
  </form>
<?php endif; ?>