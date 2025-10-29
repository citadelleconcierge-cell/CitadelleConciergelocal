<?php
require __DIR__ . '/config.php';

$uri = normalise_uri($_SERVER['REQUEST_URI'] ?? '/');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($uri === '/lang/en') { set_lang('en'); header('Location: /'); exit; }
if ($uri === '/lang/fr') { set_lang('fr'); header('Location: /'); exit; }

function render_view(string $view, array $data = []): string
{
    $view = trim($view, '/');
    if ($view === '' || strpos($view, '..') !== false) {
        throw new InvalidArgumentException('Invalid view reference');
    }
    $path = __DIR__ . '/view/' . $view . '.php';
    if (!is_file($path)) {
        throw new RuntimeException('View not found: ' . $view);
    }
    extract($data, EXTR_SKIP);
    ob_start();
    include $path;
    return (string)ob_get_clean();
}

function normalise_uri(string $raw): string
{
    $path = parse_url($raw, PHP_URL_PATH);
    if ($path === false || $path === null) {
        $path = '/';
    }
    $path = rawurldecode($path);
    $path = preg_replace('#/+#', '/', $path) ?: '/';
    $path = $path === '/' ? '/' : rtrim($path, '/');
    if ($path === '') {
        $path = '/';
    }
    return $path;
}

function render_page(string $title, string $view, array $data = []): void
{
    $content = render_view($view, $data);
    $pageTitle = $title;
    $title = $pageTitle;
    include __DIR__ . '/view/layout/head.php';
    echo $content;
    include __DIR__ . '/view/layout/foot.php';
}

function render(string $title, string $content, array $data = []): void
{
    extract($data, EXTR_SKIP);
    $pageTitle = $title;
    $title = $pageTitle;
    include __DIR__ . '/view/layout/head.php';
    echo $content;
    include __DIR__ . '/view/layout/foot.php';
}

function take_flash(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function take_error(): ?string
{
    if (!empty($_SESSION['error'])) {
        $error = $_SESSION['error'];
        unset($_SESSION['error']);
        return $error;
    }
    return null;
}

function credential_by_password(PDO $pdo, string $password): ?array
{
    if ($password === '') {
        return null;
    }
    $lookup = hash('sha256', $password);
    $stmt = $pdo->prepare('SELECT * FROM credentials WHERE lookup = ?');
    $stmt->execute([$lookup]);
    $credential = $stmt->fetch();
    if ($credential && password_verify($password, $credential['passhash'])) {
        return $credential;
    }
    return null;
}

function fetch_client_by_id(PDO $pdo, int $clientId): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM clients WHERE id = ?');
    $stmt->execute([$clientId]);
    return $stmt->fetch() ?: null;
}

function fetch_client_by_slug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM clients WHERE slug = ?');
    $stmt->execute([$slug]);
    return $stmt->fetch() ?: null;
}

function ensure_client_or_admin_access(PDO $pdo, array $client): void
{
    if (is_admin()) {
        return;
    }
    if (!is_client()) {
        header('Location: /client');
        exit;
    }
    if ((int)current_client_id() !== (int)$client['id']) {
        header('Location: /client');
        exit;
    }
}

if ($uri === '/logout') {
    auth_logout();
    header('Location: /');
    exit;
}

if ($uri === '/login') {
    header('Location: /client');
    exit;
}

if ($uri === '/contact' && $method === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name && $email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $pdo->prepare('INSERT INTO leads(name, email, phone, message, created_at) VALUES(?,?,?,?,?)')
            ->execute([$name, $email, $phone, $message, date('c')]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Thank you! We will respond within 24 hours.'];
    } else {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Please provide a valid name and email address.'];
    }
    header('Location: /#quote-form');
    exit;
}

if ($uri === '/' || $uri === '/index.php') {
    $serviceAreas = [
        'Carcassonne citadel & bastide neighbourhoods',
        'Villages across the Aude & Corbières',
        'Canal du Midi retreats',
        'Occitanie wine country estates',
    ];
    $contacts = [
        ['label' => 'WhatsApp', 'display' => '+33 7 68 47 32 11', 'href' => 'https://wa.me/33768473211'],
        ['label' => 'Email', 'display' => 'hello@citadelleconcierge.fr', 'href' => 'mailto:hello@citadelleconcierge.fr'],
        ['label' => 'Brief form', 'display' => 'Share your project', 'href' => '#quote-form'],
    ];
    $services = [
        'Changeovers & linen orchestration',
        'Pool and spa equilibrium',
        'Garden & exterior care',
        'Trusted maintenance coordination',
        'Guest concierge & stay curation',
        'Owner reporting & asset protection',
    ];

    render_page('Home', 'home/index', [
        'flash' => take_flash(),
        'serviceAreas' => $serviceAreas,
        'contacts' => $contacts,
        'services' => $services,
    ]);
    exit;
}

