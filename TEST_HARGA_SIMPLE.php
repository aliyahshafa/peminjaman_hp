<?php
// TEST HARGA SIMPLE
// Jalankan di browser: http://localhost/your-project/TEST_HARGA_SIMPLE.php

// Load CodeIgniter
require_once 'app/Config/Paths.php';
$paths = new Config\Paths();
require_once $paths->systemDirectory . '/bootstrap.php';

$app = Config\Services::codeigniter();
$app->initialize();

echo "<h2>TEST HARGA SIMPLE</h2>";

// Test 1: Cek langsung dari database
echo "<h3>1. Test Database Langsung</h3>";
$db = \Config\Database::connect();

try {
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
    echo "❌ Error: " . $e->getMessage();
}

// Test 2: Cek via AlatModel
echo "<h3>2. Test via AlatModel</h3>";
try {
    $alatModel = new \App\Models\AlatModel();
    $alat = $alatModel->limit(3)->findAll();
    
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Merk</th><th>Tipe</th><th>Harga</th></tr>";
    foreach ($alat as $item) {
        echo "<tr>";
        echo "<td>{$item['id_hp']}</td>";
        echo "<td>{$item['merk']}</td>";
        echo "<td>{$item['tipe']}</td>";
        echo "<td style='color: " . (($item['harga'] ?? 0) > 0 ? 'green' : 'red') . "'>" . ($item['harga'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}

// Test 3: Update harga manual
echo "<h3>3. Test Update Harga Manual</h3>";
try {
    // Update harga untuk ID pertama
    $firstAlat = $db->query("SELECT id_hp FROM alat LIMIT 1")->getRow();
    if ($firstAlat) {
        $updateResult = $db->query("UPDATE alat SET harga = 175000 WHERE id_hp = {$firstAlat->id_hp}");
        echo "Update berhasil: " . ($updateResult ? "✅ YA" : "❌ TIDAK") . "<br>";
        
        // Cek hasil update
        $checkResult = $db->query("SELECT merk, tipe, harga FROM alat WHERE id_hp = {$firstAlat->id_hp}")->getRow();
        echo "Harga setelah update: <strong style='color: green'>Rp " . number_format($checkResult->harga, 0, ',', '.') . "</strong><br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error update: " . $e->getMessage();
}

echo "<hr>";
echo "<h3>KESIMPULAN</h3>";
echo "<p>Jika harga masih 0 di test 1 dan 2, berarti:</p>";
echo "<ul>";
echo "<li>❌ Kolom harga belum ada di database, atau</li>";
echo "<li>❌ Data harga belum diisi</li>";
echo "</ul>";
echo "<p><strong>SOLUSI:</strong> Jalankan file <code>PASTI_FIX_HARGA.sql</code> step by step</p>";
?>