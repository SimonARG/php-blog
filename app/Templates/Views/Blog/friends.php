<div class="friends-container">
    <?php if (!isset($friends)): ?>
        No hay nada por aqui!
    <?php else: ?>
        <?php foreach($friends as $key => $friend): ?>
            <div>
                <a href="<?= $friend['url'] ?>" target="_blank"><?= $friend['title'] ?></a>
                <div><?= $friend['comment'] ?></div>
                <hr>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>