if ($uri === '/services') {
    $serviceGroups = [
        'Changeovers & Housekeeping' => [
            'Arrival / departure staging with photographic proof',
            'Five-star linen service with damage logging',
            'Deep cleans, laundry & restocking playbooks',
            'Seasonal openings and closings',
        ],
        'Operations & Maintenance' => [
            'Preventive inspections with actionable reporting',
            'Reliable artisan sourcing & supervision',
            'Smart home, HVAC, and security monitoring liaison',
            'Inventory management for amenities & supplies',
        ],
        'Outdoor & Wellness' => [
            'Pool chemistry balancing & equipment care',
            'Jacuzzi, sauna, and spa preparation',
            'Landscape coordination & exterior lighting checks',
            'Storm readiness and rapid response clean-ups',
        ],
        'Guest & Lifestyle Concierge' => [
            'VIP welcome rituals and gift sourcing',
            '24/7 bilingual guest messaging',
            'Chef, driver, and experience curation',
            'Business concierge for retreat organisers',
        ],
    ];

    render_page('Services', 'services/index', [
        'serviceGroups' => $serviceGroups,
    ]);
    exit;
}

if ($uri === '/gallery/upload' && $method === 'POST') {
    if (!is_admin() && !is_billing()) {
        http_response_code(403);
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'You must be logged in as staff to upload images.'];
        header('Location: /gallery');
        exit;
    }

    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Upload failed. Please choose an image file.'];
        header('Location: /gallery');
        exit;
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
    $originalName = $_FILES['image']['name'] ?? 'upload';
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions, true)) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Unsupported file type.'];
        header('Location: /gallery');
        exit;
    }

    $caption = trim($_POST['caption'] ?? '');
    $caption = $caption !== '' ? $caption : 'Uploaded image';

    $safeName = preg_replace('/[^A-Za-z0-9_.-]/', '_', basename($originalName, '.' . $extension));
    $filename = time() . '-' . $safeName . '.' . $extension;
    $destinationDir = __DIR__ . '/images/gallery/uploads';
    if (!is_dir($destinationDir)) {
        mkdir($destinationDir, 0775, true);
    }
    $destination = $destinationDir . '/' . $filename;
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Unable to move uploaded file.'];
        header('Location: /gallery');
        exit;
    }

    $relativePath = 'images/gallery/uploads/' . $filename;
    $stmt = $pdo->prepare('INSERT INTO gallery_images(path, caption, created_at) VALUES(?,?,?)');
    $stmt->execute([$relativePath, $caption, date('c')]);

    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Image added to the gallery.'];
    header('Location: /gallery');
    exit;
}

if ($uri === '/gallery') {
    $images = [];
    try {
        $stmt = $pdo->query('SELECT path, caption FROM gallery_images ORDER BY created_at DESC');
        if ($stmt) {
            $images = $stmt->fetchAll();
        }
    } catch (Throwable $e) {
        $images = [];
    }

    if (empty($images)) {
        $images = [
            ['path' => 'images/gallery/pool-evening.svg', 'caption' => 'Twilight pool reset ready for late arrivals.'],
            ['path' => 'images/gallery/interior-prep.svg', 'caption' => 'Boutique rental staged with custom amenities.'],
            ['path' => 'images/gallery/garden-trim.svg', 'caption' => 'Garden refresh after Mistral winds.'],
        ];
    }

    render_page('Gallery', 'gallery/index', [
        'images' => $images,
        'flash' => take_flash(),
        'canUpload' => is_admin() || is_billing(),
    ]);
    exit;
}

if ($uri === '/billing') {
    if (!is_admin() && !is_billing()) {
        render_page('Billing', 'billing/locked', ['error' => take_error()]);
        exit;
    }

    $tiers = [
        [
            'name' => 'Essential Care',
            'price' => 'Tailored per property',
            'features' => [
                'Changeover execution and linen management',
                'Monthly property inspection report',
                'Emergency response within 24h',
            ],
        ],
        [
            'name' => 'Concierge Signature',
            'price' => 'From €420 / month',
            'features' => [
                'All Essential Care services',
                'Guest concierge & booking management',
                'Weekly pool & garden oversight',
            ],
        ],
        [
            'name' => 'Citadelle Bespoke',
            'price' => 'Quote on request',
            'features' => [
                'Dedicated lifestyle manager',
                'Project supervision & artisan sourcing',
                'Full business concierge support',
            ],
        ],
    ];

    render_page('Billing', 'billing/index', ['tiers' => $tiers]);
    exit;
}

