# Citadelle Concierge Site

Citadelle Concierge is a PHP 8 application that powers the public marketing pages, protected billing tiers, client workspaces, and an embedded admin console for the Citadelle Concierge business.

## What's included

- **Polished landing page** – refined hero with Citadel Blue messaging, quick quote access, and clear navigation into services, billing, and the client portal.
- **Dedicated public pages** – `/services` and `/gallery` provide deep service descriptions and a curated photo showcase backed by the database.
- **Protected billing tiers** – `/billing` is locked behind a shared password so prospects only see pricing after receiving the code.
- **Per-client portals** – `/client` prompts for a password and routes each client to their personalised dashboard (checklists, tasks, invoices, slips, and metrics).
- **Hidden admin console** – entering the admin password on `/client` unlocks `/admin`, exposing client management, uploads, metrics, and contact lead tracking.
- **Contact capture** – the homepage quote form posts to `/contact` and stores submissions in the `leads` table for follow-up.

## Getting started locally

1. Ensure PHP 8.1+ is installed.
2. Install dependencies and seed the SQLite database:
   ```bash
   php setup.php
   ```
3. Start the development server:
   ```bash
   php -S 0.0.0.0:8000 index.php
   ```
4. Browse to [http://localhost:8000](http://localhost:8000).

### Default access codes

`setup.php` seeds starter credentials you can replace in production:

- **Admin:** `Citadel_Admin_2025`
- **Billing (shared):** `Citadel_Billing_2025`
- **Clients:**
  - Monze → `Monze123`
  - Mimosa → `Mimosa123`
  - Jesse → `Jesse123`
  - Fiona → `Fiona123`
  - Fanjeaux → `Fanjeaux123`

### Directory overview

```
view/
├── layout/          # Shared head/footer layout
├── home/            # Homepage layout
├── services/        # Services landing
├── gallery/         # Public gallery
├── billing/         # Billing (locked + tiers)
├── auth/            # Password gate
├── client/          # Client dashboard
├── admin/           # Admin console
└── errors/          # Error templates
```

Uploaded client files are stored in `uploads/<client_id>/` (git-ignored). Public imagery lives in `images/`, including the brand wordmark and gallery placeholders.

## Regenerating seed content

To reset the database after schema changes, delete `data/citadelle.sqlite` and rerun `php setup.php`.

---

Questions or improvement ideas? Open an issue or update the relevant view in its dedicated folder.
