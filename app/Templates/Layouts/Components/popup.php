<?php if (isset($_SESSION['popup_content'])): ?>
  <?php
  $message = $_SESSION['popup_content'];
  unset($_SESSION['popup_content']);
  ?>
  <div class="popup-container">
    <div class="popup"><?= $message ?></div>
  </div>
<?php endif; ?>