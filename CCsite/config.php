<?php
// Citadelle Concierge configuration bootstrap

if (file_exists(__DIR__ . '/.env.php')) {
    require __DIR__ . '/.env.php';
}

$DB_DSN = getenv('DB_DSN') ?: 'sqlite:' . __DIR__ . '/data/citadelle.sqlite';
$DB_USER = getenv('DB_USER') ?: null;
$DB_PASS = getenv('DB_PASS') ?: null;

try {
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    die('DB connection failed: ' . htmlspecialchars($e->getMessage()));
}

initialize_app_storage($pdo);

session_start();

function t(string $key): string
{
    $lang = $_SESSION['lang'] ?? 'en';
    $dict = [
        'en' => [
            'brand' => 'Citadelle Concierge',
            'home' => 'Home',
            'services' => 'Services',
            'gallery' => 'Gallery',
            'billing' => 'Billing',
            'client_area' => 'Client Area',
            'enter_access_code' => 'Enter access code',
            'enter_password' => 'Enter password',
            'access_portal' => 'Access portal',
            'logout' => 'Log out',
            'admin_dashboard' => 'Admin dashboard',
            'add_client' => 'Add client',
            'client_name' => 'Client name',
            'client_slug' => 'Client URL slug',
            'client_password' => 'Client password',
            'save' => 'Save',
            'cancel' => 'Cancel',
            'tasks' => 'Tasks',
            'checklists' => 'Checklists',
            'status' => 'Status',
            'name' => 'Name',
            'actions' => 'Actions',
            'view' => 'View',
            'date' => 'Date',
            'note' => 'Note',
            'created' => 'Created',
            'summary' => 'Summary',
            'total_hours' => 'Total hours',
            'total_changeovers' => 'Total changeovers',
            'upload' => 'Upload',
            'invoices' => 'Invoices',
            'receipts' => 'Receipts & slips',
            'public_gallery' => 'Project gallery',
            'request_quote' => 'Request a quote',
            'explore_services' => 'Explore services',
            'contact_us' => 'Contact us',
            'billing_locked' => 'Billing is protected',
            'billing_instructions' => 'Enter the shared Citadelle password to view pricing tiers.',
            'invalid_code' => 'Invalid access code',
            'add_task' => 'Add task',
            'task_title' => 'Task title',
            'task_status' => 'Task status',
            'task_note' => 'Task note',
            'add_item' => 'Add item',
            'new_checklist_item' => 'New checklist item',
            'mark_done' => 'Mark done',
            'mark_in_progress' => 'Mark in progress',
            'mark_cancelled' => 'Mark cancelled',
            'status_in_progress' => 'In progress',
            'status_done' => 'Done',
            'status_cancelled' => 'Cancelled',
            'delete' => 'Delete',
            'hours' => 'Hours',
            'changeovers' => 'Changeovers',
            'update' => 'Update',
            'client_gallery' => 'Client gallery',
            'client_invoices' => 'Client invoices',
            'client_receipts' => 'Client slips',
            'admin_metrics' => 'Citadelle at a glance',
            'clients_total' => 'Active clients',
            'hours_total' => 'Total hours',
            'changeovers_total' => 'Total changeovers',
            'billing_tier' => 'Billing tier',
            'cta_contact' => 'WhatsApp, email, or send a brief',
            'language' => 'Language',
        ],
        'fr' => [
            'brand' => 'Citadelle Concierge',
            'home' => 'Accueil',
            'services' => 'Services',
            'gallery' => 'Galerie',
            'billing' => 'Tarifs',
            'client_area' => 'Espace client',
            'enter_access_code' => 'Entrez le code',
            'enter_password' => 'Entrez le mot de passe',
            'access_portal' => 'Accéder',
            'logout' => 'Déconnexion',
            'admin_dashboard' => 'Tableau de bord admin',
            'add_client' => 'Ajouter un client',
            'client_name' => 'Nom du client',
            'client_slug' => 'Identifiant URL',
            'client_password' => 'Mot de passe client',
            'save' => 'Enregistrer',
            'cancel' => 'Annuler',
            'tasks' => 'Tâches',
            'checklists' => 'Listes',
            'status' => 'Statut',
            'name' => 'Nom',
            'actions' => 'Actions',
            'view' => 'Voir',
            'date' => 'Date',
            'note' => 'Note',
            'created' => 'Créé',
            'summary' => 'Résumé',
            'total_hours' => 'Heures totales',
            'total_changeovers' => 'Remises en état',
            'upload' => 'Téléverser',
            'invoices' => 'Factures',
            'receipts' => 'Reçus & justificatifs',
            'public_gallery' => 'Galerie',
            'request_quote' => 'Demander un devis',
            'explore_services' => 'Explorer les services',
            'contact_us' => 'Contactez-nous',
            'billing_locked' => 'Tarifs protégés',
            'billing_instructions' => 'Saisissez le mot de passe Citadelle partagé pour voir les offres.',
            'invalid_code' => 'Code invalide',
            'add_task' => 'Ajouter une tâche',
            'task_title' => 'Titre de la tâche',
            'task_status' => 'Statut',
            'task_note' => 'Note',
            'add_item' => 'Ajouter un item',
            'new_checklist_item' => 'Nouvel élément de liste',
            'mark_done' => 'Marquer terminé',
            'mark_in_progress' => 'Marquer en cours',
            'mark_cancelled' => 'Marquer annulé',
            'status_in_progress' => 'En cours',
            'status_done' => 'Terminé',
            'status_cancelled' => 'Annulé',
            'delete' => 'Supprimer',
            'hours' => 'Heures',
            'changeovers' => 'Remises',
            'update' => 'Mettre à jour',
            'client_gallery' => 'Galerie client',
            'client_invoices' => 'Factures',
            'client_receipts' => 'Justificatifs',
            'admin_metrics' => 'Vue Citadelle',
            'clients_total' => 'Clients actifs',
            'hours_total' => 'Heures totales',
            'changeovers_total' => 'Remises en état',
            'billing_tier' => 'Offre',
            'cta_contact' => 'WhatsApp, email ou message',
            'language' => 'Langue',
        ],
    ];

    return $dict[$lang][$key] ?? $key;
}

