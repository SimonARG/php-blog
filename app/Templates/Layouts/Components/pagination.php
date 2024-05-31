<?php if ($totalPages > 1) : ?>
  <ul class="pagination">
      <?php if ($currentPage > 1) : ?>
        <a href="?page=<?= $currentPage - 1 ?>">🞀</a>
      <?php endif; ?>

      <?php for ($page = 1; $page <= $totalPages; $page++) : ?>
        <?php if ($page < $currentPage - 3): ?>
          <?php continue; ?>
        <?php endif; ?>

        <?php if ($page === $currentPage - 3): ?>
          <a href="?page=<?= 1 ?>">...</a>
          <?php continue; ?>
        <?php endif; ?>

        <a href="?page=<?= $page ?>" <?= $page == $currentPage ? ' class="active"' : '' ?>><?= $page ?></a>

        <?php if ($page > $currentPage + 1): ?>
          <a href="?page=<?= $totalPages ?>">...</a>
          <?php break; ?>
        <?php endif; ?>
      <?php endfor; ?>

      <?php if ($currentPage < $totalPages) : ?>
        <a href="?page=<?= $currentPage + 1 ?>">🞂</a>
      <?php endif; ?>
  </ul>
<?php endif; ?>