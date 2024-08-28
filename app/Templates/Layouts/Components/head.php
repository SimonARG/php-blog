<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Blog">

  <link rel="icon" type="image/x-icon" href="/imgs/blog/<?= $blogConfig['icon'] ?>">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wdth,wght@0,62.5..100,100..900;1,62.5..100,100..900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" />

  <link rel="stylesheet" href="/css/index.css">

  <?php if(isset($posts) && $posts): ?>
    <?php foreach($posts as $key => $post): ?>
      <link rel="preload" as="image" href="<?= '/imgs/thumbs/' . htmlspecialchars($post['thumb']) ?>">
    <?php endforeach; ?>
  <?php endif; ?>

  <title>Blog</title>

  <style>
    :root {
      --panel-bg: <?= htmlspecialchars($blogConfig['panel_color'] . '7a' ?? '') ?>;
      --panel-h: <?= htmlspecialchars($blogConfig['panel_hover'] . '7a' ?? '') ?>;
      --panel-a: <?= htmlspecialchars($blogConfig['panel_active'] . '7a' ?? '') ?>;
      --text-2: <?= htmlspecialchars($blogConfig['text_dim'] ?? '') ?>;
    }
    body {
      <?php if(!empty($blogConfig['bg_color'])): ?>
        background-color: <?= htmlspecialchars($blogConfig['bg_color']) ?>;
      <?php elseif(!empty($blogConfig['bg_image'])): ?>
        background-image: url("/<?= htmlspecialchars(strpos($blogConfig['bg_image'], 'http') === 0 ? $blogConfig['bg_image'] : "../imgs/blog/{$blogConfig['bg_image']}") ?>");
      <?php endif; ?>
    }
    body *:not(.comment-count, .report) {
      border-color: <?= $blogConfig['text_color'] ?> !important;
    }
    body,
    .index > .post > .post-container a,
    .single > .post .poster a,
    .single > .user > .posts > a:hover,
    .single > .user > .comments > a:hover,
    .single > .user > .saved-posts:hover,
    div.body-preview {
      color: <?= $blogConfig['text_color'] ?>;
    }
  </style>
</head>