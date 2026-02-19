<?php
// TEST DELETE DAN BACKUP SYSTEM
// Jalankan file ini di browser untuk test: http://localhost/your-project/TEST_DELETE_BACKUP.php

// Load CodeIgniter
require_once 'app/Config/Paths.php';
$paths = new Config\Paths();
require_once $paths->systemDirectory . '/bootstrap.php';

$app = Config\Services::codeigniter();
$app->initialize();

echo "<h2>TEST DELETE DAN BACKUP SYSTEM</h2>";

// Set session untuk testing (simulasi login admin)
$session = session();
$session->set([
    'id_user' => 1,
    'role' => 'Admin',
    'nama_user' => 'Test Admin'
]);

echo "<h3>1. Session Info</h3>";
echo "User ID: " . $session->get('id_user') . "<br>";
echo "Role: " . $session->get('role') . "<br>";

// Cek tabel trash
$db = \Config\Database::connect();
$tableExists = $db->tableExists('trash');

echo "<h3>2. Tabel Trash</h3>";
echo "Tabel trash ada: " . ($tableExists ? "✅ YA" : "❌ TIDAK") . "<br>";

if (!$tableExists) {
    echo "<strong>❌ MASALAH: Tabel trash tidak ada!</strong><br>";
    echo "Jalankan SQL untuk membuat tabel trash terlebih dahulu.<br>";
    exit;
}

// Test TrashModel
echo "<h3>3. Test TrashModel</h3>";
try {
    $trashModel = new \App\Models\TrashModel();
    echo "TrashModel berhasil dimuat ✅<br>";
    
    // Test data dummy
    $testData = [
        'id_hp' => 999,
        'merk' => 'Test Merk',
        'tipe' => 'Test Tipe',
        'kondisi' => 'Baik',
        'status' => 'Tersedia',
        'harga' => 1000000
    ];
    
    echo "<h4>Data Test:</h4>";
    echo "<pre>" . print_r($testData, true) . "</pre>";
    
    // Test backup
    echo "<h4>Test Backup ke Trash:</h4>";
    $backupResult = $trashModel->backupData('alat', 999, $testData, 'Test backup');
    
    if ($backupResult) {
        echo "✅ Backup berhasil! Insert ID: " . $backupResult . "<br>";
        
        // Cek data di trash
        $trashData = $trashModel->where('record_id', 999)->where('table_name', 'alat')->first();
        if ($trashData) {
            echo "✅ Data ditemukan di trash:<br>";
            echo "<pre>" . print_r($trashData, true) . "</pre>";
            
            // Decode JSON
            $decodedData = json_decode($trashData['data_backup'], true);
            echo "✅ Data decoded:<br>";
            echo "<pre>" . print_r($decodedData, true) . "</pre>";
            
            // Hapus test data
            $trashModel->delete($trashData['id_trash']);
            echo "✅ Test data dihapus dari trash<br>";
        } else {
            echo "❌ Data tidak ditemukan di trash setelah backup<br>";
        }
    } else {
        echo "❌ Backup gagal!<br>";
        
        // Cek error
        $errors = $trashModel->errors();
        if ($errors) {
            echo "Errors: <pre>" . print_r($errors, true) . "</pre>";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "<br>";
}

// Test AlatModel
echo "<h3>4. Test AlatModel</h3>";
try {
    $alatModel = new \App\Models\AlatModel();
    echo "AlatModel berhasil dimuat ✅<br>";
    
    // Cek data alat yang ada
    $alatCount = $alatModel->countAllResults();
    echo "Jumlah data alat: " . $alatCount . "<br>";
    
    if ($alatCount > 0) {
        $sampleAlat = $alatModel->first();
        echo "Sample data alat:<br>";
        echo "<pre>" . print_r($sampleAlat, true) . "</pre>";
    }
    
} catch (Exception $e) {
    echo "❌ Error AlatModel: " . $e->getMessage() . "<br>";
}

// Cek data trash yang ada
echo "<h3>5. Data Trash Saat Ini</h3>";
try {
    $currentTrash = $db->query("SELECT * FROM trash ORDER BY deleted_at DESC")->getResult();
    echo "Jumlah data di trash: " . count($currentTrash) . "<br>";
    
    if (count($currentTrash) > 0) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Table</th><th>Record ID</th><th>Deleted By</th><th>Deleted At</th><th>Reason</th></tr>";
        foreach ($currentTrash as $item) {
            echo "<tr>";
            echo "<td>{$item->id_trash}</td>";
            echo "<td>{$item->table_name}</td>";
            echo "<td>{$item->record_id}</td>";
            echo "<td>{$item->deleted_by}</td>";
            echo "<td>{$item->deleted_at}</td>";
            echo "<td>{$item->reason}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "❌ Tidak ada data di trash<br>";
    }
} catch (Exception $e) {
    echo "❌ Error mengambil data trash: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<h3>KESIMPULAN</h3>";
echo "Jika semua test di atas berhasil, maka sistem trash berfungsi dengan baik.<br>";
echo "Jika ada yang gagal, periksa:<br>";
echo "1. Apakah tabel trash sudah dibuat?<br>";
echo "2. Apakah session user sudah set?<br>";
echo "3. Apakah ada error di model atau controller?<br>";
?>