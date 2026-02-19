<?php
// Script untuk mengecek struktur tabel peminjaman
require 'vendor/autoload.php';

$pathsConfig = 'app/Config/Paths.php';
require realpath($pathsConfig) ?: $pathsConfig;

$paths = new Config\Paths();
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';
$app = require realpath($bootstrap) ?: $bootstrap;

$db = \Config\Database::connect();

echo "<h2>STRUKTUR TABEL PEMINJAMAN</h2><hr>";

$query = $db->query("DESCRIBE peminjaman");
$results = $query->getResultArray();

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";

foreach ($results as $row) {
    echo "<tr>";
    echo "<td><strong>" . $row['Field'] . "</strong></td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $row['Null'] . "</td>";
    echo "<td>" . $row['Key'] . "</td>";
    echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
    echo "</tr>";
}

echo "</table><hr>";

echo "<h3>Sample Data (1 baris terakhir):</h3>";
$sampleQuery = $db->query("SELECT * FROM peminjaman ORDER BY id_peminjaman DESC LIMIT 1");
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
}
