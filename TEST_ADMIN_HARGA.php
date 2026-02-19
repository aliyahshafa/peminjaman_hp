<?php
// TEST: Debug AdminController harga issue
// Akses: http://localhost/TEST_ADMIN_HARGA.php

require_once 'vendor/autoload.php';

// Load CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

echo "<h2>TEST ADMIN CONTROLLER HARGA</h2>";

// Simulate what AdminController does
$alatModel = new \App\Models\AlatModel();

echo "<h3>1. Test AlatModel->findAll():</h3>";
$recentAlat = $alatModel->orderBy('id_hp', 'DESC')->limit(5)->findAll();
echo "<pre>";
print_r($recentAlat);
echo "</pre>";

echo "<h3>2. Test Debug Method:</h3>";
$debugData = $alatModel->debugHarga();
echo "<pre>";
print_r($debugData);
echo "</pre>";

echo "<h3>3. Test Manual Query:</h3>";
$db = \Config\Database::connect();
$query = $db->query("SELECT id_hp, merk, tipe, harga, deleted_at FROM alat WHERE deleted_at IS NULL ORDER BY id_hp DESC LIMIT 5");
$manualData = $query->getResultArray();
echo "<pre>";
print_r($manualData);
echo "</pre>";

echo "<h3>4. Test Category Join:</h3>";
$categoryModel = new \App\Models\CategoryModel();
foreach ($recentAlat as $key => $alat) {
    if (isset($alat['id_category'])) {
        $category = $categoryModel->find($alat['id_category']);
        $recentAlat[$key]['nama_category'] = $category ? $category['nama_category'] : '-';
    } else {
        $recentAlat[$key]['nama_category'] = '-';
    }
}
echo "<pre>";
print_r($recentAlat);
echo "</pre>";

echo "<h3>5. Check Model Configuration:</h3>";
echo "Protected Fields: " . ($alatModel->getProtectFields() ? 'true' : 'false') . "<br>";
echo "Use Soft Deletes: " . ($alatModel->getUseSoftDeletes() ? 'true' : 'false') . "<br>";
echo "Allowed Fields: ";
echo "<pre>";
print_r($alatModel->getAllowedFields());
echo "</pre>";
?>