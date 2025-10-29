<div class="wrap">
  <h1><?= t('admin_dashboard'); ?></h1>
  <?php if (!empty($flash)): ?>
    <div class="alert <?= htmlspecialchars($flash['type']); ?>" style="margin-top:16px;">
      <?= htmlspecialchars($flash['message']); ?>
    </div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert error" style="margin-top:16px;">
      <?= htmlspecialchars($error); ?>
    </div>
  <?php endif; ?>
  <section class="section" style="margin-top:24px;">
    <div class="cards">
      <div class="card">
        <h3><?= t('clients_total'); ?></h3>
        <p style="font-size:2rem; margin:0; font-weight:700;"><?= (int)$metrics['clients']; ?></p>
      </div>
      <div class="card">
        <h3><?= t('hours_total'); ?></h3>
        <p style="font-size:2rem; margin:0; font-weight:700;"><?= number_format((float)$metrics['hours'], 1); ?></p>
      </div>
      <div class="card">
        <h3><?= t('changeovers_total'); ?></h3>
        <p style="font-size:2rem; margin:0; font-weight:700;"><?= (int)$metrics['changeovers']; ?></p>
      </div>
    </div>
  </section>

  <section class="section">
    <h2 class="section-title">Add a client</h2>
    <form method="post" action="/admin/client/create" class="cards" style="gap:16px;">
      <div class="card" style="flex:1;">
        <div class="stack">
          <input class="input" type="text" name="name" placeholder="<?= t('client_name'); ?>" required />
          <input class="input" type="text" name="slug" placeholder="<?= t('client_slug'); ?>" required />
          <input class="input" type="text" name="password" placeholder="<?= t('client_password'); ?>" required />
          <button class="button" type="submit"><?= t('add_client'); ?></button>
        </div>
      </div>
    </form>
  </section>

  <section class="section">
    <h2 class="section-title">Manage clients</h2>
    <div class="card" style="overflow-x:auto;">
      <table class="table">
        <thead>
          <tr>
            <th><?= t('client_name'); ?></th>
            <th><?= t('client_slug'); ?></th>
            <th><?= t('total_hours'); ?></th>
            <th><?= t('total_changeovers'); ?></th>
            <th><?= t('actions'); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($clients as $client): ?>
            <tr>
              <td><?= htmlspecialchars($client['name']); ?></td>
              <td><?= htmlspecialchars($client['slug']); ?></td>
              <td><?= number_format((float)$client['summary_hours'], 1); ?></td>
              <td><?= (int)$client['summary_changeovers']; ?></td>
              <td style="display:flex; gap:8px; flex-wrap:wrap;">
                <a class="button secondary" href="/clients/<?= urlencode($client['slug']); ?>">View</a>
                <form method="post" action="/admin/client/password" style="display:flex; gap:6px;">
                  <input type="hidden" name="client_id" value="<?= (int)$client['id']; ?>" />
                  <input class="input" type="text" name="password" placeholder="<?= t('client_password'); ?>" required />
                  <button class="button secondary" type="submit"><?= t('update'); ?></button>
                </form>
                <form method="post" action="/admin/client/delete" onsubmit="return confirm('Delete client? This removes all associated records.');">
                  <input type="hidden" name="client_id" value="<?= (int)$client['id']; ?>" />
                  <button class="button secondary" type="submit" style="background:#fff0f0; border-color:#ffcccc; color:#b81f1f;"><?= t('delete'); ?></button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>

  <section class="section">
    <h2 class="section-title">Upload invoices & slips</h2>
    <div class="card">
      <form method="post" action="/admin/upload" enctype="multipart/form-data" class="stack">
        <div class="form-row" style="gap:16px;">
          <select class="input" name="client_id" required style="max-width:220px;">
            <option value="">Select client</option>
            <?php foreach ($clients as $client): ?>
              <option value="<?= (int)$client['id']; ?>"><?= htmlspecialchars($client['name']); ?></option>
            <?php endforeach; ?>
          </select>
          <select class="input" name="type" style="max-width:180px;">
            <option value="invoice"><?= t('invoices'); ?></option>
            <option value="receipt"><?= t('receipts'); ?></option>
          </select>
          <input class="input" type="text" name="label" placeholder="File label (optional)" />
          <input class="input" type="file" name="file" required />
        </div>
        <button class="button" type="submit"><?= t('upload'); ?></button>
      </form>
    </div>
  </section>

  <section class="section">
    <h2 class="section-title">Recent quote requests</h2>
    <div class="card" style="overflow-x:auto;">
      <table class="table">
        <thead>
          <tr>
            <th><?= t('name'); ?></th>
            <th>Email</th>
            <th>Phone</th>
            <th><?= t('created'); ?></th>
            <th><?= t('note'); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($leads)): ?>
            <tr><td colspan="5" class="muted">No leads yet.</td></tr>
          <?php endif; ?>
          <?php foreach ($leads as $lead): ?>
            <tr>
              <td><?= htmlspecialchars($lead['name']); ?></td>
              <td><a href="mailto:<?= htmlspecialchars($lead['email']); ?>"><?= htmlspecialchars($lead['email']); ?></a></td>
              <td><?= htmlspecialchars($lead['phone']); ?></td>
              <td><?= htmlspecialchars(date('d M Y H:i', strtotime($lead['created_at']))); ?></td>
              <td><?= nl2br(htmlspecialchars($lead['message'])); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
</div>
