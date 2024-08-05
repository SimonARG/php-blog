<div class="settings-container">
  <form action="/admin/title" method="POST">
    <label for="title">Cambiar título:</label>
    <div>
      <input id="title" name="title" type="text" value="<?= $blogConfig["title"] ?>">
      <input class="btn" type="submit" value="&gt;">
    </div>
  </form>

  <form enctype="multipart/form-data" action="/admin/icon" method="POST">
    <label>Cambiar ícono de pestaña:</label>
    <div>
      <label class="btn file-up-btn" for="icon">🡅</label>
      <input type="text" required placeholder="imagen.jpg" class="file-up-field">
      <input hidden id="icon" name="icon" type="file" accept=".jpg, .gif, .png, .webp, .avif, .jpeg, .jfif">
      <input class="btn" type="submit" value="&gt;">
    </div>
  </form>

  <form enctype="multipart/form-data" action="/admin/bg-image" method="POST">
    <label>Cambiar la imágen de fondo:</label>
    <div>
      <label class="btn file-up-btn" for="bg-image">🡅</label>
      <input type="text" required placeholder="imagen.jpg" class="file-up-field">
      <input hidden id="bg-image" name="bg-image" type="file" accept=".jpg, .gif, .png, .webp, .avif, .jpeg, .jfif, .gif">
      <input class="btn" type="submit" value="&gt;">
    </div>
  </form>

  <form action="/admin/bg-color" method="POST">
    <label for="bg-color">Cambiar color de fondo:</label>
    <div>
      <input type="color" id="bg-color" name="bg-color" <?php if ($blogConfig['bg_color']) : ?> value="<?= $blogConfig['bg_color'] ?>" <?php endif; ?>>
      <input class="btn" type="submit" value="&gt;">
    </div>
  </form>

  <form action="/admin/text" method="POST">
    <label for="text-color">Cambiar color del texto principal:</label>
    <div>
      <input type="color" id="text-color" name="text-color" <?php if ($blogConfig['text_color']) : ?> value="<?= $blogConfig['text_color'] ?>" <?php endif; ?>>
      <input class="btn" type="submit" value="&gt;">
    </div>
  </form>

  <form action="/admin/text-dim" method="POST">
    <label for="text-dim">Cambiar color del texto secundario:</label>
    <div>
      <input type="color" id="text-dim" name="text-dim" <?php if ($blogConfig['text_dim']) : ?> value="<?= $blogConfig['text_dim'] ?>" <?php endif; ?>>
      <input class="btn" type="submit" value="&gt;">
    </div>
  </form>

  <form action="/admin/panel-bg" method="POST">
    <label for="panel-color">Cambiar color de panel:</label>
    <div>
      <input type="color" id="panel-color" name="panel-color" <?php if ($blogConfig['panel_color']) : ?> value="<?= $blogConfig['panel_color'] ?>" <?php endif; ?>>
      <input class="btn" type="submit" value="&gt;">
    </div>
  </form>

  <form action="/admin/panel-h" method="POST">
    <label for="panel-hover">Cambiar color de panel en hover:</label>
    <div>
      <input type="color" id="panel-hover" name="panel-hover" <?php if ($blogConfig['panel_hover']) : ?> value="<?= $blogConfig['panel_hover'] ?>" <?php endif; ?>>
      <input class="btn" type="submit" value="&gt;">
    </div>
  </form>

  <form action="/admin/panel-a" method="POST">
    <label for="panel-active">Cambiar color de panel activo:</label>
    <div>
      <input type="color" id="panel-active" name="panel-active" <?php if ($blogConfig['panel_active']) : ?> value="<?= $blogConfig['panel_active'] ?>" <?php endif; ?>>
      <input class="btn" type="submit" value="&gt;">
    </div>
  </form>
</div>