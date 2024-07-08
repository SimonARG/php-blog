<?php
$baseUrl = $GLOBALS['config']['base_url'];

function truncateHTML($html_string, $length, $append = '&hellip;', $is_html = true) {
  $html_string = trim($html_string);
  $plain_text_length = strlen(strip_tags($html_string));
  $append = ($plain_text_length > $length) ? $append : '';
  $i = 0;
  $tags = [];
  $output = '';

  if ($is_html) {
      preg_match_all('/(<[^>]+>)?([^<]*)/', $html_string, $tag_matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);

      foreach ($tag_matches as $tag_match) {
          $tag_text_length = strlen(strip_tags($tag_match[2][0]));

          if ($i + $tag_text_length > $length) {
              $remaining_length = $length - $i;
              $output .= substr($tag_match[2][0], 0, $remaining_length) . $append;
              break;
          }

          $output .= $tag_match[0][0];
          $i += $tag_text_length;

          if (!empty($tag_match[1][0])) {
              $tag = substr(strtok($tag_match[1][0], " \t\n\r\0\x0B>"), 1);
              if ($tag[0] != '/') {
                  $tags[] = $tag;
              } elseif (end($tags) == substr($tag, 1)) {
                  array_pop($tags);
              }
          }
      }

      while (!empty($tags)) {
          $output .= '</' . array_pop($tags) . '>';
      }
  } else {
      $output = substr($html_string, 0, $length) . $append;
  }

  return $output;
}

$currUrl = $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>

<div class="index">
  <?php foreach ($posts as $index => $post) : ?>
    <?php require __DIR__ . '/../../Layouts/Components/report.php'; ?>

    <div class="post <?= 'post-' . $index + 1 ?>">
      <?php if($_SESSION): ?>
          <div class="menu">
            <div class="arrow">⯈</div>

            <ul class="dropdown">
              <?php if(($post['username'] == $_SESSION['username']) || $_SESSION['role'] == 'admin' || $_SESSION['role'] == 'mod'): ?>
                <li>
                  <a href="<?= $baseUrl . 'post/edit/' . $post['id'] ?>">Editar</a>
                </li>

                <li>
                  <form action="<?= $baseUrl ?>post/delete" method="POST"><input type="hidden" name="post_id" value="<?= $post['id'] ?>"><input type="submit" value="Eliminar"></form>
                </li>
              <?php endif; ?>

              <li>
                <div class="report-btn">Reportar</div>
              </li>

              <?php if(!in_array($post['id'], $_SESSION['saved_posts'])) : ?>
                <li>
                  <form method="POST" action="<?= $baseUrl . 'user/saved/save' ?>">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
                    <input type="hidden" name="curr_page" value="<?= $currentPage ?? 1 ?>">
                    <input type="submit" value="Guardar">
                  </form>
                </li>
              <?php endif; ?>
            </ul>
          </div>
        <?php endif; ?>
      <div class="post-container">
        <a href="/post/<?= $post['id'] ?>">
          <div class="date"><?= htmlspecialchars($post['created_at']) ?></div>
          <h1 class="title"><?= htmlspecialchars($post['title']) ?></h1>
          <h2 class="subtitle"><?= htmlspecialchars($post['subtitle']) ?></h2>
        </a>
          <div class="poster"><span>Posted by </span><a class="link" href="<?= $baseUrl . 'user/' . $post['user_id'] ?>"><?= htmlspecialchars($post['username']) ?></a></div>
        <a href="/post/<?= $post['id'] ?>">
          <div class="thumb">
            <img class="no-select" src="<?= $baseUrl . 'imgs/thumbs/' . htmlspecialchars($post['thumb']) ?>" alt="miniatura">
          </div>
          <div class="body body-preview"><?= truncateHTML(($post['body']), 160, "...") ?></div>
          <hr>
        </a>
      </div>

      <div>
        <a class="continue" href="/post/<?= $post['id'] ?>">Continuar leyendo...</a>
      </div>
      <?php if($post['comments'] > 0): ?>
        <a class="comment-count" href="/post/<?= $post['id'] . '#comment-1' ?>">
          <span class="material-symbols-rounded">comment</span>
          <div><?= $post['comments'] ?></div>
        </a>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>

<?php require __DIR__ . '/../../Layouts/Components/pagination.php' ?>