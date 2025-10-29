<div class="wrap">
  <div class="card" style="max-width:520px; margin:80px auto; padding:40px 36px;">
    <h1><?= htmlspecialchars($headline ?? t('enter_access_code')) ?></h1>
    <?php if (!empty($description)): ?>
      <p style="color:var(--muted);"><?= htmlspecialchars($description) ?></p>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="alert error" style="margin-top:16px;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" action="<?= htmlspecialchars($action ?? '/client/access') ?>" style="margin-top:24px; display:grid; gap:16px;">
      <input class="input" type="password" name="password" placeholder="<?= t('enter_password'); ?>" required />
      <button class="button" type="submit"><?= t('access_portal'); ?></button>
    </form>
    <p style="margin-top:18px; color:var(--muted); font-size:0.9rem;">Admins can enter their dedicated password here to access the full control centre.</p>
  </div>
</div>
