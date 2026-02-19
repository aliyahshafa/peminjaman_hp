<?php
// Script untuk mengecek kolom di tabel user
require 'vendor/autoload.php';

// Load CodeIgniter
$pathsConfig = 'app/Config/Paths.php';
require realpath($pathsConfig) ?: $pathsConfig;

$paths = new Config\Paths();
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';
$app = require realpath($bootstrap) ?: $bootstrap;

// Get database connection
$db = \Config\Database::connect();

echo "<h2>CEK STRUKTUR TABEL USER</h2>";
echo "<hr>";

// Query untuk melihat struktur tabel user
$query = $db->query("DESCRIBE user");
$results = $query->getResultArray();

echo "<h3>Kolom-kolom di tabel USER:</h3>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";

foreach ($results as $row) {
    echo "<tr>";
    echo "<td><strong>" . $row['Field'] . "</strong></td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $row['Null'] . "</td>";
    echo "<td>" . $row['Key'] . "</td>";
    echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
    echo "<td>" . ($row['Extra'] ?? '-') . "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<hr>";
echo "<h3>Sample Data (1 baris):</h3>";
$sampleQuery = $db->query("SELECT * FROM user LIMIT 1");
$sample = $sampleQuery->getRowArray();

if ($sample) {
    echo "<table border='1' cellpadding='5'>";
    foreach ($sample as $key => $value) {
        echo "<tr>";
        echo "<td><strong>$key</strong></td>";
        echo "<td>" . ($value ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>Tidak ada data di tabel user</p>";
}