function set_lang(string $lang): void
{
    $_SESSION['lang'] = in_array($lang, ['en', 'fr'], true) ? $lang : 'en';
}

function current_auth(): ?array
{
    return $_SESSION['auth'] ?? null;
}

function auth_login(array $credential): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }
    $_SESSION['auth'] = [
        'credential_id' => $credential['id'],
        'role' => $credential['role'],
        'client_id' => $credential['client_id'],
        'label' => $credential['label'],
        'created_at' => $credential['created_at'],
    ];
}

function auth_logout(): void
{
    unset($_SESSION['auth']);
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }
}

function is_admin(): bool
{
    return (current_auth()['role'] ?? null) === 'admin';
}

function is_client(): bool
{
    return (current_auth()['role'] ?? null) === 'client';
}

function is_billing(): bool
{
    return (current_auth()['role'] ?? null) === 'billing';
}

function initialize_app_storage(PDO $pdo): void
{
    ensure_schema($pdo);
    ensure_directories();
    seed_defaults($pdo);
}

function ensure_schema(PDO $pdo): void
{
    $schemaFile = __DIR__ . '/data/schema.sql';
    if (!is_file($schemaFile)) {
        return;
    }

    $sql = file_get_contents($schemaFile);
    if ($sql === false) {
        return;
    }

    $pdo->exec($sql);
}

function ensure_directories(): void
{
    $galleryDir = __DIR__ . '/images/gallery/uploads';
    if (!is_dir($galleryDir)) {
        mkdir($galleryDir, 0775, true);
    }
    $uploadDir = __DIR__ . '/uploads';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }
}

function seed_defaults(PDO $pdo): void
{
    seed_credentials($pdo);
    seed_clients($pdo);
    seed_gallery($pdo);
}

function seed_credentials(PDO $pdo): void
{
    $now = date('c');
    $adminPassword = getenv('ADMIN_PASSWORD') ?: 'Citadel_Admin_2025';
    ensure_credential($pdo, 'Citadelle Admin', 'admin', $adminPassword, null, $now);

    $billingPassword = getenv('BILLING_PASSWORD') ?: 'Citadel_Billing_2025';
    ensure_credential($pdo, 'Citadelle Billing', 'billing', $billingPassword, null, $now);
}

