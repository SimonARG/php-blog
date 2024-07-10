<div class="create-post">
  <form enctype="multipart/form-data" class="new-post-form" action="/post/store" method="POST">
    <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf'] ?? '' ?>">

    <label for="title">Titulo</label>
    <input minlength="4" maxlength="40" required type="text" class="title" id="title" name="title" <?php if (isset($errors['title_error'])) : ?> <?= "placeholder='" . $errors['title_error'] . "'" ?> <?= "class='ph-error'" ?> <?php else : ?> <?php if (isset($errors)) : ?> <?= "value='" . $request['title'] . "'" ?> <?php else : ?> <?= "placeholder='Titulo'" ?> <?php endif; ?> <?php endif; ?>>

    <label for="subtitle">Subtitulo</label>
    <input class="subtitle" minlength="4" maxlength="50" required type="text" id="subtitle" name="subtitle" <?php if (isset($errors['subtitle_error'])) : ?> <?= "placeholder='" . $errors['subtitle_error'] . "'" ?> <?= "class='subtitle ph-error'" ?> <?php else : ?> <?php if (isset($errors)) : ?> <?= "value='" . $request['subtitle'] . "'" ?> <?php else : ?> <?= "placeholder='Subtitulo' class='subtitle'" ?> <?php endif; ?> <?php endif; ?>>

    <label for="thumb">Miniatura</label>
    <div class="file-up">
      <label class="btn file-up-btn" for="thumb">🡅</label>
      <input type="text" required <?php if (isset($errors['thumb_error'])) : ?> <?= "placeholder='" . $errors['thumb_error'] . "'" ?> <?= "class='file-up-field ph-error'" ?> <?php else : ?> <?php if (isset($errors)) : ?> <?= "value='" . basename($request['thumb']) . "'" ?> <?php else : ?> <?= "placeholder='imagen.jpg' class='file-up-field'" ?> <?php endif; ?> <?php endif; ?>>
    </div>
    <input type="file" id="thumb" name="thumb" hidden accept=".jpg, .png, .webp, .avif, .jpeg, .jfif">

    <div class="editor-tabs">
      <a class="tab input-tab active">
        <label for="body">Contenido</label>
      </a>

      <a class="tab description-tab">
        <span>Vista</span>
      </a>

      <span class="material-symbols-rounded btn formatting-btn">info</span>
    </div>

    <div class="tab-content">
      <div class="tab-pane active">
        <textarea minlength="10" maxlength="40000" required name="body" id="body" <?php if (isset($errors['body_error'])) : ?><?= "class='ph-error'" ?><?php endif; ?>><?php if (isset($errors) && !isset($errors['body_error'])) : ?><?= $request['body'] ?><?php endif; ?></textarea>
      </div>

      <div class="tab-pane">
        <div class="preview body-preview" id="body-preview"></div>
      </div>
    </div>

    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">

    <input class="btn" type="submit" value="Crear Post">
  </form>
</div>

<div class="formatting-help body-preview">
  <span class="material-symbols-rounded btn">close</span>
  <div>
    <h1># Title</h1>
  </div>
  <div class="group">
    <div>**<strong>Bold</strong>**</div>
    <div>*<em>Italic</em>*</div>
    <div>~~<del>Strikethrough</del>~~</div>
  </div>
  <div>**<strong>Bold and _<em>nested italic</em>_</strong>**</div>
  <div>***<strong><em>Bold and italic</em></strong>***</div>
  <div>Sub&lt;sub&gt;<sub>script</sub>&lt;/sub&gt;</div>
  <div>Super&lt;sup&gt;<sup>script</sup>&lt;/sup&gt;</div>
  <div class="quote">
    <blockquote>
      <p>> Quote</p>
    </blockquote>
  </div>
  <div>[Link]<a href="">(https://www.link.com/)</a></div>
  <div>![Image](https://www.mysite.com/image.jpg)</div>
  <div class="list">
    <ul>
      <li>* List</li>
      <ul>
        <li>* Nested List</li>
      </ul>
    </ul>
  </div>
  <div class="list">
    <ol>
      <li>1. List</li>
      <ol>
        <li>1. Nested List</li>
      </ol>
    </ol>
  </div>
</div>