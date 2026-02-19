<?php
// DEBUG HARGA DASHBOARD
// Jalankan file ini di browser untuk debug: http://localhost/your-project/DEBUG_HARGA_DASHBOARD.php

// Load CodeIgniter
require_once 'app/Config/Paths.php';
$paths = new Config\Paths();
require_once $paths->systemDirectory . '/bootstrap.php';

$app = Config\Services::codeigniter();
$app->initialize();

echo "<h2>DEBUG HARGA DASHBOARD</h2>";

// Cek database connection
$db = \Config\Database::connect();

echo "<h3>1. Cek Struktur Tabel Alat</h3>";
try {
    $query = $db->query("DESCRIBE alat");
    $columns = $query->getResult();
    
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    
    $hargaExists = false;
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col->Field}</td>";
        echo "<td>{$col->Type}</td>";
        echo "<td>{$col->Null}</td>";
        echo "<td>{$col->Key}</td>";
        echo "<td>{$col->Default}</td>";
        echo "</tr>";
        
        if ($col->Field == 'harga') {
            $hargaExists = true;
        }
    }
    echo "</table>";
    
    echo "<p><strong>Kolom harga ada:</strong> " . ($hargaExists ? "✅ YA" : "❌ TIDAK") . "</p>";
    
} catch (Exception $e) {
    echo "❌ Error cek struktur: " . $e->getMessage();
}

echo "<h3>2. Cek Data Harga di Tabel Alat</h3>";
try {
    $query = $db->query("SELECT id_hp, merk, tipe, harga, kondisi, status FROM alat LIMIT 10");
    $alat = $query->getResult();
    
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Merk</th><th>Tipe</th><th>Harga</th><th>Kondisi</th><th>Status</th></tr>";
    
    $totalHargaNol = 0;
    foreach ($alat as $item) {
        echo "<tr>";
        echo "<td>{$item->id_hp}</td>";
        echo "<td>{$item->merk}</td>";
        echo "<td>{$item->tipe}</td>";
        echo "<td style='color: " . ($item->harga > 0 ? 'green' : 'red') . "'>{$item->harga}</td>";
        echo "<td>{$item->kondisi}</td>";
        echo "<td>{$item->status}</td>";
        echo "</tr>";
        
        if ($item->harga == 0 || $item->harga == null) {
            $totalHargaNol++;
        }
    }
    echo "</table>";
    
    echo "<p><strong>Data dengan harga 0:</strong> {$totalHargaNol} dari " . count($alat) . "</p>";
    
} catch (Exception $e) {
    echo "❌ Error cek data: " . $e->getMessage();
}

echo "<h3>3. Test Query Dashboard Admin</h3>";
try {
    $alatModel = new \App\Models\AlatModel();
    $recentAlat = $alatModel->select('alat.*, category.nama_category')
        ->join('category', 'category.id_category = alat.id_category', 'left')
        ->orderBy('alat.created_at', 'DESC')
        ->limit(5)
        ->findAll();
    
    echo "<p><strong>Query berhasil:</strong> ✅ YA</p>";
    echo "<p><strong>Jumlah data:</strong> " . count($recentAlat) . "</p>";
    
    if (count($recentAlat) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Merk</th><th>Tipe</th><th>Harga</th><th>Kategori</th></tr>";
        
        foreach ($recentAlat as $item) {
            echo "<tr>";
            echo "<td>{$item['merk']}</td>";
            echo "<td>{$item['tipe']}</td>";
            echo "<td style='color: " . ($item['harga'] > 0 ? 'green' : 'red') . "'>" . ($item['harga'] ?? 'NULL') . "</td>";
            echo "<td>{$item['nama_category']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (Exception $e) {
    echo "❌ Error query dashboard: " . $e->getMessage();
}

echo "<h3>4. Test Query Dashboard Petugas</h3>";
try {
    $peminjamanModel = new \App\Models\PeminjamanModel();
    $peminjamanMenunggu = $peminjamanModel
        ->select('peminjaman.*, alat.merk, alat.tipe, alat.harga, users.nama_user')
        ->join('alat', 'alat.id_hp = peminjaman.id_hp')
        ->join('users', 'users.id_user = peminjaman.id_user')
        ->where('peminjaman.status', 'Diajukan')
        ->limit(5)
        ->findAll();
    
    echo "<p><strong>Query berhasil:</strong> ✅ YA</p>";
    echo "<p><strong>Jumlah data:</strong> " . count($peminjamanMenunggu) . "</p>";
    
    if (count($peminjamanMenunggu) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Peminjam</th><th>Merk</th><th>Tipe</th><th>Harga</th></tr>";
        
        foreach ($peminjamanMenunggu as $item) {
            echo "<tr>";
            echo "<td>{$item['nama_user']}</td>";
            echo "<td>{$item['merk']}</td>";
            echo "<td>{$item['tipe']}</td>";
            echo "<td style='color: " . ($item['harga'] > 0 ? 'green' : 'red') . "'>" . ($item['harga'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (Exception $e) {
    echo "❌ Error query petugas: " . $e->getMessage();
}

echo "<h3>5. Test Query Dashboard Peminjam</h3>";
try {
    // Set dummy session untuk test
    $session = session();
    $session->set('id_user', 1);
    
    $peminjamanModel = new \App\Models\PeminjamanModel();
    $riwayat = $peminjamanModel->getRiwayatByUser(1);
    
    echo "<p><strong>Query berhasil:</strong> ✅ YA</p>";
    echo "<p><strong>Jumlah data:</strong> " . count($riwayat) . "</p>";
    
    if (count($riwayat) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Merk</th><th>Tipe</th><th>Harga</th><th>Status</th></tr>";
        
        foreach (array_slice($riwayat, 0, 5) as $item) {
            echo "<tr>";
            echo "<td>{$item['merk']}</td>";
            echo "<td>{$item['tipe']}</td>";
            echo "<td style='color: " . ($item['harga'] > 0 ? 'green' : 'red') . "'>" . ($item['harga'] ?? 'NULL') . "</td>";
            echo "<td>{$item['status']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (Exception $e) {
    echo "❌ Error query peminjam: " . $e->getMessage();
}

echo "<h3>6. SOLUSI</h3>";
echo "<p>Jika semua harga masih 0, jalankan SQL berikut:</p>";
echo "<pre>";
echo "-- Tambah kolom harga jika belum ada
ALTER TABLE alat ADD COLUMN harga decimal(10,2) DEFAULT 0 AFTER tipe;

-- Update harga untuk semua data
UPDATE alat SET harga = 150000 WHERE harga = 0 OR harga IS NULL;

-- Update harga berdasarkan merk
UPDATE alat SET harga = 180000 WHERE merk LIKE '%iPhone%' OR merk LIKE '%Apple%';
UPDATE alat SET harga = 160000 WHERE merk LIKE '%Samsung%';
UPDATE alat SET harga = 140000 WHERE merk LIKE '%Xiaomi%' OR merk LIKE '%Redmi%';
UPDATE alat SET harga = 120000 WHERE merk LIKE '%Oppo%';
UPDATE alat SET harga = 110000 WHERE merk LIKE '%Vivo%';
";
echo "</pre>";

echo "<hr>";
echo "<p><strong>Selesai debugging. Cek hasil di atas untuk mengetahui masalahnya.</strong></p>";
?>