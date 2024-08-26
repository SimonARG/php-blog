<div class="contacts-container">
  <?php if (!isset($contacts)) : ?>
    No hay nada por aqui!
  <?php else : ?>
    <?php foreach ($contacts as $key => $contact) : ?>
      <div class="contact">
        <?php if ($admin): ?>
          <form class="delete-contact" method="POST" action="/admin/contact/delete/<?= $contact['id'] ?>">
            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">

            <button type="submit"><span class="material-symbols-rounded btn">delete</span></button>
          </form>

          <span class="material-symbols-rounded btn edit">edit_square</span>
        <?php endif; ?>


        <?php if (filter_var($contact['url'], FILTER_VALIDATE_EMAIL)) : ?>
            <div>E-Mail:</div>
            <div class="email"><?= $contact['url'] ?></div>
        <?php else : ?>
            <a href="<?= $contact['url'] ?>" target="_blank"><?= $contact['title'] ?></a>
        <?php endif; ?>

        <?php if ($admin): ?>
          <form class="update" method="POST" action="/admin/contact/update/<?= $contact['id'] ?>">
            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
            
            <span class="material-symbols-rounded btn close-edit">close</span>
            <h1>Editar Contacto</h1>

            <div>
              <label for="name">Nombre</label>
              <input required id="name" name="name" type="text" placeholder="Página" value="<?= $contact['title'] ?>">
            </div>

            <div>
              <label for="url">URL</label>
              <input required id="url" name="url" type="text" placeholder="https://www.sitioweb.com/perfil" value="<?= $contact['url'] ?>">
            </div>

            <input class="btn" type="submit" value="Editar">
          </form>
        <?php endif; ?>
      </div>
    <hr>
    <?php endforeach; ?>
  <?php endif; ?>

  <?php if ($admin): ?>
    <span class="material-symbols-rounded add-contact btn">add_box</span>
  <?php endif; ?>
</div>

<?php if ($admin): ?>
  <form class="new-contact" method="POST" action="/admin/contact/store">
    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">

    <span class="material-symbols-rounded btn">close</span>
    <h1>Nuevo Contacto</h1>

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