<?php require 'Components/head.php'; ?>

<body>
    <?php require 'Components/header.php'; ?>
    <div class="body-container">
        <div>
            <?= $content ?>
            <?php require 'Components/sidebar.php'; ?>
        </div>
        <?php require 'Components/footer.php'; ?>
    </div>
    <?php require 'Components/pop_sidebar.php'; ?>
    <?php require 'Components/popup.php'; ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/2.1.0/showdown.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="/js/index.js"></script>
    <link rel="stylesheet" href="/css/markdown.css">
</body>