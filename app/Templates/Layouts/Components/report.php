<?php
$currUrl = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>

<?php if (isset($index) && !(isset($comment))) : ?>
  <form class="report-form <?= 'report-' . ($index + 1) ?>" method="post" action="/report">
    <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf'] ?? '' ?>">

    <span class="material-symbols-rounded btn">close</span>

    <label for="comment">Razón</label>
    <input maxlength="40" type="text" id="comment" name="comment" placeholder="Opcional">

    <input type="hidden" name="type" value="post">
    <input type="hidden" name="id" value="<?= $post['id'] ?>">
    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
    <input type="hidden" name="curr_url" value="<?= $currUrl ?>">

    <input class="btn" type="submit" value="Reportar">
  </form>
<?php elseif (isset($index) && isset($comment)) : ?>
  <form class="report-form <?= 'report-' . ($index + 2) ?>" method="post" action="/report">
    <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf'] ?? '' ?>">

    <span class="material-symbols-rounded btn">close</span>

    <label for="comment">Razón</label>
    <input maxlength="40" type="text" id="comment" name="comment" placeholder="Opcional">

    <input type="hidden" name="type" value="comment">
    <input type="hidden" name="id" value="<?= $comment['id'] ?>">
    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
    <input type="hidden" name="curr_url" value="<?= $currUrl ?>">

    <input class="btn" type="submit" value="Reportar">
  </form>
<?php elseif (isset($user)) : ?>
  <form class="report-form report-1" method="post" action="/report">
    <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf'] ?? '' ?>">

    <span class="material-symbols-rounded btn">close</span>

    <label for="comment">Razón</label>
    <input maxlength="40" type="text" id="comment" name="comment" placeholder="Opcional">

    <input type="hidden" name="type" value="user">
    <input type="hidden" name="id" value="<?= $user['id'] ?>">
    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
    <input type="hidden" name="curr_url" value="<?= $currUrl ?>">

    <input class="btn" type="submit" value="Reportar">
  </form>
<?php elseif (isset($post) && !(isset($index))) : ?>
  <form class="report-form report-1" method="post" action="/report">
    <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf'] ?? '' ?>">

    <span class="material-symbols-rounded btn">close</span>

    <label for="comment">Razón</label>
    <input maxlength="40" type="text" id="comment" name="comment" placeholder="Opcional">

    <input type="hidden" name="type" value="post">
    <input type="hidden" name="id" value="<?= $post['id'] ?>">
    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
    <input type="hidden" name="curr_url" value="<?= $currUrl ?>">

    <input class="btn" type="submit" value="Reportar">
  </form>
<?php endif; ?>