<?php
// Script untuk mengecek harga di database
require 'vendor/autoload.php';

// Load CodeIgniter
$pathsConfig = 'app/Config/Paths.php';
require realpath($pathsConfig) ?: $pathsConfig;

$paths = new Config\Paths();
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';
$app = require realpath($bootstrap) ?: $bootstrap;

// Get database connection
$db = \Config\Database::connect();

echo "<h2>CEK HARGA DI DATABASE</h2>";
echo "<hr>";

// Query langsung ke tabel alat
$query = $db->query("SELECT id_hp, merk, tipe, harga, status FROM alat LIMIT 10");
$results = $query->getResultArray();

echo "<h3>Data dari tabel ALAT (10 baris pertama):</h3>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Merk</th><th>Tipe</th><th>Harga</th><th>Status</th></tr>";

foreach ($results as $row) {
    echo "<tr>";
    echo "<td>" . $row['id_hp'] . "</td>";
    echo "<td>" . $row['merk'] . "</td>";
    echo "<td>" . $row['tipe'] . "</td>";
    echo "<td><strong style='color: " . ($row['harga'] > 0 ? 'green' : 'red') . "'>Rp " . number_format($row['harga'], 0, ',', '.') . "</strong></td>";
    echo "<td>" . $row['status'] . "</td>";
    echo "</tr>";
}

echo "</table>";

// Cek apakah ada harga yang 0
$queryZero = $db->query("SELECT COUNT(*) as total FROM alat WHERE harga = 0 OR harga IS NULL");
$zeroCount = $queryZero->getRow()->total;

echo "<hr>";
echo "<h3>Statistik:</h3>";
echo "<p>Total alat dengan harga Rp 0 atau NULL: <strong style='color: red;'>$zeroCount</strong></p>";

if ($zeroCount > 0) {
    echo "<p style='color: red;'><strong>⚠️ ADA MASALAH!</strong> Beberapa alat memiliki harga Rp 0 atau NULL di database.</p>";
    echo "<p>Jalankan query UPDATE untuk memperbaiki:</p>";
    echo "<pre>UPDATE alat SET harga = 50000 WHERE harga = 0 OR harga IS NULL;</pre>";
} else {
    echo "<p style='color: green;'><strong>✓ SEMUA BAIK!</strong> Semua alat memiliki harga yang valid.</p>";
}
