<div class="contacts-container">
    <?php if (!isset($contacts)): ?>
        No hay nada por aqui!
    <?php else: ?>
        <?php foreach($contacts as $key => $contact): ?>
            <?php if(filter_var($contact['url'], FILTER_VALIDATE_EMAIL)): ?>
                <div>
                    <div>E-Mail:</div>
                    <div class="email"><?= $contact['url'] ?></div>
                    <hr>
                </div>
            <?php else: ?>
                <div>
                    <a href="<?= $contact['url'] ?>" target="_blank"><?= $contact['title'] ?></a>
                    <hr>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>