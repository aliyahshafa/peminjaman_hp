<?php
// DEBUG: Check what data is in $riwayat
require_once 'vendor/autoload.php';

$app = \Config\Services::codeigniter();
$app->initialize();

echo "<h2>DEBUG RIWAYAT HARGA</h2>";

// Simulate what DashboardPeminjam does
$peminjamanModel = new \App\Models\PeminjamanModel();

// Test getRiwayatByUser method
echo "<h3>1. Test getRiwayatByUser method:</h3>";
$riwayat = $peminjamanModel->getRiwayatByUser(1); // Assuming user ID 1 exists
echo "<pre>";
print_r(array_slice($riwayat, 0, 2)); // Show first 2 records
echo "</pre>";

// Check if harga field exists
echo "<h3>2. Check if harga field exists in first record:</h3>";
if (!empty($riwayat)) {
    $firstRecord = $riwayat[0];
    echo "Keys in first record: " . implode(', ', array_keys($firstRecord)) . "<br>";
    echo "Harga value: " . ($firstRecord['harga'] ?? 'NOT FOUND') . "<br>";
    echo "Harga isset: " . (isset($firstRecord['harga']) ? 'YES' : 'NO') . "<br>";
}

// Test direct query
echo "<h3>3. Direct query test:</h3>";
$db = \Config\Database::connect();
$query = $db->query("
    SELECT p.id_peminjaman, p.nama_user, p.status, a.merk, a.tipe, a.harga 
    FROM peminjaman p 
    JOIN alat a ON a.id_hp = p.id_hp 
    WHERE p.id_user = 1 
    LIMIT 2
");
$directData = $query->getResultArray();
echo "<pre>";
print_r($directData);
echo "</pre>";
?>