<?php
// DEBUG TRASH SYSTEM
// Jalankan file ini di browser untuk debug: http://localhost/your-project/DEBUG_TRASH.php

// Load CodeIgniter
require_once 'app/Config/Paths.php';
$paths = new Config\Paths();
require_once $paths->systemDirectory . '/bootstrap.php';

$app = Config\Services::codeigniter();
$app->initialize();

echo "<h2>DEBUG TRASH SYSTEM</h2>";

// Cek apakah tabel trash ada
$db = \Config\Database::connect();
$tableExists = $db->tableExists('trash');

echo "<h3>1. Cek Tabel Trash</h3>";
echo "Tabel trash ada: " . ($tableExists ? "✅ YA" : "❌ TIDAK") . "<br>";

if ($tableExists) {
    // Cek struktur tabel
    echo "<h3>2. Struktur Tabel Trash</h3>";
    $query = $db->query("DESCRIBE trash");
    $columns = $query->getResult();
    
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col->Field}</td>";
        echo "<td>{$col->Type}</td>";
        echo "<td>{$col->Null}</td>";
        echo "<td>{$col->Key}</td>";
        echo "<td>{$col->Default}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Cek data di trash
    echo "<h3>3. Data di Trash</h3>";
    $trashQuery = $db->query("SELECT * FROM trash ORDER BY deleted_at DESC");
    $trashData = $trashQuery->getResult();
    
    echo "Jumlah data di trash: " . count($trashData) . "<br>";
    
    if (count($trashData) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Table</th><th>Record ID</th><th>Deleted By</th><th>Deleted At</th><th>Data</th></tr>";
        foreach ($trashData as $item) {
            echo "<tr>";
            echo "<td>{$item->id_trash}</td>";
            echo "<td>{$item->table_name}</td>";
            echo "<td>{$item->record_id}</td>";
            echo "<td>{$item->deleted_by}</td>";
            echo "<td>{$item->deleted_at}</td>";
            echo "<td>" . substr($item->data_backup, 0, 100) . "...</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "❌ Tidak ada data di trash<br>";
    }
    
    // Test insert ke trash
    echo "<h3>4. Test Insert ke Trash</h3>";
    try {
        $testData = [
            'table_name' => 'test',
            'record_id' => 999,
            'data_backup' => json_encode(['test' => 'data']),
            'deleted_by' => 1,
            'deleted_at' => date('Y-m-d H:i:s'),
            'reason' => 'Test insert'
        ];
        
        $result = $db->table('trash')->insert($testData);
        echo "Test insert: " . ($result ? "✅ BERHASIL" : "❌ GAGAL") . "<br>";
        
        if ($result) {
            // Hapus test data
            $db->query("DELETE FROM trash WHERE table_name = 'test' AND record_id = 999");
            echo "Test data dihapus<br>";
        }
    } catch (Exception $e) {
        echo "❌ Error test insert: " . $e->getMessage() . "<br>";
    }
    
} else {
    echo "<h3>❌ Tabel trash tidak ada!</h3>";
    echo "Jalankan SQL berikut:<br>";
    echo "<pre>";
    echo "CREATE TABLE IF NOT EXISTS `trash` (
  `id_trash` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(50) NOT NULL,
  `record_id` int(11) NOT NULL,
  `data_backup` longtext NOT NULL,
  `deleted_by` int(11) NOT NULL,
  `deleted_at` datetime NOT NULL,
  `reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_trash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    echo "</pre>";
}

// Cek session user
echo "<h3>5. Cek Session</h3>";
$session = session();
echo "User ID: " . ($session->get('id_user') ?? 'TIDAK ADA') . "<br>";
echo "Role: " . ($session->get('role') ?? 'TIDAK ADA') . "<br>";

echo "<h3>6. Cek Model TrashModel</h3>";
try {
    $trashModel = new \App\Models\TrashModel();
    echo "TrashModel berhasil dimuat ✅<br>";
    
    // Test method backupData
    if (method_exists($trashModel, 'backupData')) {
        echo "Method backupData ada ✅<br>";
    } else {
        echo "Method backupData TIDAK ADA ❌<br>";
    }
} catch (Exception $e) {
    echo "Error loading TrashModel: " . $e->getMessage() . "<br>";
}