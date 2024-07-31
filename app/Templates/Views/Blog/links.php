<div class="links-container">
<?php if (!isset($links)): ?>
        No hay nada por aqui!
    <?php else: ?>
        <?php foreach($links as $key => $link): ?>
            <div>
                <a href="<?= $link['url'] ?>" target="_blank"><?= $link['title'] ?></a>
                <hr>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>