if ($uri === '/billing/access' && $method === 'POST') {
    $password = trim($_POST['password'] ?? '');
    $credential = credential_by_password($pdo, $password);
    if ($credential && $credential['role'] === 'billing') {
        auth_login($credential);
        header('Location: /billing');
        exit;
    }
    $_SESSION['error'] = t('invalid_code');
    header('Location: /billing');
    exit;
}

if ($uri === '/client' && $method === 'GET') {
    render_page('Client access', 'auth/password-gate', [
        'headline' => t('client_area'),
        'description' => 'Enter your dedicated Citadelle password to reach your personalised dashboard.',
        'action' => '/client/access',
        'error' => take_error(),
    ]);
    exit;
}

if ($uri === '/client/access' && $method === 'POST') {
    $password = trim($_POST['password'] ?? '');
    $credential = credential_by_password($pdo, $password);
    if ($credential) {
        auth_login($credential);
        if ($credential['role'] === 'admin') {
            header('Location: /admin');
            exit;
        }
        if ($credential['role'] === 'client' && $credential['client_id']) {
            $client = fetch_client_by_id($pdo, (int)$credential['client_id']);
            if ($client) {
                header('Location: /clients/' . urlencode($client['slug']));
                exit;
            }
        }
        if ($credential['role'] === 'billing') {
            header('Location: /billing');
            exit;
        }
    }
    $_SESSION['error'] = t('invalid_code');
    header('Location: /client');
    exit;
}

if (preg_match('#^/clients/([a-z0-9\-]+)$#', $uri, $matches)) {
    $slug = $matches[1];
    $client = fetch_client_by_slug($pdo, $slug);
    if (!$client) {
        http_response_code(404);
        render_page('Not found', 'errors/404');
        exit;
    }
    ensure_client_or_admin_access($pdo, $client);

    $checklistStmt = $pdo->prepare('SELECT * FROM checklist_items WHERE client_id = ? ORDER BY position');
    $checklistStmt->execute([$client['id']]);
    $checklist = $checklistStmt->fetchAll();

    $tasksStmt = $pdo->prepare('SELECT * FROM tasks WHERE client_id = ? ORDER BY created_at DESC');
    $tasksStmt->execute([$client['id']]);
    $tasks = $tasksStmt->fetchAll();

    $invoicesStmt = $pdo->prepare('SELECT * FROM invoices WHERE client_id = ? ORDER BY uploaded_at DESC');
    $invoicesStmt->execute([$client['id']]);
    $invoices = $invoicesStmt->fetchAll();

    $receiptsStmt = $pdo->prepare('SELECT * FROM receipts WHERE client_id = ? ORDER BY uploaded_at DESC');
    $receiptsStmt->execute([$client['id']]);
    $receipts = $receiptsStmt->fetchAll();

    render_page($client['name'], 'client/index', [
        'client' => $client,
        'checklist' => $checklist,
        'tasks' => $tasks,
        'invoices' => $invoices,
        'receipts' => $receipts,
    ]);
    exit;
}

if ($uri === '/client/checklist/add' && $method === 'POST') {
    $clientId = (int)($_POST['client_id'] ?? 0);
    $client = fetch_client_by_id($pdo, $clientId);
    if (!$client) {
        header('Location: /client');
        exit;
    }
    ensure_client_or_admin_access($pdo, $client);
    $title = trim($_POST['title'] ?? '');
    if ($title !== '') {
        $position = (int)$pdo->query('SELECT COALESCE(MAX(position),0)+1 FROM checklist_items WHERE client_id=' . (int)$clientId)->fetchColumn();
        $now = date('c');
        $stmt = $pdo->prepare('INSERT INTO checklist_items(client_id, title, done, position, created_at, updated_at) VALUES(?,?,?,?,?,?)');
        $stmt->execute([$clientId, $title, 0, $position, $now, $now]);
    }
    header('Location: /clients/' . urlencode($client['slug']));
    exit;
}

