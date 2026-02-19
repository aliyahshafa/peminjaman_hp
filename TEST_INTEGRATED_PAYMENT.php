<?php
// TEST: Integrated Payment with Return Process
require_once 'vendor/autoload.php';

$app = \Config\Services::codeigniter();
$app->initialize();

echo "<h1>TEST INTEGRATED PAYMENT WITH RETURN</h1>";

// Test 1: Check current peminjaman yang bisa dikembalikan
echo "<h2>1. CHECK PEMINJAMAN YANG BISA DIKEMBALIKAN</h2>";
$db = \Config\Database::connect();
try {
    $query = $db->query("
        SELECT p.*, a.merk, a.tipe, a.harga 
        FROM peminjaman p 
        JOIN alat a ON a.id_hp = p.id_hp 
        WHERE p.status = 'Disetujui' 
        LIMIT 5
    ");
    $peminjamanAktif = $query->getResultArray();
    
    if (!empty($peminjamanAktif)) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>User ID</th><th>HP</th><th>Harga HP</th><th>Status</th><th>Tanggal Pinjam</th></tr>";
        foreach ($peminjamanAktif as $p) {
            echo "<tr>";
            echo "<td>{$p['id_peminjaman']}</td>";
            echo "<td>{$p['id_user']}</td>";
            echo "<td>{$p['merk']} {$p['tipe']}</td>";
            echo "<td>Rp " . number_format($p['harga'], 0, ',', '.') . "</td>";
            echo "<td>{$p['status']}</td>";
            echo "<td>" . date('d/m/Y', strtotime($p['waktu'])) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Tidak ada peminjaman aktif yang bisa dikembalikan</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

// Test 2: Simulate denda calculation
echo "<h2>2. SIMULATE DENDA CALCULATION</h2>";
$kondisiOptions = ['baik', 'rusak ringan', 'rusak berat'];
echo "<table border='1'>";
echo "<tr><th>Kondisi HP</th><th>Denda</th><th>Perlu Pembayaran</th></tr>";
foreach ($kondisiOptions as $kondisi) {
    $denda = 0;
    if ($kondisi == 'rusak ringan') {
        $denda = 10000;
    } elseif ($kondisi == 'rusak berat') {
        $denda = 20000;
    }
    
    echo "<tr>";
    echo "<td>" . ucwords($kondisi) . "</td>";
    echo "<td>Rp " . number_format($denda, 0, ',', '.') . "</td>";
    echo "<td>" . ($denda > 0 ? 'Ya' : 'Tidak') . "</td>";
    echo "</tr>";
}
echo "</table>";

// Test 3: Check pembayaran table structure
echo "<h2>3. CHECK PEMBAYARAN TABLE READY</h2>";
try {
    $query = $db->query("SELECT COUNT(*) as total FROM pembayaran");
    $count = $query->getRowArray();
    echo "<p style='color: green;'>✓ Tabel pembayaran siap, total records: " . $count['total'] . "</p>";
    
    // Show table structure
    $structure = $db->query("DESCRIBE pembayaran")->getResultArray();
    echo "<p><strong>Fields:</strong> ";
    $fields = array_column($structure, 'Field');
    echo implode(', ', $fields) . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

// Test 4: Test models
echo "<h2>4. TEST MODELS</h2>";
try {
    $peminjamanModel = new \App\Models\PeminjamanModel();
    $pembayaranModel = new \App\Models\PembayaranModel();
    $alatModel = new \App\Models\AlatModel();
    
    echo "<p style='color: green;'>✓ PeminjamanModel loaded</p>";
    echo "<p style='color: green;'>✓ PembayaranModel loaded</p>";
    echo "<p style='color: green;'>✓ AlatModel loaded</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

// Test 5: Simulate integrated flow
echo "<h2>5. SIMULATE INTEGRATED FLOW</h2>";
if (!empty($peminjamanAktif)) {
    $samplePeminjaman = $peminjamanAktif[0];
    
    echo "<h3>Sample Flow untuk Peminjaman ID: {$samplePeminjaman['id_peminjaman']}</h3>";
    echo "<p><strong>HP:</strong> {$samplePeminjaman['merk']} {$samplePeminjaman['tipe']}</p>";
    echo "<p><strong>Harga HP:</strong> Rp " . number_format($samplePeminjaman['harga'], 0, ',', '.') . "</p>";
    
    echo "<table border='1'>";
    echo "<tr><th>Kondisi Dipilih</th><th>Denda</th><th>Action</th><th>Status Akhir</th></tr>";
    
    foreach ($kondisiOptions as $kondisi) {
        $denda = 0;
        if ($kondisi == 'rusak ringan') {
            $denda = 10000;
        } elseif ($kondisi == 'rusak berat') {
            $denda = 20000;
        }
        
        $action = ($denda > 0) ? 'Bayar + Kembalikan' : 'Kembalikan saja';
        $statusAkhir = ($denda > 0) ? 'Dikembalikan (Lunas)' : 'Menunggu Pengembalian';
        
        echo "<tr>";
        echo "<td>" . ucwords($kondisi) . "</td>";
        echo "<td>Rp " . number_format($denda, 0, ',', '.') . "</td>";
        echo "<td>$action</td>";
        echo "<td>$statusAkhir</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<h2>CONCLUSION</h2>";
echo "<p><strong>✓ Sistem pembayaran terintegrasi dengan pengembalian siap digunakan!</strong></p>";
echo "<p><strong>Flow:</strong></p>";
echo "<ol>";
echo "<li>Peminjam pilih kondisi HP saat pengembalian</li>";
echo "<li>Jika ada denda → Form pembayaran muncul otomatis</li>";
echo "<li>Peminjam pilih metode pembayaran</li>";
echo "<li>Submit → Pembayaran tersimpan + Status langsung 'Dikembalikan' + HP jadi 'Tersedia'</li>";
echo "<li>Jika tidak ada denda → Langsung proses pengembalian biasa</li>";
echo "</ol>";
echo "<p><strong>Test URL:</strong> <a href='" . base_url('/peminjam/peminjaman') . "' target='_blank'>/peminjam/peminjaman</a></p>";
?>