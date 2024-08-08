<div class="single">
  <?php if($_SESSION && !($_SESSION['role'] == 'restricted') && !($_SESSION['role'] == 'banned')): ?>
    <?php require __DIR__ . '/../../Layouts/Components/report.php'; ?>
  <?php endif; ?>

  <div class="post">
    <h1 class="title"><?= htmlspecialchars($post['title']) ?></h1>
    <h2 class="subtitle"><?= htmlspecialchars($post['subtitle']) ?></h2>
    <div class="poster">
      <span>Posteado por <a href="<?= '/user/' . $post['user_id'] ?>"><?= htmlspecialchars($post['username']) ?></a> el <?= htmlspecialchars($post['created_at']) ?>
    </div>
    <div class="thumb">
      <img class="no-select" src="<?= '/imgs/thumbs/' . htmlspecialchars($post['thumb']) ?>" alt="">
    </div>
    <div class="body body-preview"><?= $post['body'] ?></div>
    <hr>
    <?php if ($_SESSION): ?>
      <div class="btns">
        <?php if(!in_array($post['id'], $_SESSION['saved_posts'])): ?>
          <form class="btn" method="POST" action="/user/saved/save">
            <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf'] ?? '' ?>">
            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
            <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
            <input type="submit" value="Guardar">
          </form>
        <?php else: ?>
          <form class="btn" method="POST" action="/user/saved/delete">
            <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf'] ?? '' ?>">

            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
            <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
            <input type="submit" value="Quitar de guardados">
          </form>
        <?php endif; ?>
        <div class="report-btn btn">Reportar</div>
        <?php if (($_SESSION['user_id'] == $post['user_id']) || $_SESSION['role'] == 'admin' || $_SESSION['role'] == 'mod'): ?>
          <a class="btn" href="<?= '/post/edit/' . $post['id'] ?>">Editar</a>
          <form class="btn" action="/post/delete/<?= $post['id'] ?>" method="POST">
            <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf'] ?? '' ?>">
            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
            <input type="submit" value="Eliminar">
          </form>
        <?php endif; ?>
      </div>
    <?php endif; ?>
    <div class="comment-count"><?= 'Comentarios: ' . $post['comments'] ?></div>
  </div>

  <div class="comments">
    <?php if ($_SESSION): ?>
      <form class="new-comment" method="POST" action="/comments/store">
        <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf'] ?? '' ?>">

        <label for="body">Nuevo Comentario</label>
        <textarea required maxlength="1600" name="body" id="body" placeholder="Comment..." autocomplete="off"<?php if (isset($errors['body_error'])): ?><?= "class='ph-error'" ?><?php endif; ?>></textarea>
        <input class="btn" type="submit" value="Comentar">
        <?php if ($_SESSION): ?>
          <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
          <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
        <?php endif; ?>
      </form>
    <?php endif; ?>

    <?php if (isset($comments)): ?>
      <?php foreach($comments as $index => $comment): ?>
        <?php if($_SESSION && !($_SESSION['role'] == 'restricted') && !($_SESSION['role'] == 'banned')): ?>
          <?php require __DIR__ . '/../../Layouts/Components/report.php'; ?>
        <?php endif; ?>

        <div class="comment" id="<?= 'comment-' . $index + 1 ?>">
          <div class="dropdown">
            <?php if ($_SESSION && ($_SESSION['user_id'] == $comment['user_id'] || ($_SESSION['role'] == 'mod' || $_SESSION['role'] == 'admin'))): ?>
              <form class="edit" action="<?= '/comments/update/' . $comment['id'] ?>" method="POST">
                <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf'] ?? '' ?>">

                <textarea name="body" id="body"><?= $comment['body'] ?></textarea>
                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                <input class="btn" type="submit" value="Editar">
              </form>
            <?php endif; ?>

            <div class="report-btn btn">Reportar</div>

            <?php if ($_SESSION && ($_SESSION['user_id'] == $comment['user_id'] || ($_SESSION['role'] == 'mod' || $_SESSION['role'] == 'admin'))): ?>
              <form class="del" action="/comments/delete/<?= $comment['id'] ?>" method="POST">
                <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf'] ?? '' ?>">

                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                <input class="btn" type="submit" value="Eliminar">
              </form>
            <?php endif; ?>
          </div>
          <?php if ($_SESSION && !($_SESSION['role'] == 'restricted' || $_SESSION['role'] == 'banned')): ?>
            <div class="arrow">⯆</div>
          <?php endif; ?>

          <div class="comment-header">
            Posteado por <a href="<?= '/user/' . $comment['user_id'] ?>"><?= $comment['username'] ?></a><?= ' el ' . $comment['created_at'] ?>
          </div>

          <div class="comment-content">
            <div class="comment-avatar">
              <img src="<?= '/imgs/avatars/' . htmlspecialchars($comment['avatar']) ?>" alt="<?= $comment['username'] . "'s avatar" ?>">
            </div>
            <div class="body-col">
              <?= $comment['body'] ?>
            </div>
          </div>

          <div class="comment-footer">
            <div>
              <?php if ($comment['updated_at']): ?>
                <?= 'Actualizado en ' . $comment['updated_at'] ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>