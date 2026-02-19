<?php
// TEST: Check if AlatModel->find() returns harga
require_once 'vendor/autoload.php';

$app = \Config\Services::codeigniter();
$app->initialize();

echo "<h2>TEST ALAT MODEL FIND</h2>";

$alatModel = new \App\Models\AlatModel();

// Test find method
echo "<h3>1. Test AlatModel->find():</h3>";
$alat = $alatModel->find(58); // Using ID from screenshot
echo "<pre>";
print_r($alat);
echo "</pre>";

if ($alat) {
    echo "Harga from find(): " . ($alat['harga'] ?? 'NOT FOUND') . "<br>";
}

// Test with explicit select
echo "<h3>2. Test with explicit select:</h3>";
$alatExplicit = $alatModel->select('id_hp, merk, tipe, harga')->find(58);
echo "<pre>";
print_r($alatExplicit);
echo "</pre>";

if ($alatExplicit) {
    echo "Harga from explicit select: " . ($alatExplicit['harga'] ?? 'NOT FOUND') . "<br>";
}

// Test findAll
echo "<h3>3. Test findAll():</h3>";
$allAlat = $alatModel->limit(2)->findAll();
echo "<pre>";
print_r($allAlat);
echo "</pre>";

// Check model configuration
echo "<h3>4. Model Configuration:</h3>";
echo "Use Soft Deletes: " . ($alatModel->getUseSoftDeletes() ? 'true' : 'false') . "<br>";
echo "Allowed Fields: ";
echo "<pre>";
print_r($alatModel->getAllowedFields());
echo "</pre>";
?>