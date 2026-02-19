<?php
// MANUAL TEST TRASH - Jalankan di browser
require_once 'app/Config/Paths.php';
$paths = new Config\Paths();
require_once $paths->systemDirectory . '/bootstrap.php';

$app = Config\Services::codeigniter();
$app->initialize();

// Set session
$session = session();
$session->set([
    'id_user' => 1,
    'role' => 'Admin',
    'nama_user' => 'Test Admin'
]);

echo "<h2>MANUAL TEST TRASH</h2>";

$db = \Config\Database::connect();

// 1. Cek tabel trash
echo "<h3>1. Cek Tabel Trash</h3>";
$tableExists = $db->tableExists('trash');
echo "Tabel trash ada: " . ($tableExists ? "✅ YA" : "❌ TIDAK") . "<br>";

if (!$tableExists) {
    echo "<strong>Buat tabel trash dulu!</strong><br>";
    exit;
}

// 2. Manual insert ke trash
echo "<h3>2. Manual Insert ke Trash</h3>";
$testData = [
    'table_name' => 'alat',
    'record_id' => 999,
    'data_backup' => json_encode([
        'id_hp' => 999,
        'merk' => 'Samsung',
        'tipe' => 'Galaxy Test',
        'kondisi' => 'Baik',
        'status' => 'Tersedia',
        'harga' => 5000000
    ]),
    'deleted_by' => 1,
    'deleted_at' => date('Y-m-d H:i:s'),
    'reason' => 'Manual test insert'
];

try {
    $result = $db->table('trash')->insert($testData);
    echo "Manual insert: " . ($result ? "✅ BERHASIL" : "❌ GAGAL") . "<br>";
    
    if ($result) {
        $insertId = $db->insertID();
        echo "Insert ID: " . $insertId . "<br>";
        
        // Cek data yang baru diinsert
        $insertedData = $db->table('trash')->where('id_trash', $insertId)->get()->getRow();
        echo "Data yang diinsert:<br>";
        echo "<pre>" . print_r($insertedData, true) . "</pre>";
    }
} catch (Exception $e) {
    echo "❌ Error manual insert: " . $e->getMessage() . "<br>";
}

// 3. Test dengan TrashModel
echo "<h3>3. Test dengan TrashModel</h3>";
try {
    $trashModel = new \App\Models\TrashModel();
    
    $testData2 = [
        'id_hp' => 998,
        'merk' => 'iPhone',
        'tipe' => 'Test Model',
        'kondisi' => 'Baik',
        'status' => 'Tersedia',
        'harga' => 8000000
    ];
    
    $backupResult = $trashModel->backupData('alat', 998, $testData2, 'Test via TrashModel');
    
    if ($backupResult) {
        echo "✅ TrashModel backup berhasil! ID: " . $backupResult . "<br>";
    } else {
        echo "❌ TrashModel backup gagal<br>";
        $errors = $trashModel->errors();
        if ($errors) {
            echo "Errors: <pre>" . print_r($errors, true) . "</pre>";
        }
    }
} catch (Exception $e) {
    echo "❌ Error TrashModel: " . $e->getMessage() . "<br>";
}

// 4. Lihat semua data di trash
echo "<h3>4. Semua Data di Trash</h3>";
try {
    $allTrash = $db->table('trash')->orderBy('deleted_at', 'DESC')->get()->getResult();
    echo "Total data di trash: " . count($allTrash) . "<br>";
    
    if (count($allTrash) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Table</th><th>Record ID</th><th>Deleted By</th><th>Deleted At</th><th>Reason</th><th>Data</th></tr>";
        foreach ($allTrash as $item) {
            echo "<tr>";
            echo "<td>{$item->id_trash}</td>";
            echo "<td>{$item->table_name}</td>";
            echo "<td>{$item->record_id}</td>";
            echo "<td>{$item->deleted_by}</td>";
            echo "<td>{$item->deleted_at}</td>";
            echo "<td>{$item->reason}</td>";
            echo "<td>" . substr($item->data_backup, 0, 50) . "...</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (Exception $e) {
    echo "❌ Error mengambil data trash: " . $e->getMessage() . "<br>";
}

// 5. Cleanup test data
echo "<h3>5. Cleanup Test Data</h3>";
try {
    $deleted = $db->table('trash')->whereIn('record_id', [999, 998])->delete();
    echo "Test data dihapus: " . $deleted . " records<br>";
} catch (Exception $e) {
    echo "❌ Error cleanup: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<strong>Selesai testing. Cek hasilnya di atas.</strong>";
?>