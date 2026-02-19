<?php
// FINAL TEST: All controllers and views
require_once 'vendor/autoload.php';

$app = \Config\Services::codeigniter();
$app->initialize();

echo "<h1>FINAL ALL CONTROLLERS TEST</h1>";

// Test 1: Database baseline
echo "<h2>1. DATABASE BASELINE</h2>";
$db = \Config\Database::connect();
$query = $db->query('SELECT id_hp, merk, tipe, harga FROM alat LIMIT 2');
$baseline = $query->getResultArray();
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Merk</th><th>Tipe</th><th>Harga (DB)</th></tr>";
foreach ($baseline as $row) {
    echo "<tr><td>{$row['id_hp']}</td><td>{$row['merk']}</td><td>{$row['tipe']}</td><td><strong>{$row['harga']}</strong></td></tr>";
}
echo "</table>";

// Test 2: AlatController simulation (Admin)
echo "<h2>2. ALAT CONTROLLER (ADMIN)</h2>";
$alatModel = new \App\Models\AlatModel();
$categoryModel = new \App\Models\CategoryModel();

$builder = $alatModel->db->table('alat')
    ->select('alat.*, category.nama_category')
    ->join('category', 'category.id_category = alat.id_category', 'left')
    ->limit(2);

$alatControllerData = $builder->get()->getResultArray();
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Merk</th><th>Tipe</th><th>Harga (AlatController)</th></tr>";
foreach ($alatControllerData as $row) {
    echo "<tr><td>{$row['id_hp']}</td><td>{$row['merk']}</td><td>{$row['tipe']}</td><td><strong>{$row['harga']}</strong></td></tr>";
}
echo "</table>";

// Test 3: AdminController simulation (Dashboard)
echo "<h2>3. ADMIN CONTROLLER (DASHBOARD)</h2>";
$adminDashboardData = $alatModel->getAlatWithHargaForced();
$adminDashboardData = array_slice($adminDashboardData, 0, 2);
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Merk</th><th>Tipe</th><th>Harga (AdminDashboard)</th></tr>";
foreach ($adminDashboardData as $row) {
    echo "<tr><td>{$row['id_hp']}</td><td>{$row['merk']}</td><td>{$row['tipe']}</td><td><strong>{$row['harga']}</strong></td></tr>";
}
echo "</table>";

// Test 4: PeminjamanModel simulation (Peminjam Dashboard)
echo "<h2>4. PEMINJAMAN MODEL (PEMINJAM DASHBOARD)</h2>";
$peminjamanModel = new \App\Models\PeminjamanModel();

// Find user with data
$userQuery = $db->query("SELECT DISTINCT id_user FROM peminjaman LIMIT 1");
$users = $userQuery->getResultArray();

if (!empty($users)) {
    $userId = $users[0]['id_user'];
    $riwayatData = $peminjamanModel->getRiwayatByUser($userId);
    
    if (!empty($riwayatData)) {
        echo "<table border='1'>";
        echo "<tr><th>ID Peminjaman</th><th>Merk</th><th>Tipe</th><th>Harga (Riwayat)</th></tr>";
        $first = $riwayatData[0];
        echo "<tr><td>{$first['id_peminjaman']}</td><td>{$first['merk']}</td><td>{$first['tipe']}</td><td><strong>{$first['harga']}</strong></td></tr>";
        echo "</table>";
    } else {
        echo "<p>No riwayat data found for user $userId</p>";
    }
} else {
    echo "<p>No users found in peminjaman table</p>";
}

// Test 5: View display simulation
echo "<h2>5. VIEW DISPLAY SIMULATION</h2>";
if (!empty($alatControllerData)) {
    $item = $alatControllerData[0];
    $displayHarga = number_format($item['harga'] ?? 0, 0, ',', '.');
    echo "<p>View will display: <strong>Rp $displayHarga</strong></p>";
    echo "<p>Raw harga value: <strong>{$item['harga']}</strong></p>";
}

echo "<h2>FINAL RESULT</h2>";
echo "<p><strong>If all tables above show proper harga values (not 0), then ALL fixes are working!</strong></p>";
echo "<p>Check your web interface now - all dashboards should show correct harga values.</p>";
?>