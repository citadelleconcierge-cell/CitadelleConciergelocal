<div class="wrap">
  <?php if (!empty($flash)): ?>
    <div class="alert <?= htmlspecialchars($flash['type']) ?>">
      <?= htmlspecialchars($flash['message']) ?>
    </div>
  <?php endif; ?>

  <section class="hero">
    <div>
      <span class="eyebrow">Occitanie • Property care</span>
      <h1>Property stewardship with boutique-hotel polish.</h1>
      <p>We coordinate changeovers, maintenance, and guest touchpoints so your second home stays impeccable without the back-and-forth.</p>
      <div class="actions">
        <a class="button primary" href="#contact">Book an intro call</a>
        <a class="button ghost" href="/gallery">View recent work</a>
      </div>
    </div>
    <div>
      <ul class="hero-list">
        <li><span>01</span> Precise changeovers &amp; linen rotation</li>
        <li><span>02</span> Pool, spa, and garden oversight</li>
        <li><span>03</span> Trusted artisan coordination</li>
        <li><span>04</span> Discreet guest concierge</li>
      </ul>
    </div>
  </section>

  <section class="section" id="highlights">
    <h2 class="section-title">Why owners choose Citadelle</h2>
    <div class="grid cols-3">
      <div class="card compact">
        <h3>One point of contact</h3>
        <p>Direct access to a bilingual concierge who orchestrates every supplier and schedules updates before you need to ask.</p>
      </div>
      <div class="card compact">
        <h3>Season-ready playbooks</h3>
        <p>Tailored SOPs keep villas prepared for arrivals, maintenance windows, and off-season protection.</p>
      </div>
      <div class="card compact">
        <h3>Coverage across Occitanie</h3>
        <div class="chips">
          <?php foreach ($serviceAreas as $area): ?>
            <span class="chip"><?= htmlspecialchars($area) ?></span>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="section" id="services">
    <h2 class="section-title">Signature services</h2>
    <div class="grid cols-3">
      <?php foreach ($services as $service): ?>
        <div class="card compact">
          <h3><?= htmlspecialchars($service) ?></h3>
          <p>Delivered by a core Citadelle team with trusted specialists on standby.</p>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="section" id="contact">
    <h2 class="section-title">Start the conversation</h2>
    <p class="section-lead">Choose a channel or leave a quick brief—we reply within 24 hours.</p>
    <div class="contact-grid">
      <?php foreach ($contacts as $contact): ?>
        <a class="contact-card dark" href="<?= htmlspecialchars($contact['href']) ?>" target="<?= str_starts_with($contact['href'], 'http') ? '_blank' : '_self' ?>">
          <span><?= htmlspecialchars($contact['label']) ?></span>
          <strong><?= htmlspecialchars($contact['display']) ?></strong>
        </a>
      <?php endforeach; ?>
      <form class="contact-card" method="post" action="/contact">
        <span><?= t('contact_us'); ?></span>
        <div class="stack">
          <input class="input" type="text" name="name" placeholder="Name" required />
          <input class="input" type="email" name="email" placeholder="Email" required />
          <input class="input" type="text" name="phone" placeholder="Phone (optional)" />
          <textarea class="input" name="message" placeholder="Tell us about your property"></textarea>
          <button class="button outline" type="submit">Send request</button>
        </div>
      </form>
    </div>
  </section>

  <section class="section" id="cta">
    <div class="grid cols-2">
      <div class="card compact">
        <h3>Request tiered pricing</h3>
        <p>Unlock the protected billing page with your Citadelle password or contact us for credentials.</p>
        <a class="button primary" href="/billing">Access billing</a>
      </div>
      <div class="card compact">
        <h3>Client dashboard</h3>
        <p>Log in to review changeovers, documents, and concierge updates tailored to your property.</p>
        <a class="button outline" href="/client">Open client area</a>
      </div>
    </div>
  </section>
</div>