if ($uri === '/client/checklist/toggle' && $method === 'POST') {
    $itemId = (int)($_POST['item_id'] ?? 0);
    $stmt = $pdo->prepare('SELECT * FROM checklist_items WHERE id = ?');
    $stmt->execute([$itemId]);
    $item = $stmt->fetch();
    if ($item) {
        $client = fetch_client_by_id($pdo, (int)$item['client_id']);
        if ($client) {
            ensure_client_or_admin_access($pdo, $client);
            $done = (int)$item['done'] ? 0 : 1;
            $pdo->prepare('UPDATE checklist_items SET done = ?, updated_at = ? WHERE id = ?')->execute([$done, date('c'), $itemId]);
            header('Location: /clients/' . urlencode($client['slug']));
            exit;
        }
    }
    header('Location: /client');
    exit;
}

if ($uri === '/client/tasks/create' && $method === 'POST') {
    $clientId = (int)($_POST['client_id'] ?? 0);
    $client = fetch_client_by_id($pdo, $clientId);
    if (!$client) {
        header('Location: /client');
        exit;
    }
    ensure_client_or_admin_access($pdo, $client);
    $title = trim($_POST['title'] ?? '');
    $note = trim($_POST['note'] ?? '');
    $status = $_POST['status'] ?? 'in_progress';
    $allowedStatuses = ['in_progress', 'done', 'cancelled'];
    if (!in_array($status, $allowedStatuses, true)) {
        $status = 'in_progress';
    }
    if ($title !== '') {
        $now = date('c');
        $pdo->prepare('INSERT INTO tasks(client_id, title, status, note, created_by_role, created_at, updated_at) VALUES(?,?,?,?,?,?,?)')
            ->execute([$clientId, $title, $status, $note, is_admin() ? 'admin' : 'client', $now, $now]);
    }
    header('Location: /clients/' . urlencode($client['slug']));
    exit;
}

if ($uri === '/client/tasks/status' && $method === 'POST') {
    $taskId = (int)($_POST['task_id'] ?? 0);
    $status = $_POST['status'] ?? 'in_progress';
    $allowedStatuses = ['in_progress', 'done', 'cancelled'];
    if (!in_array($status, $allowedStatuses, true)) {
        $status = 'in_progress';
    }
    $stmt = $pdo->prepare('SELECT * FROM tasks WHERE id = ?');
    $stmt->execute([$taskId]);
    $task = $stmt->fetch();
    if ($task) {
        $client = fetch_client_by_id($pdo, (int)$task['client_id']);
        if ($client) {
            ensure_client_or_admin_access($pdo, $client);
            $pdo->prepare('UPDATE tasks SET status = ?, updated_at = ? WHERE id = ?')
                ->execute([$status, date('c'), $taskId]);
            header('Location: /clients/' . urlencode($client['slug']));
            exit;
        }
    }
    header('Location: /client');
    exit;
}

if ($uri === '/client/summary/update' && $method === 'POST') {
    if (!is_admin()) {
        header('Location: /client');
        exit;
    }
    $clientId = (int)($_POST['client_id'] ?? 0);
    $hours = (float)($_POST['summary_hours'] ?? 0);
    $changeovers = (int)($_POST['summary_changeovers'] ?? 0);
    $pdo->prepare('UPDATE clients SET summary_hours = ?, summary_changeovers = ?, updated_at = ? WHERE id = ?')
        ->execute([$hours, $changeovers, date('c'), $clientId]);
    $client = fetch_client_by_id($pdo, $clientId);
    if ($client) {
        header('Location: /clients/' . urlencode($client['slug']));
        exit;
    }
    header('Location: /admin');
    exit;
}

if ($uri === '/admin') {
    if (!is_admin()) {
        header('Location: /client');
        exit;
    }
    $clients = $pdo->query('SELECT * FROM clients ORDER BY name')->fetchAll();
    $metrics = [
        'clients' => (int)$pdo->query('SELECT COUNT(*) FROM clients')->fetchColumn(),
        'hours' => (float)$pdo->query('SELECT COALESCE(SUM(summary_hours),0) FROM clients')->fetchColumn(),
        'changeovers' => (int)$pdo->query('SELECT COALESCE(SUM(summary_changeovers),0) FROM clients')->fetchColumn(),
    ];
    $leads = $pdo->query('SELECT * FROM leads ORDER BY created_at DESC LIMIT 20')->fetchAll();
    render_page('Admin', 'admin/index', [
        'clients' => $clients,
        'metrics' => $metrics,
        'leads' => $leads,
        'flash' => take_flash(),
        'error' => take_error(),
    ]);
    exit;
}

