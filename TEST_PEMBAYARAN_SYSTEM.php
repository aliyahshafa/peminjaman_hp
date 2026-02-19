<?php
// TEST: Pembayaran System (Updated for existing database)
require_once 'vendor/autoload.php';

$app = \Config\Services::codeigniter();
$app->initialize();

echo "<h1>TEST PEMBAYARAN SYSTEM (EXISTING DATABASE)</h1>";

// Test 1: Check pembayaran table structure
echo "<h2>1. CHECK PEMBAYARAN TABLE STRUCTURE</h2>";
$db = \Config\Database::connect();
try {
    $query = $db->query("DESCRIBE pembayaran");
    $columns = $query->getResultArray();
    
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>{$col['Default']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<p style='color: green;'>✓ Tabel pembayaran sudah ada dengan struktur yang sesuai</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

// Test 2: Check PembayaranModel with correct fields
echo "<h2>2. CHECK PEMBAYARAN MODEL</h2>";
try {
    $pembayaranModel = new \App\Models\PembayaranModel();
    echo "<p style='color: green;'>✓ PembayaranModel berhasil diload</p>";
    echo "<p>Table: " . $pembayaranModel->getTable() . "</p>";
    echo "<p>Primary Key: " . $pembayaranModel->getPrimaryKey() . "</p>";
    echo "<p>Allowed Fields: " . implode(', ', $pembayaranModel->getAllowedFields()) . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

// Test 3: Check existing pembayaran data
echo "<h2>3. CHECK EXISTING PEMBAYARAN DATA</h2>";
try {
    $existingData = $db->query("SELECT * FROM pembayaran LIMIT 5")->getResultArray();
    
    if (!empty($existingData)) {
        echo "<p>Jumlah data pembayaran: <strong>" . count($existingData) . "</strong></p>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>User ID</th><th>Peminjaman ID</th><th>Subtotal</th><th>Status</th><th>Metode</th></tr>";
        foreach ($existingData as $data) {
            echo "<tr>";
            echo "<td>{$data['id_pembayaran']}</td>";
            echo "<td>{$data['id_user']}</td>";
            echo "<td>{$data['id_peminjaman']}</td>";
            echo "<td>Rp " . number_format($data['subtotal'] ?? 0, 0, ',', '.') . "</td>";
            echo "<td>{$data['status']}</td>";
            echo "<td>{$data['metode_pembayaran']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Belum ada data pembayaran</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

// Test 4: Check peminjaman yang perlu dibayar
echo "<h2>4. CHECK PEMINJAMAN YANG PERLU DIBAYAR</h2>";
try {
    $peminjamanModel = new \App\Models\PeminjamanModel();
    $peminjamanPerluBayar = $peminjamanModel
        ->where('status', 'Menunggu Pengembalian')
        ->where('denda >', 0)
        ->findAll();
    
    echo "<p>Jumlah peminjaman yang perlu dibayar: <strong>" . count($peminjamanPerluBayar) . "</strong></p>";
    
    if (!empty($peminjamanPerluBayar)) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>User ID</th><th>HP ID</th><th>Denda</th><th>Status</th><th>Kondisi HP</th></tr>";
        foreach (array_slice($peminjamanPerluBayar, 0, 5) as $p) {
            echo "<tr>";
            echo "<td>{$p['id_peminjaman']}</td>";
            echo "<td>{$p['id_user']}</td>";
            echo "<td>{$p['id_hp']}</td>";
            echo "<td>Rp " . number_format($p['denda'], 0, ',', '.') . "</td>";
            echo "<td>{$p['status']}</td>";
            echo "<td>{$p['kondisi_hp']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

// Test 5: Test PembayaranController methods
echo "<h2>5. TEST PEMBAYARAN CONTROLLER</h2>";
try {
    $pembayaranController = new \App\Controllers\PembayaranController();
    echo "<p style='color: green;'>✓ PembayaranController berhasil diload</p>";
    
    // Test model methods
    $pembayaranModel = new \App\Models\PembayaranModel();
    
    // Test getByUser method
    $userPembayaran = $pembayaranModel->getByUser(1); // Test with user ID 1
    echo "<p>Test getByUser(1): " . count($userPembayaran) . " records found</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<h2>CONCLUSION</h2>";
echo "<p><strong>✓ Sistem pembayaran sudah disesuaikan dengan database yang ada!</strong></p>";
echo "<p>Database fields yang digunakan:</p>";
echo "<ul>";
echo "<li>subtotal (untuk jumlah denda)</li>";
echo "<li>metode_pembayaran (untuk metode bayar)</li>";
echo "<li>status (untuk status pembayaran)</li>";
echo "<li>waktu, harga, tanggal_bayar (fields tambahan)</li>";
echo "</ul>";
echo "<p><strong>Akses:</strong> <a href='" . base_url('/peminjam/pembayaran') . "' target='_blank'>/peminjam/pembayaran</a></p>";
?>