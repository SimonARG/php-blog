<?php require 'Components/head.php'; ?>

<body>
    <?php require 'Components/header.php'; ?>
    <?= $content ?>
    <?php require 'Components/sidebar.php'; ?>
    <?php require 'Components/footer.php'; ?>
    <script src="<?= $baseUrl ?>/js/index.js"></script>
</body>