function seed_clients(PDO $pdo): void
{
    $defaultChecklist = [
        'Confirm property ventilation and security',
        'Inspect utilities and reset systems',
        'Prepare welcome amenities',
        'Refresh linens and restock essentials',
    ];

    $clients = [
        ['Monze', 'monze', getenv('CLIENT_MONZE_PASSWORD') ?: 'Monze123'],
        ['Mimosa', 'mimosa', getenv('CLIENT_MIMOSA_PASSWORD') ?: 'Mimosa123'],
        ['Jesse', 'jesse', getenv('CLIENT_JESSE_PASSWORD') ?: 'Jesse123'],
        ['Fiona', 'fiona', getenv('CLIENT_FIONA_PASSWORD') ?: 'Fiona123'],
        ['Fanjeaux', 'fanjeaux', getenv('CLIENT_FANJEAUX_PASSWORD') ?: 'Fanjeaux123'],
    ];

    foreach ($clients as [$name, $slug, $password]) {
        ensure_client_record($pdo, $name, $slug, $password, $defaultChecklist);
    }
}

function seed_gallery(PDO $pdo): void
{
    try {
        $count = (int)$pdo->query('SELECT COUNT(*) FROM gallery_images')->fetchColumn();
    } catch (Throwable $e) {
        $count = 0;
    }

    if ($count > 0) {
        return;
    }

    $images = [
        ['images/gallery/pool-evening.svg', 'Pool service at sunset'],
        ['images/gallery/garden-trim.svg', 'Garden refresh and trimming'],
        ['images/gallery/interior-prep.svg', 'Concierge welcome setup'],
    ];

    $stmt = $pdo->prepare('INSERT INTO gallery_images(path, caption, created_at) VALUES(?,?,?)');
    foreach ($images as [$path, $caption]) {
        $stmt->execute([$path, $caption, date('c')]);
    }
}

function ensure_credential(PDO $pdo, string $label, string $role, string $password, ?int $clientId = null, ?string $createdAt = null): void
{
    $query = 'SELECT id FROM credentials WHERE role = :role AND label = :label';
    if ($clientId === null) {
        $query .= ' AND client_id IS NULL';
    } else {
        $query .= ' AND client_id = :client_id';
    }

    $stmt = $pdo->prepare($query . ' LIMIT 1');
    $params = [
        ':role' => $role,
        ':label' => $label,
    ];
    if ($clientId !== null) {
        $params[':client_id'] = $clientId;
    }
    $stmt->execute($params);
    if ($stmt->fetchColumn()) {
        return;
    }

    $lookup = hash('sha256', $password);
    $passhash = password_hash($password, PASSWORD_BCRYPT);
    $pdo->prepare('INSERT INTO credentials(label, role, lookup, passhash, client_id, created_at) VALUES(?,?,?,?,?,?)')
        ->execute([$label, $role, $lookup, $passhash, $clientId, $createdAt ?? date('c')]);
}

function ensure_client_record(PDO $pdo, string $name, string $slug, string $password, array $checklist): void
{
    $stmt = $pdo->prepare('SELECT id FROM clients WHERE slug = ? LIMIT 1');
    $stmt->execute([$slug]);
    $clientId = $stmt->fetchColumn();

    $now = date('c');
    if (!$clientId) {
        $pdo->prepare('INSERT INTO clients(name, slug, summary_hours, summary_changeovers, notes, created_at, updated_at) VALUES(?,?,?,?,?,?,?)')
            ->execute([$name, $slug, 0, 0, '', $now, $now]);
        $clientId = (int)$pdo->lastInsertId();
    }

    ensure_credential($pdo, $name . ' access', 'client', $password, (int)$clientId, $now);

    $countStmt = $pdo->prepare('SELECT COUNT(*) FROM checklist_items WHERE client_id = ?');
    $countStmt->execute([(int)$clientId]);
    if ((int)$countStmt->fetchColumn() === 0) {
        $position = 1;
        $insert = $pdo->prepare('INSERT INTO checklist_items(client_id, title, done, position, created_at, updated_at) VALUES(?,?,?,?,?,?)');
        foreach ($checklist as $item) {
            $insert->execute([(int)$clientId, $item, 0, $position++, $now, $now]);
        }
    }
}

function current_client_id(): ?int
{
    return is_client() ? (int)current_auth()['client_id'] : null;
}
