<div class="contacts-container">
  <?php if (!isset($contacts)) : ?>
    No hay nada por aqui!
  <?php else : ?>
    <?php foreach ($contacts as $key => $contact) : ?>
      <div class="contact">
        <form class="delete-contact" method="POST" action="/admin/contact/delete/<?= $contact['id'] ?>">
          <button type="submit"><span class="material-symbols-rounded btn">delete</span></button>
        </form>

        <?php if (filter_var($contact['url'], FILTER_VALIDATE_EMAIL)) : ?>
            <span class="material-symbols-rounded btn edit-contact">edit_square</span>

            <div>E-Mail:</div>
            <div class="email"><?= $contact['url'] ?></div>
        <?php else : ?>
            <span class="material-symbols-rounded btn edit-contact">edit_square</span>

            <a href="<?= $contact['url'] ?>" target="_blank"><?= $contact['title'] ?></a>
        <?php endif; ?>

        <form class="update-contact" method="POST" action="/admin/contact/update/<?= $contact['id'] ?>">
          <span class="material-symbols-rounded btn close-edit-contact">close</span>
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
      </div>
    <hr>
    <?php endforeach; ?>
  <?php endif; ?>
  <span class="material-symbols-rounded add-contact btn">add_box</span>
</div>

<form class="new-contact" method="POST" action="/admin/contact/store">
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