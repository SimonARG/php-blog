<?php require 'Components/head.php'; ?>

<body>
    <?php require 'Components/header.php'; ?>
    <div class="body-container">
        <?= $content ?>
        <?php require 'Components/footer.php'; ?>
    </div>
    <?php require 'Components/sidebar.php'; ?>
    <?php require 'Components/popup.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/2.1.0/showdown.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="<?= $baseUrl . 'js/index.js' ?>"></script>
    <link rel="stylesheet" href="<?= $baseUrl . 'css/markdown.css' ?>">
</body>