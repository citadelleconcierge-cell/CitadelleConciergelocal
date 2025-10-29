<div class="wrap">
  <section class="hero light">
    <div>
      <span class="eyebrow">Services</span>
      <h1>Modular concierge support for discerning owners.</h1>
      <p>Select the pillars you need today—layer extra care as your property evolves.</p>
      <div class="actions">
        <a class="button primary" href="/#contact">Schedule a call</a>
        <a class="button outline" href="/billing">View protected billing</a>
      </div>
    </div>
    <div>
      <ul class="hero-list">
        <li><span>01</span> Tailored changeover programs</li>
        <li><span>02</span> Preventive maintenance oversight</li>
        <li><span>03</span> Outdoor &amp; wellness care</li>
        <li><span>04</span> Guest experience design</li>
      </ul>
    </div>
  </section>

  <section class="section">
    <h2 class="section-title">Core capabilities</h2>
    <div class="grid cols-2">
      <?php foreach ($serviceGroups as $group => $items): ?>
        <div class="card compact">
          <h3><?= htmlspecialchars($group) ?></h3>
          <ul style="margin:0; padding-left:18px; color:var(--ink-soft); display:grid; gap:8px;">
            <?php foreach ($items as $item): ?>
              <li><?= htmlspecialchars($item) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="section">
    <div class="grid cols-3">
      <div class="card compact">
        <h3>Renovation &amp; project oversight</h3>
        <p>We supervise works, share weekly progress visuals, and coordinate trades end-to-end.</p>
      </div>
      <div class="card compact">
        <h3>Guest journey curation</h3>
        <p>Signature welcomes, chefs, chauffeurs, and bespoke itineraries elevate every stay.</p>
      </div>
      <div class="card compact">
        <h3>Owner peace of mind</h3>
        <p>Concise reporting, transparent billing, and a rapid-response protocol protect your asset.</p>
      </div>
    </div>
  </section>
</div>
