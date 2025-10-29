<?php $isAdmin = is_admin(); ?>
<div class="wrap">
  <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
    <h1 style="margin:0; font-size:clamp(28px,4vw,38px);">
      <?= htmlspecialchars($client['name']) ?>
    </h1>
    <div style="display:flex; gap:12px; flex-wrap:wrap;">
      <a class="button secondary" style="color:var(--ink); border-color:var(--ink);" href="/">Back home</a>
      <?php if ($isAdmin): ?>
        <a class="button secondary" style="color:var(--ink); border-color:var(--ink);" href="/admin">Admin</a>
      <?php endif; ?>
      <a class="button" href="/logout"><?= t('logout'); ?></a>
    </div>
  </div>

  <section class="section" style="margin-top:28px;">
    <div class="cards">
      <div class="card">
        <h3><?= t('summary'); ?></h3>
        <div style="display:flex; gap:24px; flex-wrap:wrap; font-size:1.1rem;">
          <div><strong><?= t('total_hours'); ?>:</strong> <?= number_format((float)$client['summary_hours'], 1); ?></div>
          <div><strong><?= t('total_changeovers'); ?>:</strong> <?= (int)$client['summary_changeovers']; ?></div>
        </div>
        <?php if ($isAdmin): ?>
          <form method="post" action="/client/summary/update" style="margin-top:18px; display:flex; gap:12px; flex-wrap:wrap;">
            <input type="hidden" name="client_id" value="<?= (int)$client['id']; ?>" />
            <input class="input" style="max-width:160px;" type="number" step="0.5" name="summary_hours" value="<?= htmlspecialchars($client['summary_hours']); ?>" placeholder="<?= t('total_hours'); ?>" />
            <input class="input" style="max-width:160px;" type="number" name="summary_changeovers" value="<?= htmlspecialchars($client['summary_changeovers']); ?>" placeholder="<?= t('total_changeovers'); ?>" />
            <button class="button" type="submit"><?= t('update'); ?></button>
          </form>
        <?php endif; ?>
      </div>
      <div class="card bordered">
        <h3>Client quick links</h3>
        <div class="buttons">
          <a class="button primary" href="#tasks">Tasks</a>
          <a class="button primary" href="#checklist">Checklist</a>
          <a class="button primary" href="#files">Files</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section" id="checklist">
    <h2 class="section-title">Checklist</h2>
    <div class="cards">
      <div class="card" style="flex:2;">
        <table class="table">
          <thead>
            <tr>
              <th><?= t('name'); ?></th>
              <th><?= t('status'); ?></th>
              <th><?= t('actions'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($checklist)): ?>
              <tr><td colspan="3" class="muted">No checklist items yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($checklist as $item): ?>
              <tr>
                <td><?= htmlspecialchars($item['title']); ?></td>
                <td>
                  <?php if ($item['done']): ?>
                    <span class="badge success"><?= t('status_done'); ?></span>
                  <?php else: ?>
                    <span class="badge"><?= t('status_in_progress'); ?></span>
                  <?php endif; ?>
                </td>
                <td>
                  <form method="post" action="/client/checklist/toggle" style="display:inline;">
                    <input type="hidden" name="item_id" value="<?= (int)$item['id']; ?>" />
                    <button class="button secondary" type="submit" style="padding:8px 14px;">
                      <?= $item['done'] ? t('mark_in_progress') : t('mark_done'); ?>
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="card" style="flex:1;">
        <h3><?= t('add_item'); ?></h3>
        <form method="post" action="/client/checklist/add" class="stack">
          <input type="hidden" name="client_id" value="<?= (int)$client['id']; ?>" />
          <input class="input" type="text" name="title" placeholder="<?= t('new_checklist_item'); ?>" required />
          <button class="button" type="submit"><?= t('save'); ?></button>
        </form>
      </div>
    </div>
  </section>

  <section class="section" id="tasks">
    <h2 class="section-title">Additional tasks</h2>
    <div class="cards">
      <div class="card" style="flex:2;">
        <table class="table">
          <thead>
            <tr>
              <th><?= t('task_title'); ?></th>
              <th><?= t('task_status'); ?></th>
              <th><?= t('created'); ?></th>
              <th><?= t('actions'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($tasks)): ?>
              <tr><td colspan="4" class="muted">No tasks yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($tasks as $task): ?>
              <?php $statusKey = 'status_' . $task['status']; ?>
              <tr>
                <td>
                  <div style="font-weight:600;"><?= htmlspecialchars($task['title']); ?></div>
                  <?php if (!empty($task['note'])): ?>
                    <div style="color:var(--muted); font-size:0.9rem;"><?= nl2br(htmlspecialchars($task['note'])); ?></div>
                  <?php endif; ?>
                </td>
                <td><span class="badge <?= $task['status'] === 'done' ? 'success' : ($task['status'] === 'cancelled' ? 'danger' : ''); ?>"><?= t($statusKey); ?></span></td>
                <td><?= htmlspecialchars(date('d M Y', strtotime($task['created_at']))); ?></td>
                <td>
                  <form method="post" action="/client/tasks/status" style="display:flex; gap:8px; align-items:center;">
                    <input type="hidden" name="task_id" value="<?= (int)$task['id']; ?>" />
                    <select name="status" class="input" style="max-width:160px;">
                      <option value="in_progress" <?= $task['status'] === 'in_progress' ? 'selected' : ''; ?>><?= t('status_in_progress'); ?></option>
                      <option value="done" <?= $task['status'] === 'done' ? 'selected' : ''; ?>><?= t('status_done'); ?></option>
                      <option value="cancelled" <?= $task['status'] === 'cancelled' ? 'selected' : ''; ?>><?= t('status_cancelled'); ?></option>
                    </select>
                    <button class="button secondary" type="submit" style="padding:8px 14px;"><?= t('update'); ?></button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="card" style="flex:1;">
        <h3><?= t('add_task'); ?></h3>
        <form method="post" action="/client/tasks/create" class="stack">
          <input type="hidden" name="client_id" value="<?= (int)$client['id']; ?>" />
          <input class="input" type="text" name="title" placeholder="<?= t('task_title'); ?>" required />
          <select class="input" name="status">
            <option value="in_progress"><?= t('status_in_progress'); ?></option>
            <option value="done"><?= t('status_done'); ?></option>
            <option value="cancelled"><?= t('status_cancelled'); ?></option>
          </select>
          <textarea class="input" name="note" placeholder="<?= t('task_note'); ?>"></textarea>
          <button class="button" type="submit"><?= t('save'); ?></button>
        </form>
      </div>
    </div>
  </section>

  <section class="section" id="files">
    <h2 class="section-title">Documents & slips</h2>
    <div class="cards">
      <div class="card">
        <h3><?= t('client_invoices'); ?></h3>
        <table class="table">
          <thead><tr><th><?= t('name'); ?></th><th><?= t('date'); ?></th><th><?= t('actions'); ?></th></tr></thead>
          <tbody>
            <?php if (empty($invoices)): ?>
              <tr><td colspan="3" class="muted">No invoices yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($invoices as $invoice): ?>
              <?php $path = '/uploads/' . $client['id'] . '/' . rawurlencode($invoice['filename']); ?>
              <tr>
                <td><?= htmlspecialchars($invoice['label'] ?: $invoice['filename']); ?></td>
                <td><?= htmlspecialchars(date('d M Y', strtotime($invoice['uploaded_at']))); ?></td>
                <td><a class="button secondary" href="<?= $path; ?>" target="_blank"><?= t('view'); ?></a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="card">
        <h3><?= t('client_receipts'); ?></h3>
        <table class="table">
          <thead><tr><th><?= t('name'); ?></th><th><?= t('date'); ?></th><th><?= t('actions'); ?></th></tr></thead>
          <tbody>
            <?php if (empty($receipts)): ?>
              <tr><td colspan="3" class="muted">No receipts yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($receipts as $receipt): ?>
              <?php $path = '/uploads/' . $client['id'] . '/' . rawurlencode($receipt['filename']); ?>
              <tr>
                <td><?= htmlspecialchars($receipt['label'] ?: $receipt['filename']); ?></td>
                <td><?= htmlspecialchars(date('d M Y', strtotime($receipt['uploaded_at']))); ?></td>
                <td><a class="button secondary" href="<?= $path; ?>" target="_blank"><?= t('view'); ?></a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>
