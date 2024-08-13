<?php
  $type = '';
  if ($report['resource_type'] == 'Post') {
    $type = 'post';
  }
  else if ($report['resource_type'] == 'Comment') {
    $type = 'comment';
  }
  else if ($report['resource_type'] == 'User') {
    $type = 'user';
  }
?>

<div class="report-single">
  <div class="title">
    <?php if ($type == 'post'): ?>
        <h1>Report para el post con ID:<?= ' ' . $report['resource_id'] ?></h1>
    <?php elseif ($type == 'comment'): ?>
        <h1>Report para el comentario con ID:<?= ' ' . $report['resource_id'] ?></h1>
    <?php elseif ($type == 'user'): ?>
        <h1>Report para el usuario con ID:<?= ' ' . $report['resource_id'] ?></h1>
    <?php endif; ?>
  </div>

  <div class="report-flex">
    <div>
      <?php if ($type == 'post'): ?>
          <div>Post de:</div>
          <div><a target="_blank" href="/user/<?= $report['owner_id'] ?>"><?= $report['resource_owner'] ?></a></div>
      <?php elseif ($type == 'comment'): ?>
          <div>Comentario de:</div>
          <div><a target="_blank" href="/user/<?= $report['owner_id'] ?>"><?= $report['resource_owner'] ?></a></div>
      <?php elseif ($type == 'user'): ?>
          <div>Cuenta de:</div>
          <div><a target="_blank" href="/user/<?= $report['owner_id'] ?>"><?= $report['resource_owner'] ?></a></div>
      <?php endif; ?>
    </div>

    <div>
      <div>Reportado en:</div>
      <div><?= $report['created_at'] ?></div>
    </div>

    <?php if ($report['comment']): ?>
      <div>
        <div>Razón:</div>
        <div><?= $report['comment'] ?></div>
      </div>
    <?php endif; ?>

    <div>
      <div>Reportado por:</div>
      <div><a target="_blank" href="/user/<?= $report['reporter_id'] ?>"><?= $report['reporter'] ?></a></div>
    </div>

    <div class="link">
      <?php if ($type == 'post'): ?>
        <a target="_blank" href="/post/<?= $report['resource_id'] ?>">Ver <?= 'post' ?></a>
      <?php elseif ($type == 'comment'): ?>
        <a target="_blank" href="/post/<?= $report['resource_id'] ?>">Ver <?= 'comentario' ?></a>
      <?php elseif ($type == 'user'): ?>
        <a target="_blank" href="/user/<?= $report['resource_id'] ?>">Ver <?= 'perfil' ?></a>
      <?php endif; ?>
    </div>

    <?php if ($report['reviewed']): ?>
      <div>
        <div>Revisado por:</div>
        <div><a target="_blank" href="/user/<?= $report['reviewer_id'] ?>"><?= $report['reviewer'] ?></a></div>
      </div>

      <div>
        <?php if (count($report['mod_actions']) > 1): ?>
          <div>Acciones:</div>
          <?php else: ?>
            <div>Acción:</div>
        <?php endif; ?>
        <div>
          <?php foreach ($report['mod_actions'] as $key => $action): ?>
            <?php if (count($report['mod_actions']) > 1): ?>
              <?php if ($key == (count($report['mod_actions']) - 1)): ?>
                <div><?= '- ' . $action['consequence'] ?></div>
              <?php else: ?>
                <div><?= '- ' . $action['consequence'] . ',' ?></div>
              <?php endif; ?>
            <?php else: ?>
              <div><?= $action['consequence'] ?></div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>

      <?php if ($report['motive']): ?>
        <div>
          <div>Racional:</div>
          <div><?= $report['motive'] ?></div>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>

  <div class="review-report btn">Revisar</div>

  <?php if ($report['reviewed']): ?>
    <form method="POST" action="/admin/report/reset/<?= $report['id'] ?>">
      <input type="hidden" name="mod-action-id" value="<?= $report['mod_action_id'] ?>">
      <input class="btn" type="submit" value="Marcar como pendiente">
    </form>
  <?php endif; ?>

  <form class="review" action="/admin/report/review/<?= $report['id'] ?>" method="POST">
    <span class="material-symbols-rounded btn">close</span>

    <div>
      <label class="no-select" for="none">Nada</label>
      <input type="checkbox" id="none" name="none" value="1">
    </div>

    <div>
      <label class="no-select" for="warning">Advertencia</label>
      <input type="checkbox" id="warning" name="warning" value="2">
    </div>

    <div>
      <label class="no-select" for="modified">Recurso modificado</label>
      <input type="checkbox" id="modified" name="modified" value="3">
    </div>

    <div>
      <label class="no-select" for="deleted">Recurso eliminado</label>
      <input type="checkbox" id="deleted" name="deleted" value="4">
    </div>

    <div>
      <label class="no-select" for="restricted">Usuario restringido</label>
      <input type="checkbox" id="restricted" name="restricted" value="5">
    </div>

    <div>
      <label class="no-select" for="banned">Usuario banneado</label>
      <input type="checkbox" id="banned" name="banned" value="6">
    </div>

    <hr>

    <label for="motive">Motivo:</label>
    <input required id="motive" name="motive" type="text" placeholder="...">

    <input type="hidden" name="reviewer-id" value="<?= $_SESSION['user_id'] ?>">
    <input type="hidden" name="report-id" value="<?= $report['id'] ?>">

    <input class="btn" type="submit" value="Subir">
  </form>
</div>