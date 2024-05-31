<?php
$baseUrl = $GLOBALS['config']['base_url'];
?>

<div class="index-container">
  <div class="index">
    <?php foreach ($posts as $index => $post) : ?>
      <div class="post <?= 'post-' . $index + 1 ?>">
        <div class="post-container">
          <a href="/post/<?= $post['id'] ?>">
            <div class="date"><?= htmlspecialchars($post['created_at']) ?></div>
            <h1 class="title"><?= htmlspecialchars($post['title']) ?></h1>
            <h2 class="subtitle"><?= htmlspecialchars($post['subtitle']) ?></h2>
            <div class="poster">
              <span>Posted by </span><?= htmlspecialchars($post['username']) ?>
            </div>
            <div class="thumb">
              <img class="no-select" src="<?= $baseUrl . 'imgs/' . htmlspecialchars($post['thumb']) ?>" alt="">
            </div>
            <div class="body"><?= mb_strimwidth(htmlspecialchars($post['body']), 0, 160, "...") ?></div>
            <hr>
          </a>
        </div>
        <div>
          <a class="continue" href="/post/<?= $post['id'] ?>">Continuar leyendo...</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <?php require $baseUrl . 'App/Templates/Layouts/Components/pagination.php' ?>
</div>