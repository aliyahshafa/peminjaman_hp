<?php
// Test the harga fix
require_once 'vendor/autoload.php';

$app = \Config\Services::codeigniter();
$app->initialize();

echo "<h2>TEST HARGA FIX</h2>";

// Test AlatModel after disabling soft deletes
$alatModel = new \App\Models\AlatModel();

echo "<h3>1. AlatModel findAll() after fix:</h3>";
$data = $alatModel->select('id_hp, merk, tipe, harga')->limit(3)->findAll();
echo "<pre>";
print_r($data);
echo "</pre>";

echo "<h3>2. AdminController simulation:</h3>";
$recentAlat = $alatModel->select('id_hp, merk, tipe, harga, kondisi, status, id_category')
    ->orderBy('id_hp', 'DESC')
    ->limit(3)
    ->findAll();
echo "<pre>";
print_r($recentAlat);
echo "</pre>";

echo "<h3>3. Check if harga values are present:</h3>";
foreach ($recentAlat as $alat) {
    echo "ID: {$alat['id_hp']}, Merk: {$alat['merk']}, Harga: " . ($alat['harga'] ?? 'NULL') . "<br>";
}
?>