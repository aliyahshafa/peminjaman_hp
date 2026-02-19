<?php
// Test file untuk debug error
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "Test 1: PHP OK<br>";

// Load CodeIgniter
require_once __DIR__ . '/../vendor/autoload.php';
echo "Test 2: Autoload OK<br>";

// Test database connection
$db = \Config\Database::connect();
echo "Test 3: Database OK<br>";

// Test query alat
try {
    $query = $db->query("SELECT alat.*, category.nama_category 
                         FROM alat 
                         LEFT JOIN category ON category.id_category = alat.id_category 
                         LIMIT 5");
    $results = $query->getResultArray();
    
    echo "Test 4: Query OK<br>";
    echo "Jumlah data: " . count($results) . "<br><br>";
    
    if (count($results) > 0) {
        echo "<pre>";
        print_r($results[0]);
        echo "</pre>";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
