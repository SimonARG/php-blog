<?php if ($totalPages > 1) : ?>
  <ul class="pagination">
      <?php if ($currentPage > 1) : ?>
        <?php if (isset($_GET['query'])) : ?>
          <a href="<?= 'search?query=' . $_GET['query'] . '&page=' . $currentPage - 1 ?>">🞀</a>
        <?php elseif (isset($user)): ?>
          <a href="<?= '/search/user/posts/' . $user['id'] . '?page=' . $currentPage - 1 ?>">🞀</a>
        <?php else: ?>
          <a href="?page=<?= $currentPage - 1 ?>">🞀</a>
        <?php endif; ?>
      <?php endif; ?>

      <?php for ($page = 1; $page <= $totalPages; $page++) : ?>
        <?php if ($page < $currentPage - 3): ?>
          <?php continue; ?>
        <?php endif; ?>

        <?php if ($page === $currentPage - 3): ?>
          <?php if (isset($_GET['query'])) : ?>
            <a href="<?= 'search?query=' . $_GET['query'] . '&page=' . 1 ?>">...</a>
            <?php continue; ?>
          <?php elseif (isset($user)): ?>
            <a href="<?= '/search/user/posts/' . $user['id'] . '?page=' . 1 ?>">...</a>
          <?php else: ?>
            <a href="?page=<?= 1 ?>">...</a>
            <?php continue; ?>
          <?php endif; ?>
        <?php endif; ?>

        <?php if (isset($_GET['query'])) : ?>
          <a href="<?= 'search?query=' . $_GET['query'] . '&page=' . $page ?>" <?= $page == $currentPage ? ' class="active"' : '' ?>><?= $page ?></a>
        <?php elseif (isset($user)): ?>
          <a href="<?= '/search/user/posts/' . $user['id'] . '?page=' . $page ?>" <?= $page == $currentPage ? ' class="active"' : '' ?>><?= $page ?></a>
        <?php else: ?>
          <a href="<?= '?page=' . $page ?>" <?= $page == $currentPage ? ' class="active"' : '' ?>><?= $page ?></a>
        <?php endif; ?>

        <?php if ($page > $currentPage + 1): ?>
          <?php if (isset($_GET['query'])) : ?>
            <a href="<?= 'search?query=' . $_GET['query'] . '&page=' . $totalPages ?>">...</a>
            <?php break; ?>
          <?php elseif (isset($user)): ?>
            <a href="<?= '/search/user/posts/' . $user['id'] . '?page=' . $totalPages ?>">...</a>
          <?php else: ?>
            <a href="?page=<?= $totalPages ?>">...</a>
            <?php break; ?>
          <?php endif; ?>
        <?php endif; ?>
      <?php endfor; ?>

      <?php if ($currentPage < $totalPages) : ?>
        <?php if (isset($_GET['query'])) : ?>
          <a href="<?= 'search?query=' . $_GET['query'] . '&page=' . $currentPage + 1 ?>">🞂</a>
        <?php elseif (isset($user)): ?>
          <a href="<?= '/search/user/posts/' . $user['id'] . '?page=' . $currentPage + 1 ?>">🞂</a>
        <?php else: ?>
          <a href="<?= '?page=' . $currentPage + 1 ?>">🞂</a>
        <?php endif; ?>
      <?php endif; ?>
  </ul>
<?php endif; ?>