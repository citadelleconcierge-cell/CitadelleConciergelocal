<?php
require __DIR__ . '/config.php';

$pdo->exec(file_get_contents(__DIR__ . '/data/schema.sql'));

function hash_lookup_pair(string $password): array {
    return [
        'lookup' => hash('sha256', $password),
        'hash' => password_hash($password, PASSWORD_BCRYPT),
    ];
}

function ensure_credential(PDO $pdo, string $label, string $role, string $password, ?int $clientId = null): void {
    $pair = hash_lookup_pair($password);
    $pdo->prepare("INSERT OR IGNORE INTO credentials(label, role, lookup, passhash, client_id, created_at) VALUES(?,?,?,?,?,?)")
        ->execute([$label, $role, $pair['lookup'], $pair['hash'], $clientId, date('c')]);
}

function ensure_client(PDO $pdo, string $name, string $slug, string $password, array $checklist = []): int {
    $now = date('c');
    $pdo->prepare("INSERT OR IGNORE INTO clients(name, slug, summary_hours, summary_changeovers, notes, created_at, updated_at) VALUES(?,?,?,?,?,?,?)")
        ->execute([$name, $slug, 0, 0, '', $now, $now]);
    $clientId = (int)$pdo->query("SELECT id FROM clients WHERE slug=" . $pdo->quote($slug))->fetchColumn();
    ensure_credential($pdo, $name . ' access', 'client', $password, $clientId);

    if ($clientId) {
        $existing = $pdo->query("SELECT COUNT(*) FROM checklist_items WHERE client_id=" . (int)$clientId)->fetchColumn();
        if ((int)$existing === 0) {
            $position = 1;
            foreach ($checklist as $item) {
                $pdo->prepare("INSERT INTO checklist_items(client_id, title, done, position, created_at, updated_at) VALUES(?,?,?,?,?,?)")
                    ->execute([$clientId, $item, 0, $position++, $now, $now]);
            }
        }
    }

    return $clientId;
}

// Seed admin + billing credentials
ensure_credential($pdo, 'Citadelle Admin', 'admin', 'Citadel_Admin_2025');
ensure_credential($pdo, 'Citadelle Billing', 'billing', 'Citadel_Billing_2025');

// Default checklist items shared across clients
$defaultChecklist = [
    'Confirm property ventilation and security',
    'Inspect utilities and reset systems',
    'Prepare welcome amenities',
    'Refresh linens and restock essentials',
];

$clients = [
    ['Monze', 'monze', 'Monze123', $defaultChecklist],
    ['Mimosa', 'mimosa', 'Mimosa123', $defaultChecklist],
    ['Jesse', 'jesse', 'Jesse123', $defaultChecklist],
    ['Fiona', 'fiona', 'Fiona123', $defaultChecklist],
    ['Fanjeaux', 'fanjeaux', 'Fanjeaux123', $defaultChecklist],
];

foreach ($clients as [$name, $slug, $password, $list]) {
    ensure_client($pdo, $name, $slug, $password, $list);
}

// Seed gallery placeholders
if ((int)$pdo->query('SELECT COUNT(*) FROM gallery_images')->fetchColumn() === 0) {
    $images = [
        ['images/gallery/pool-evening.svg', 'Pool service at sunset'],
        ['images/gallery/garden-trim.svg', 'Garden refresh and trimming'],
        ['images/gallery/interior-prep.svg', 'Concierge welcome setup'],
    ];
    foreach ($images as [$path, $caption]) {
        $pdo->prepare('INSERT INTO gallery_images(path, caption, created_at) VALUES(?,?,?)')
            ->execute([$path, $caption, date('c')]);
    }
}

echo "Setup complete.\n";
echo "Admin access password: Citadel_Admin_2025\n";
echo "Billing shared password: Citadel_Billing_2025\n";
foreach ($clients as [$name, $slug, $password]) {
    echo sprintf("Client %s password: %s\n", $name, $password);
}
