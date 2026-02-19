<?php
// TEST CONTROLLER HARGA
// Jalankan di browser: http://localhost/your-project/TEST_CONTROLLER_HARGA.php

// Load CodeIgniter
require_once 'app/Config/Paths.php';
$paths = new Config\Paths();
require_once $paths->systemDirectory . '/bootstrap.php';

$app = Config\Services::codeigniter();
$app->initialize();

echo "<h2>TEST CONTROLLER HARGA</h2>";

// Set dummy session
$session = session();
$session->set([
    'id_user' => 1,
    'role' => 'Admin',
    'nama_user' => 'Test Admin'
]);

echo "<h3>1. Test AdminController Query</h3>";
try {
    $alatModel = new \App\Models\AlatModel();
    $recentAlat = $alatModel->select('alat.id_hp, alat.merk, alat.tipe, alat.harga, alat.kondisi, alat.status, category.nama_category')
        ->join('category', 'category.id_category = alat.id_category', 'left')
        ->orderBy('alat.id_hp', 'DESC')
        ->limit(5)
        ->findAll();
    
    echo "<p><strong>Query berhasil:</strong> ✅ YA</p>";
    echo "<p><strong>Jumlah data:</strong> " . count($recentAlat) . "</p>";
    
    if (count($recentAlat) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Merk</th><th>Tipe</th><th>Harga</th><th>Kategori</th></tr>";
        
        foreach ($recentAlat as $item) {
            $harga = $item['harga'] ?? 'NULL';
            echo "<tr>";
            echo "<td>{$item['id_hp']}</td>";
            echo "<td>{$item['merk']}</td>";
            echo "<td>{$item['tipe']}</td>";
            echo "<td style='color: " . ($harga > 0 ? 'green' : 'red') . "'>{$harga}</td>";
            echo "<td>{$item['nama_category']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Debug array structure
        echo "<h4>Debug Array Structure (First Item):</h4>";
        echo "<pre>";
        print_r($recentAlat[0]);
        echo "</pre>";
    }
    
} catch (Exception $e) {
    echo "❌ Error AdminController: " . $e->getMessage();
}

echo "<h3>2. Test PetugasController Query</h3>";
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
            $harga = $item['harga'] ?? 'NULL';
            echo "<tr>";
            echo "<td>{$item['nama_user']}</td>";
            echo "<td>{$item['merk']}</td>";
            echo "<td>{$item['tipe']}</td>";
            echo "<td style='color: " . ($harga > 0 ? 'green' : 'red') . "'>{$harga}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Debug array structure
        echo "<h4>Debug Array Structure (First Item):</h4>";
        echo "<pre>";
        print_r($peminjamanMenunggu[0]);
        echo "</pre>";
    }
    
} catch (Exception $e) {
    echo "❌ Error PetugasController: " . $e->getMessage();
}

echo "<h3>3. Test PeminjamanModel getRiwayatByUser</h3>";
try {
    $peminjamanModel = new \App\Models\PeminjamanModel();
    $riwayat = $peminjamanModel->getRiwayatByUser(1);
    
    echo "<p><strong>Query berhasil:</strong> ✅ YA</p>";
    echo "<p><strong>Jumlah data:</strong> " . count($riwayat) . "</p>";
    
    if (count($riwayat) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Merk</th><th>Tipe</th><th>Harga</th><th>Status</th></tr>";
        
        foreach (array_slice($riwayat, 0, 5) as $item) {
            $harga = $item['harga'] ?? 'NULL';
            echo "<tr>";
            echo "<td>{$item['merk']}</td>";
            echo "<td>{$item['tipe']}</td>";
            echo "<td style='color: " . ($harga > 0 ? 'green' : 'red') . "'>{$harga}</td>";
            echo "<td>{$item['status']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Debug array structure
        echo "<h4>Debug Array Structure (First Item):</h4>";
        echo "<pre>";
        print_r($riwayat[0]);
        echo "</pre>";
    }
    
} catch (Exception $e) {
    echo "❌ Error PeminjamanModel: " . $e->getMessage();
}

echo "<h3>4. Test Direct Database Query</h3>";
try {
    $db = \Config\Database::connect();
    $query = $db->query("SELECT id_hp, merk, tipe, harga FROM alat LIMIT 3");
    $results = $query->getResult();
    
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Merk</th><th>Tipe</th><th>Harga</th></tr>";
    foreach ($results as $row) {
        echo "<tr>";
        echo "<td>{$row->id_hp}</td>";
        echo "<td>{$row->merk}</td>";
        echo "<td>{$row->tipe}</td>";
        echo "<td style='color: " . ($row->harga > 0 ? 'green' : 'red') . "'>{$row->harga}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "❌ Error Direct Query: " . $e->getMessage();
}

echo "<hr>";
echo "<h3>KESIMPULAN</h3>";
echo "<p>Bandingkan hasil query controller dengan direct database query.</p>";
echo "<p>Jika direct query menunjukkan harga > 0 tapi controller query menunjukkan 0, berarti ada masalah di query controller.</p>";
echo "<p>Jika semua menunjukkan harga > 0, berarti masalah di view atau cache browser.</p>";
?>