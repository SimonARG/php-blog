<?php
$currUrl = $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>

<div class="index">
  <div class="reports-title">
    <span><?= $unreviewed ?></span> unreviewed reports
  </div>

  <div class="reports-btns">
    <form method="post" action="/admin/reports" class="btn">
      <input type="hidden" name="sort" value="0">
      <input type="submit" value="See newest">
    </form>

    <form method="post" action="/admin/reports" class="btn">
      <input type="hidden" name="sort" value="1">
      <input type="submit" value="See unreviewed">
    </form>
  </div>

  <?php foreach ($reports as $key => $report) : ?>
    <div class="<?= 'report' . ($report['reviewed'] ? ' done' : '') ?>">
      <div class="report-container">
        <div>
          <div>Report date:</div>
          <div><?= $report['created_at'] ?></div>
        </div>

        <?php if ($report['comment']): ?>
          <div>
            <div>Report reason:</div>
            <div><?= $report['comment'] ?></div>
          </div>
        <?php endif; ?>

        <div>
          <div>Reported resource:</div>
          <?php if ($report['resource_type'] == 'Post'): ?>
            <div><a href="/post/<?= $report['resource_id'] ?>"><?= $report['resource_type'] ?></a></div>
          <?php elseif ($report['resource_type'] == 'Comment'): ?>
            <div><a href="/"><?= $report['resource_type'] ?></a></div>
          <?php elseif ($report['resource_type'] == 'User'): ?>
            <div><a href="/user/<?= $report['resource_id'] ?>"><?= $report['resource_type'] ?></a></div>
          <?php endif; ?>
        </div>

        <div>
          <div>Reported by:</div>
          <div><a href="/user/<?= $report['reporter_id'] ?>"><?= $report['reporter'] ?></a></div>
        </div>

        <?php if (!$report['reviewed']): ?>
          <div class="see">
            <a class="btn" href="/admin/report/<?= $report['id'] ?>">Ver</a>
          </div>
        <?php endif; ?>

        <?php if ($report['reviewer']): ?>
          <div>
            <div>Reviewed by:</div>
            <div><a href="/user/<?= $report['reviewer_id'] ?>"><?= $report['reviewer'] ?></a></div>
          </div>
        <?php endif; ?>

        <?php if ($report['reviewed']): ?>
          <div>
            <?php if (count($report['mod_actions']) > 1): ?>
              <div>Actions:</div>
              <?php else: ?>
                <div>Action:</div>
            <?php endif; ?>
            <div>
              <?php foreach ($report['mod_actions'] as $key => $action): ?>
                <?php if (count($report['mod_actions']) > 1): ?>
                  <?php if ($key == (count($report['mod_actions']) - 1)): ?>
                    <div><?= '- ' . $action['consequence'] ?></div>
                  <?php else: ?>
                    <div><?= '- ' . $action['consequence'] . ',' ?></div>
                  <?php endif; ?>
                <?php else: ?>
                  <div><?= $action['consequence'] ?></div>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>

  <?php if(count($reports) < 1): ?>
    <div class="filler"></div>
    <div class="filler"></div>
    <div class="filler"></div>
  <?php elseif (count($reports) < 4): ?>
    <div class="filler"></div>
    <div class="filler"></div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/../../Layouts/Components/pagination.php' ?>