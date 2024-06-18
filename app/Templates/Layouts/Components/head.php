<?php
$baseUrl = $GLOBALS['config']['base_url'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Blog">

  <link rel="icon" type="image/x-icon" href="imgs/favicon.png">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wdth,wght@0,62.5..100,100..900;1,62.5..100,100..900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" />

  <link rel="stylesheet" href="<?= $baseUrl ?>css/index.css">

  <?php if(isset($posts) && $posts): ?>
    <?php foreach($posts as $key => $post): ?>
      <link rel="preload" as="image" href="<?= $baseUrl . 'imgs/thumbs/' . htmlspecialchars($post['thumb']) ?>">
    <?php endforeach; ?>
  <?php endif; ?>

  <title>Blog</title>
</head>