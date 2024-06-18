<?php
$baseUrl = $GLOBALS['config']['base_url'];
?>

<div class="edit-post">
  <form enctype="multipart/form-data" class="update-post-form" action="<?= $baseUrl . 'post/update/' . $post['id'] ?>" method="POST">
    <label for="title">Titulo</label>
    <input minlength="4" maxlength="40" required type="text" class="title" id="title" name="title"
    <?php if (isset($errors['title_error'])): ?>
      <?= "placeholder='" . $errors['title_error'] . "'" ?>
      <?= "class='ph-error'" ?>
    <?php else: ?>
      <?php if (isset($errors)): ?>
        <?= "value='" . $request['title'] . "'" ?>
      <?php else: ?>
        <?= "placeholder='Titulo'" ?>
        <?= "value='" . $post['title'] . "'" ?>
      <?php endif; ?>
    <?php endif; ?>
    >

    <label for="subtitle">Subtitulo</label>
    <input class="subtitle" minlength="4" maxlength="50" required type="text" id="subtitle" name="subtitle"
    <?php if (isset($errors['subtitle_error'])): ?>
      <?= "placeholder='" . $errors['subtitle_error'] . "'" ?>
      <?= "class='subtitle ph-error'" ?>
    <?php else: ?>
      <?php if (isset($errors)): ?>
        <?= "value='" . $request['subtitle'] . "'" ?>
      <?php else: ?>
        <?= "placeholder='Subtitulo' class='subtitle'" ?>
        <?= "value='" . $post['subtitle'] . "'" ?>
      <?php endif; ?>
    <?php endif; ?>
    >

    <label for="thumb">Miniatura</label>
    <div class="file-up">
      <label class="btn file-up-btn" for="thumb">🡅</label>
      <input type="text" required
      <?php if (isset($errors['thumb_error'])): ?>
        <?= "placeholder='" . $errors['thumb_error'] . "'" ?>
        <?= "class='file-up-field ph-error'" ?>
      <?php else: ?>
        <?php if (isset($errors)): ?>
          <?= "value='" . basename($request['thumb']) . "'" ?>
        <?php else: ?>
          <?= "placeholder='imagen.jpg' class='file-up-field'" ?>
          <?= "value='" . $post['thumb'] . "'" ?>
        <?php endif; ?>
      <?php endif; ?>
      >
    </div>
    <input type="file" id="thumb" name="thumb" hidden accept=".jpg, .png, .webp, .avif, .jpeg, .jfif">

    <div class="editor-tabs">
      <a class="tab input-tab active">
        <label for="body">Contenido</label>
      </a>

      <a class="tab description-tab">
        <span>Vista</span>
      </a>
    </div>

    <div class="tab-content">
      <div class="tab-pane active">
        <textarea minlength="10" maxlength="40000" required name="body" id="body"<?php if (isset($errors['body_error'])): ?><?= "class='ph-error'" ?><?php endif; ?>><?php if (isset($errors) && !isset($errors['body_error'])): ?><?= $request['body']?><?php else: ?><?= $post['body'] ?><?php endif; ?></textarea>
      </div>

      <div class="tab-pane">
        <div class="preview body-preview" id="body-preview"></div>
      </div>
    </div>

    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">

    <input class="btn" type="submit" value="Editar Post">
  </form>
</div>