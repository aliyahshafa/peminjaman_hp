<?php
// ULTIMATE DEBUG: Check everything step by step
require_once 'vendor/autoload.php';

$app = \Config\Services::codeigniter();
$app->initialize();

echo "<h1>ULTIMATE DEBUG HARGA</h1>";

// Step 1: Raw database check
echo "<h2>1. RAW DATABASE CHECK</h2>";
try {
    $pdo = new PDO('mysql:host=localhost;dbname=peminjaman_hp', 'root', '');
    $stmt = $pdo->query('SELECT id_hp, merk, tipe, harga FROM alat LIMIT 3');
    $rawData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Merk</th><th>Tipe</th><th>Harga (Raw)</th></tr>";
    foreach ($rawData as $row) {
        echo "<tr>";
        echo "<td>{$row['id_hp']}</td>";
        echo "<td>{$row['merk']}</td>";
        echo "<td>{$row['tipe']}</td>";
        echo "<td>{$row['harga']} (Type: " . gettype($row['harga']) . ")</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage();
}

// Step 2: CodeIgniter Database
echo "<h2>2. CODEIGNITER DATABASE</h2>";
$db = \Config\Database::connect();
$query = $db->query('SELECT id_hp, merk, tipe, harga FROM alat LIMIT 3');
$ciData = $query->getResultArray();

echo "<table border='1'>";
echo "<tr><th>ID</th><th>Merk</th><th>Tipe</th><th>Harga (CI)</th></tr>";
foreach ($ciData as $row) {
    echo "<tr>";
    echo "<td>{$row['id_hp']}</td>";
    echo "<td>{$row['merk']}</td>";
    echo "<td>{$row['tipe']}</td>";
    echo "<td>{$row['harga']} (Type: " . gettype($row['harga']) . ")</td>";
    echo "</tr>";
}
echo "</table>";

// Step 3: AlatModel
echo "<h2>3. ALAT MODEL</h2>";
$alatModel = new \App\Models\AlatModel();

// Test different methods
echo "<h3>3a. findAll()</h3>";
$modelData1 = $alatModel->limit(2)->findAll();
echo "<pre>";
print_r($modelData1);
echo "</pre>";

echo "<h3>3b. find() specific ID</h3>";
$modelData2 = $alatModel->find(58);
echo "<pre>";
print_r($modelData2);
echo "</pre>";

echo "<h3>3c. select() explicit</h3>";
$modelData3 = $alatModel->select('id_hp, merk, tipe, harga')->limit(2)->findAll();
echo "<pre>";
print_r($modelData3);
echo "</pre>";

// Step 4: AdminController simulation
echo "<h2>4. ADMIN CONTROLLER SIMULATION</h2>";
$adminData = $alatModel->select('id_hp, merk, tipe, harga, kondisi, status, id_category')
    ->orderBy('id_hp', 'DESC')
    ->limit(2)
    ->findAll();
echo "<pre>";
print_r($adminData);
echo "</pre>";

// Step 5: Check what AdminController actually sends to view
echo "<h2>5. WHAT ADMIN VIEW RECEIVES</h2>";
if (!empty($adminData)) {
    foreach ($adminData as $alat) {
        $displayHarga = number_format($alat['harga'] ?? 0, 0, ',', '.');
        echo "ID {$alat['id_hp']}: Raw harga = " . ($alat['harga'] ?? 'NULL') . ", Display = Rp $displayHarga<br>";
    }
}

echo "<h2>DIAGNOSIS</h2>";
echo "<p>Compare the values above to see where the harga gets lost!</p>";
?>