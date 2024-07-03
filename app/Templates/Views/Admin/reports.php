<?php
$baseUrl = $GLOBALS['config']['base_url'];

function truncateHTML($html_string, $length, $append = '&hellip;', $is_html = true)
{
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
  <div class="reports-title">
    <span><?= $unreviewed ?></span> unreviewed reports
  </div>

  <div class="reports-btns">
    <form method="post" action="<?= $baseUrl . 'admin/reports' ?>" class="btn">
      <input type="hidden" name="sort" value="0">
      <input type="submit" value="See newest">
    </form>

    <form method="post" action="<?= $baseUrl . 'admin/reports' ?>" class="btn">
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