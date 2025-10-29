<?php $lang = $_SESSION['lang'] ?? 'en'; $auth = current_auth(); ?>
<!doctype html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title><?= t('brand'); ?> — <?= htmlspecialchars($title ?? '') ?></title>
<style>
:root {
  --bg:#f6f4f0;
  --surface:#ffffff;
  --ink:#101b2a;
  --ink-soft:#526070;
  --accent:#123b4f;
  --accent-soft:#1e4f69;
  --gold:#c2a25f;
  --line:rgba(16,27,42,0.08);
  --muted:#6b7783;
  --success:#2f8c68;
  --danger:#c2514a;
  --radius:20px;
  --shadow:0 18px 40px -24px rgba(16,27,42,0.45);
}
* { box-sizing:border-box; }
html, body { margin:0; font-family:'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background:var(--bg); color:var(--ink); }
a { color:inherit; text-decoration:none; }
a:hover { text-decoration:underline; }
.header { position:sticky; top:0; z-index:30; background:rgba(246,244,240,0.94); backdrop-filter:blur(12px); border-bottom:1px solid var(--line); }
.header-inner { max-width:1160px; margin:0 auto; display:flex; align-items:center; justify-content:space-between; padding:14px 24px; gap:18px; }
.brand { display:flex; align-items:center; gap:12px; font-weight:700; letter-spacing:0.02em; }
.brand-logo { width:46px; height:46px; border-radius:14px; background:var(--ink); color:var(--gold); display:grid; place-items:center; font-weight:700; font-size:18px; }
.nav { display:flex; align-items:center; gap:18px; flex-wrap:wrap; font-size:0.94rem; }
.nav a { padding:6px 0; font-weight:600; color:var(--ink-soft); }
.nav a:hover { color:var(--ink); text-decoration:none; }
.nav .cta { padding:9px 18px; border-radius:999px; border:1px solid var(--ink); color:var(--ink); transition:background .2s ease; }
.nav .cta:hover { background:var(--ink); color:#fff; }
.lang { font-size:0.82rem; color:var(--ink-soft); display:flex; gap:6px; }
.wrap { max-width:1120px; margin:0 auto; padding:32px 24px 80px; }
.hero { border-radius:32px; background:linear-gradient(135deg, rgba(18,59,79,0.92), rgba(18,59,79,0.78)), url('/images/hero.jpg') center/cover; color:#fff; padding:56px 54px; display:grid; gap:48px; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); align-items:center; box-shadow:var(--shadow); }
.hero.simple { background:var(--ink); box-shadow:none; }
.hero.light { background:var(--surface); color:var(--ink); box-shadow:var(--shadow); }
.hero.light .eyebrow { color:var(--accent); background:rgba(18,59,79,0.1); }
.hero.light h1 { color:var(--ink); }
.eyebrow { display:inline-flex; padding:6px 12px; border-radius:999px; background:rgba(255,255,255,0.18); letter-spacing:0.18em; font-size:0.72rem; text-transform:uppercase; }
.hero h1 { font-size:clamp(32px,4vw,52px); line-height:1.04; margin:16px 0 12px; }
.hero p { margin:0; font-size:1.05rem; line-height:1.6; color:rgba(255,255,255,0.84); }
.hero-list { list-style:none; margin:0; padding:0; display:grid; gap:14px; }
.hero-list li { display:flex; align-items:center; gap:12px; font-size:0.97rem; background:rgba(255,255,255,0.08); padding:12px 16px; border-radius:16px; }
.hero-list li span { display:inline-flex; width:30px; height:30px; border-radius:50%; background:rgba(255,255,255,0.2); align-items:center; justify-content:center; font-weight:600; }
.actions { display:flex; flex-wrap:wrap; gap:14px; margin-top:24px; }
.button { display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:11px 22px; border-radius:999px; border:1px solid transparent; font-weight:600; cursor:pointer; transition:transform .18s ease, box-shadow .18s ease, background .18s ease; }
.button.primary { background:var(--gold); color:var(--ink); }
.button.primary:hover { transform:translateY(-1px); box-shadow:0 16px 24px -18px rgba(16,27,42,0.6); }
.button.ghost { border-color:rgba(255,255,255,0.4); color:#fff; background:transparent; }
.button.ghost:hover { background:rgba(255,255,255,0.12); }
.button.outline { border-color:var(--ink); color:var(--ink); background:transparent; }
.button.outline:hover { background:var(--ink); color:#fff; }
.section { margin-top:72px; display:grid; gap:28px; }
.section-title { font-size:clamp(26px,3vw,36px); margin:0; }
.section-lead { margin:0; color:var(--ink-soft); max-width:620px; }
.grid { display:grid; gap:20px; }
.grid.cols-3 { grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); }
.grid.cols-2 { grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); }
.card { background:var(--surface); border-radius:var(--radius); border:1px solid var(--line); padding:26px; box-shadow:var(--shadow); display:grid; gap:12px; }
.card h3 { margin:0; font-size:1.1rem; }
.card p { margin:0; color:var(--ink-soft); line-height:1.5; }
.card.compact { padding:20px; box-shadow:none; }
.chips { display:flex; flex-wrap:wrap; gap:10px; }
.chip { padding:8px 14px; border-radius:999px; background:rgba(16,27,42,0.08); font-size:0.85rem; color:var(--ink); }
.contact-grid { display:grid; gap:20px; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); }
.contact-card { background:var(--surface); border-radius:var(--radius); border:1px solid var(--line); padding:24px; box-shadow:var(--shadow); display:grid; gap:6px; }
.contact-card span { font-size:0.78rem; text-transform:uppercase; letter-spacing:0.16em; color:var(--ink-soft); }
.contact-card strong { font-size:1.15rem; }
.contact-card.dark { background:var(--ink); color:#fff; border-color:transparent; }
.contact-card.dark span { color:rgba(255,255,255,0.7); }
.stack { display:grid; gap:14px; }
.input, input[type="text"], input[type="email"], input[type="password"], textarea { width:100%; padding:12px 14px; border-radius:14px; border:1px solid var(--line); background:#fff; font-size:0.95rem; }
textarea { min-height:110px; }
.alert { padding:16px 18px; border-radius:16px; border:1px solid transparent; margin-bottom:18px; font-size:0.93rem; }
.alert.success { background:rgba(47,140,104,0.12); border-color:rgba(47,140,104,0.3); color:var(--success); }
.alert.error { background:rgba(194,81,74,0.12); border-color:rgba(194,81,74,0.3); color:var(--danger); }
.gallery-grid { display:grid; gap:18px; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); }
.gallery-card { border-radius:22px; overflow:hidden; background:var(--surface); border:1px solid var(--line); box-shadow:var(--shadow); }
.gallery-card img { width:100%; height:220px; object-fit:cover; display:block; }
.gallery-card .caption { padding:16px 18px; color:var(--ink-soft); font-size:0.95rem; }
.tiers { display:grid; gap:22px; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); }
.tier { background:var(--surface); border-radius:24px; padding:28px; border:1px solid var(--line); box-shadow:var(--shadow); display:grid; gap:16px; }
.tier h3 { margin:0; font-size:1.3rem; }
.tier strong { font-size:1.05rem; color:var(--ink-soft); }
.tier ul { list-style:none; margin:0; padding:0; display:grid; gap:10px; color:var(--ink-soft); }
.footer { padding:32px 24px 48px; text-align:center; color:var(--ink-soft); font-size:0.85rem; }
@media (max-width: 720px) {
  .header-inner { flex-direction:column; align-items:flex-start; }
  .nav { width:100%; justify-content:flex-start; }
  .hero { padding:40px 28px; }
  .actions { flex-direction:column; align-items:flex-start; }
}
</style>
</head>
<body>
<header class="header">
  <div class="header-inner">
    <a href="/" class="brand">
      <div class="brand-logo">CC</div>
      <div>
        <div><?= t('brand'); ?></div>
        <small style="color:var(--muted); font-size:0.78rem; letter-spacing:0.08em; text-transform:uppercase;">Concierge • Property Care • Lifestyle</small>
      </div>
    </a>
    <nav class="nav">
      <a href="/"><?= t('home'); ?></a>
      <a href="/services"><?= t('services'); ?></a>
      <a href="/gallery"><?= t('gallery'); ?></a>
      <a href="/billing"><?= t('billing'); ?></a>
      <a class="cta" href="/client"><?= t('client_area'); ?></a>
      <?php if ($auth): ?>
        <a href="/logout"><?= t('logout'); ?></a>
      <?php endif; ?>
      <span class="lang">
        <a href="/lang/en">EN</a> · <a href="/lang/fr">FR</a>
      </span>
    </nav>
  </div>
</header>
