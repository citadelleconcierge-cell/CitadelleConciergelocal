<div class="wrap">
  <?php if (!empty($flash)): ?>
    <div class="alert <?= htmlspecialchars($flash['type']) ?>" style="margin-bottom:24px;">
      <?= htmlspecialchars($flash['message']) ?>
    </div>
  <?php endif; ?>

  <section class="hero light">
    <div>
      <span class="eyebrow">Gallery</span>
      <h1>Glances at recent changeovers and guest-ready spaces.</h1>
      <p>A rotating look at villas, chambres d'hôtes, and estates prepared by the Citadelle team.</p>
    </div>
    <div>
      <ul class="hero-list">
        <li><span>01</span> Before &amp; after refreshes</li>
        <li><span>02</span> Seasonal staging moments</li>
        <li><span>03</span> Pool &amp; garden upkeep</li>
      </ul>
    </div>
  </section>

  <section class="section">
    <?php if (!empty($canUpload)): ?>
      <div class="card compact" style="margin-bottom:24px;">
        <h3>Add a new image</h3>
        <p>Upload JPG, PNG, GIF, WebP, or SVG files. Items appear instantly.</p>
        <form method="post" action="/gallery/upload" enctype="multipart/form-data" class="stack">
          <input class="input" type="file" name="image" accept="image/*" required />
          <input class="input" type="text" name="caption" placeholder="Caption (optional)" />
          <button class="button outline" type="submit">Upload image</button>
        </form>
      </div>
    <?php endif; ?>

    <div class="gallery-grid">
      <?php if (!empty($images)): ?>
        <?php foreach ($images as $image): ?>
          <div class="gallery-card">
            <img src="/<?= htmlspecialchars($image['path']) ?>" alt="<?= htmlspecialchars($image['caption']) ?>" />
            <div class="caption"><?= htmlspecialchars($image['caption']) ?></div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="card compact">
          <h3>Gallery coming soon</h3>
          <p>We are curating a new set of highlights from ongoing client work.</p>
        </div>
      <?php endif; ?>
    </div>
  </section>
</div>
