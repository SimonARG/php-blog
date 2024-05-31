<div class="index-container">
  <div class="single">
    <div class="post">
      <h1 class="title"><?= htmlspecialchars($post['title']) ?></h1>
      <h2 class="subtitle"><?= htmlspecialchars($post['subtitle']) ?></h2>
      <div class="poster">
        <span>Posted by </span><?= htmlspecialchars($post['username']) ?><span> on </span><?= htmlspecialchars($post['created_at']) ?>
      </div>
      <div class="thumb">
        <img class="no-select" src="<?= $baseUrl . 'imgs/' . htmlspecialchars($post['thumb']) ?>" alt="">
      </div>
      <div class="body"><?= htmlspecialchars($post['body']) ?></div>
    </div>
  </div>
</div>