if ($uri === '/admin/client/create' && $method === 'POST' && is_admin()) {
    $name = trim($_POST['name'] ?? '');
    $slug = strtolower(trim($_POST['slug'] ?? ''));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = trim(preg_replace('/-+/', '-', $slug), '-');
    $password = trim($_POST['password'] ?? '');
    if ($name && $slug && $password) {
        $existsStmt = $pdo->prepare('SELECT COUNT(*) FROM clients WHERE slug = ?');
        $existsStmt->execute([$slug]);
        if ($existsStmt->fetchColumn()) {
            $_SESSION['error'] = 'Client slug already exists.';
        } else {
            $now = date('c');
            $pdo->prepare('INSERT INTO clients(name, slug, summary_hours, summary_changeovers, notes, created_at, updated_at) VALUES(?,?,?,?,?,?,?)')
                ->execute([$name, $slug, 0, 0, '', $now, $now]);
            $clientId = (int)$pdo->lastInsertId();
            $pair = [
                'lookup' => hash('sha256', $password),
                'hash' => password_hash($password, PASSWORD_BCRYPT),
            ];
            $pdo->prepare('INSERT INTO credentials(label, role, lookup, passhash, client_id, created_at) VALUES(?,?,?,?,?,?)')
                ->execute([$name . ' access', 'client', $pair['lookup'], $pair['hash'], $clientId, $now]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Client created.'];
        }
    } else {
        $_SESSION['error'] = 'All client fields are required.';
    }
    header('Location: /admin');
    exit;
}

if ($uri === '/admin/client/password' && $method === 'POST' && is_admin()) {
    $clientId = (int)($_POST['client_id'] ?? 0);
    $password = trim($_POST['password'] ?? '');
    if ($clientId && $password) {
        $lookup = hash('sha256', $password);
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $pdo->prepare('UPDATE credentials SET lookup = ?, passhash = ? WHERE client_id = ? AND role = "client"')
            ->execute([$lookup, $hash, $clientId]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Client password updated.'];
    } else {
        $_SESSION['error'] = 'Client and password required.';
    }
    header('Location: /admin');
    exit;
}

if ($uri === '/admin/client/delete' && $method === 'POST' && is_admin()) {
    $clientId = (int)($_POST['client_id'] ?? 0);
    if ($clientId) {
        $pdo->prepare('DELETE FROM credentials WHERE client_id = ?')->execute([$clientId]);
        $pdo->prepare('DELETE FROM checklist_items WHERE client_id = ?')->execute([$clientId]);
        $pdo->prepare('DELETE FROM tasks WHERE client_id = ?')->execute([$clientId]);
        $pdo->prepare('DELETE FROM invoices WHERE client_id = ?')->execute([$clientId]);
        $pdo->prepare('DELETE FROM receipts WHERE client_id = ?')->execute([$clientId]);
        $pdo->prepare('DELETE FROM clients WHERE id = ?')->execute([$clientId]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Client removed.'];
    } else {
        $_SESSION['error'] = 'Client missing.';
    }
    header('Location: /admin');
    exit;
}

if ($uri === '/admin/upload' && $method === 'POST' && is_admin()) {
    $clientId = (int)($_POST['client_id'] ?? 0);
    $type = $_POST['type'] ?? 'invoice';
    $label = trim($_POST['label'] ?? '');
    $client = fetch_client_by_id($pdo, $clientId);
    if (!$client) {
        $_SESSION['error'] = 'Client not found.';
        header('Location: /admin');
        exit;
    }
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $dir = __DIR__ . '/uploads/' . $clientId;
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $name = time() . '-' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $_FILES['file']['name']);
        $dest = $dir . '/' . $name;
        move_uploaded_file($_FILES['file']['tmp_name'], $dest);
        $now = date('c');
        if ($type === 'invoice') {
            $pdo->prepare('INSERT INTO invoices(client_id, filename, label, uploaded_at) VALUES(?,?,?,?)')
                ->execute([$clientId, $name, $label, $now]);
        } else {
            $pdo->prepare('INSERT INTO receipts(client_id, filename, label, uploaded_at) VALUES(?,?,?,?)')
                ->execute([$clientId, $name, $label, $now]);
        }
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'File uploaded.'];
    } else {
        $_SESSION['error'] = 'Upload failed.';
    }
    header('Location: /admin');
    exit;
}

http_response_code(404);
render_page('Not found', 'errors/404');
