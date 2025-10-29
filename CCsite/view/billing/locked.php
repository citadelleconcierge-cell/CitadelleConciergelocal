<div class="wrap">
  <div class="card compact" style="max-width:520px; margin:80px auto; text-align:center; gap:18px;">
    <h1 style="margin:0; font-size:1.8rem;"><?= t('billing_locked'); ?></h1>
    <p class="section-lead" style="margin:0;"><?= t('billing_instructions'); ?></p>
    <?php if (!empty($error)): ?>
      <div class="alert error" style="margin:0;">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>
    <form method="post" action="/billing/access" class="stack" style="margin-top:8px;">
      <input class="input" type="password" name="password" placeholder="<?= t('enter_password'); ?>" required />
      <button class="button primary" type="submit">Unlock billing</button>
    </form>
  </div>
</div>
