<?php
$baseUrl = $GLOBALS['config']['base_url'];
?>

<header class="pos-stick">
  <div>
    <span class="material-symbols-rounded pos-ab menu-btn point no-select">menu</span>
    <div class="flex-r f-al-cent f-just-cent">
      <span class="page-title">Muerte Termica</span>
    </div>
    <span class="material-symbols-rounded pos-ab sidebar-btn point no-select">menu</span>

    <nav class="nav-menu">
      <ul>
        <li>
          <a href="<?= $baseUrl ?>">Inicio</a>
        </li>
        <li>
          <a href="<?= $baseUrl ?>">Contacto</a>
        </li>
        <li>
          <a href="<?= $baseUrl ?>">Otros Blogs</a>
        </li>
        <li>
          <a href="<?= $baseUrl ?>">Links</a>
        </li>
        <li>
          <a href="<?= $baseUrl ?>">Info</a>
        </li>
      </ul>
    </nav>
  </div>
</header>