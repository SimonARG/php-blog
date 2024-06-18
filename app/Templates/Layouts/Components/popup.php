<?php if (isset($_GET['popup_content']) || (isset($popupContent))): ?>
  <div class="popup-container">
    <div class="popup"><?= isset($_GET['popup_content']) ? $_GET['popup_content'] : (isset($popupContent) ? $popupContent : '') ?></div>
  </div>
<?php endif; ?>