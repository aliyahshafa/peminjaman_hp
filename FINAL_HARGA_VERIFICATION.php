<?php
// FINAL VERIFICATION: Test all harga fixes
require_once 'vendor/autoload.php';

$app = \Config\Services::codeigniter();
$app->initialize();

echo "<h1>FINAL HARGA VERIFICATION</h1>";

// Test 1: Direct database
echo "<h2>1. Database Has Harga:</h2>";
$db = \Config\Database::connect();
$query = $db->query("SELECT id_hp, merk, tipe, harga FROM alat WHERE harga > 0 LIMIT 3");
$dbData = $query->getResultArray();
foreach ($dbData as $row) {
    echo "ID {$row['id_hp']}: {$row['merk']} - Rp " . number_format($row['harga'], 0, ',', '.') . "<br>";
}

// Test 2: AlatModel after fixes
echo "<h2>2. AlatModel Returns Harga:</h2>";
$alatModel = new \App\Models\AlatModel();
$alatData = $alatModel->limit(3)->findAll();
foreach ($alatData as $row) {
    echo "ID {$row['id_hp']}: {$row['merk']} - Harga: " . ($row['harga'] ?? 'NOT FOUND') . "<br>";
}

// Test 3: PeminjamanModel getRiwayatByUser
echo "<h2>3. PeminjamanModel getRiwayatByUser:</h2>";
$peminjamanModel = new \App\Models\PeminjamanModel();

// Find a user with peminjaman data
$userQuery = $db->query("SELECT DISTINCT id_user FROM peminjaman LIMIT 3");
$users = $userQuery->getResultArray();

foreach ($users as $user) {
    $userId = $user['id_user'];
    $riwayat = $peminjamanModel->getRiwayatByUser($userId);
    
    if (!empty($riwayat)) {
        echo "User ID $userId has " . count($riwayat) . " records<br>";
        $first = $riwayat[0];
        echo "First record harga: " . ($first['harga'] ?? 'NOT FOUND') . "<br>";
        echo "First record keys: " . implode(', ', array_keys($first)) . "<br>";
        break;
    }
}

// Test 4: Simulate dashboard data
echo "<h2>4. Dashboard Simulation:</h2>";
if (!empty($riwayat)) {
    $r = $riwayat[0];
    $displayHarga = number_format($r['harga'] ?? 0, 0, ',', '.');
    echo "Dashboard will show: Rp $displayHarga<br>";
    echo "Raw harga value: " . ($r['harga'] ?? 'NULL') . "<br>";
}

echo "<h2>CONCLUSION:</h2>";
echo "<p>If all tests above show proper harga values, the fix is working!</p>";
?>