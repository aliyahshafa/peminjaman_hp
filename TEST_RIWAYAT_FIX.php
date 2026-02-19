<?php
// TEST: Verify getRiwayatByUser now returns harga
require_once 'vendor/autoload.php';

$app = \Config\Services::codeigniter();
$app->initialize();

echo "<h2>TEST RIWAYAT FIX</h2>";

$peminjamanModel = new \App\Models\PeminjamanModel();

// Test with different user IDs to find one with data
$userIds = [1, 2, 3, 4, 5];

foreach ($userIds as $userId) {
    echo "<h3>Testing User ID: $userId</h3>";
    
    $riwayat = $peminjamanModel->getRiwayatByUser($userId);
    
    if (!empty($riwayat)) {
        echo "Found " . count($riwayat) . " records<br>";
        
        $firstRecord = $riwayat[0];
        echo "<table border='1'>";
        echo "<tr><th>Field</th><th>Value</th></tr>";
        foreach ($firstRecord as $key => $value) {
            echo "<tr><td>$key</td><td>" . ($value ?? 'NULL') . "</td></tr>";
        }
        echo "</table>";
        
        echo "<strong>Harga value: " . ($firstRecord['harga'] ?? 'NOT FOUND') . "</strong><br>";
        break; // Stop after finding first user with data
    } else {
        echo "No records found<br>";
    }
}

// Also test direct query to compare
echo "<h3>Direct Query Comparison:</h3>";
$db = \Config\Database::connect();
$query = $db->query("
    SELECT p.*, a.merk, a.tipe, a.harga 
    FROM peminjaman p 
    LEFT JOIN alat a ON a.id_hp = p.id_hp 
    WHERE p.status IN ('Diajukan', 'Disetujui', 'Menunggu Pengembalian', 'Dikembalikan')
    LIMIT 1
");
$directData = $query->getResultArray();
if (!empty($directData)) {
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Value</th></tr>";
    foreach ($directData[0] as $key => $value) {
        echo "<tr><td>$key</td><td>" . ($value ?? 'NULL') . "</td></tr>";
    }
    echo "</table>";
}
?>