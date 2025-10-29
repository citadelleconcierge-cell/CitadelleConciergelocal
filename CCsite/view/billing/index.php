<div class="wrap">
  <section class="hero light">
    <div>
      <span class="eyebrow">Confidential billing</span>
      <h1>Three concierge tiers tailored to property ambition.</h1>
      <p>Select the level of involvement that fits your portfolio. Each tier includes transparent reporting and a dedicated point of contact.</p>
    </div>
    <div>
      <ul class="hero-list">
        <li><span>01</span> Essential care programmes</li>
        <li><span>02</span> Priority subscription bundles</li>
        <li><span>03</span> Full management partnerships</li>
      </ul>
    </div>
  </section>

  <section class="section">
    <div class="tiers">
      <?php foreach ($tiers as $tier): ?>
        <div class="tier">
          <h3><?= htmlspecialchars($tier['name']) ?></h3>
          <strong><?= htmlspecialchars($tier['price']) ?></strong>
          <ul>
            <?php foreach ($tier['features'] as $feature): ?>
              <li><?= htmlspecialchars($feature) ?></li>
            <?php endforeach; ?>
          </ul>
          <a class="button primary" href="/#contact">Discuss this tier</a>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</div>
