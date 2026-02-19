<?php
// DEBUG: Test AlatModel harga issue
// Akses: http://localhost/DEBUG_HARGA_MODEL.php

require_once 'vendor/autoload.php';

// Load CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

echo "<h2>DEBUG HARGA MODEL</h2>";

// Test 1: Direct database query
echo "<h3>1. Direct Database Query:</h3>";
$db = \Config\Database::connect();
$query = $db->query("SELECT id_hp, merk, tipe, harga FROM alat LIMIT 3");
$results = $query->getResultArray();
echo "<pre>";
print_r($results);
echo "</pre>";

// Test 2: AlatModel findAll()
echo "<h3>2. AlatModel findAll():</h3>";
$alatModel = new \App\Models\AlatModel();
$modelResults = $alatModel->findAll();
echo "<pre>";
print_r(array_slice($modelResults, 0, 3)); // Only show first 3
echo "</pre>";

// Test 3: Check model configuration
echo "<h3>3. Model Configuration:</h3>";
echo "Table: " . $alatModel->getTable() . "<br>";
echo "Primary Key: " . $alatModel->getPrimaryKey() . "<br>";
echo "Allowed Fields: ";
echo "<pre>";
print_r($alatModel->getAllowedFields());
echo "</pre>";

// Test 4: Manual select with harga
echo "<h3>4. Manual Select with Harga:</h3>";
$manualResults = $alatModel->select('id_hp, merk, tipe, harga')->limit(3)->findAll();
echo "<pre>";
print_r($manualResults);
echo "</pre>";

// Test 5: Check if soft deletes affecting results
echo "<h3>5. Check Soft Deletes:</h3>";
$withDeletedResults = $alatModel->withDeleted()->limit(3)->findAll();
echo "<pre>";
print_r($withDeletedResults);
echo "</pre>";