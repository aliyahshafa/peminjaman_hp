<?php
// TEST: Verify forced methods work
require_once 'vendor/autoload.php';

$app = \Config\Services::codeigniter();
$app->initialize();

echo "<h1>TEST FORCED METHODS</h1>";

// Test 1: Raw database (baseline)
echo "<h2>1. RAW DATABASE (Baseline)</h2>";
$db = \Config\Database::connect();
$query = $db->query('SELECT id_hp, merk, tipe, harga FROM alat LIMIT 3');
$rawData = $query->getResultArray();
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Merk</th><th>Tipe</th><th>Harga</th></tr>";
foreach ($rawData as $row) {
    echo "<tr><td>{$row['id_hp']}</td><td>{$row['merk']}</td><td>{$row['tipe']}</td><td><strong>{$row['harga']}</strong></td></tr>";
}
echo "</table>";

// Test 2: AlatModel forced method
echo "<h2>2. ALAT MODEL FORCED METHOD</h2>";
$alatModel = new \App\Models\AlatModel();
$forcedData = $alatModel->getAlatWithHargaForced();
$forcedData = array_slice($forcedData, 0, 3); // Limit to 3

echo "<table border='1'>";
echo "<tr><th>ID</th><th>Merk</th><th>Tipe</th><th>Harga</th></tr>";
foreach ($forcedData as $row) {
    echo "<tr><td>{$row['id_hp']}</td><td>{$row['merk']}</td><td>{$row['tipe']}</td><td><strong>{$row['harga']}</strong></td></tr>";
}
echo "</table>";

// Test 3: PeminjamanModel getRiwayatByUser
echo "<h2>3. PEMINJAMAN MODEL RIWAYAT</h2>";
$peminjamanModel = new \App\Models\PeminjamanModel();

// Find a user with data
$userQuery = $db->query("SELECT DISTINCT id_user FROM peminjaman LIMIT 3");
$users = $userQuery->getResultArray();

foreach ($users as $user) {
    $userId = $user['id_user'];
    $riwayat = $peminjamanModel->getRiwayatByUser($userId);
    
    if (!empty($riwayat)) {
        echo "<h3>User ID: $userId</h3>";
        echo "<table border='1'>";
        echo "<tr><th>ID Peminjaman</th><th>Merk</th><th>Tipe</th><th>Harga</th></tr>";
        
        $first = $riwayat[0];
        echo "<tr><td>{$first['id_peminjaman']}</td><td>{$first['merk']}</td><td>{$first['tipe']}</td><td><strong>{$first['harga']}</strong></td></tr>";
        echo "</table>";
        break;
    }
}

// Test 4: AdminController simulation
echo "<h2>4. ADMIN CONTROLLER SIMULATION</h2>";
$adminData = $alatModel->getAlatWithHargaForced();
$adminData = array_slice($adminData, 0, 3);

echo "<table border='1'>";
echo "<tr><th>ID</th><th>Merk</th><th>Tipe</th><th>Harga Display</th></tr>";
foreach ($adminData as $alat) {
    $displayHarga = number_format($alat['harga'] ?? 0, 0, ',', '.');
    echo "<tr><td>{$alat['id_hp']}</td><td>{$alat['merk']}</td><td>{$alat['tipe']}</td><td><strong>Rp $displayHarga</strong></td></tr>";
}
echo "</table>";

echo "<h2>RESULT</h2>";
echo "<p>If all tables above show proper harga values (not 0), then the forced methods are working!</p>";
?>