<?php
// FINAL TEST: Comprehensive harga verification
// Akses: http://localhost/FINAL_HARGA_TEST.php

require_once 'vendor/autoload.php';

$app = \Config\Services::codeigniter();
$app->initialize();

echo "<h1>FINAL HARGA TEST</h1>";

// Test 1: Direct database
echo "<h2>1. Direct Database Query:</h2>";
$db = \Config\Database::connect();
$query = $db->query("SELECT id_hp, merk, tipe, harga FROM alat LIMIT 3");
$dbData = $query->getResultArray();
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Merk</th><th>Tipe</th><th>Harga</th></tr>";
foreach ($dbData as $row) {
    echo "<tr><td>{$row['id_hp']}</td><td>{$row['merk']}</td><td>{$row['tipe']}</td><td><strong>Rp " . number_format($row['harga'], 0, ',', '.') . "</strong></td></tr>";
}
echo "</table>";

// Test 2: AlatModel after fix
echo "<h2>2. AlatModel After Fix:</h2>";
$alatModel = new \App\Models\AlatModel();
$modelData = $alatModel->select('id_hp, merk, tipe, harga')->limit(3)->findAll();
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Merk</th><th>Tipe</th><th>Harga</th></tr>";
foreach ($modelData as $row) {
    echo "<tr><td>{$row['id_hp']}</td><td>{$row['merk']}</td><td>{$row['tipe']}</td><td><strong>Rp " . number_format($row['harga'] ?? 0, 0, ',', '.') . "</strong></td></tr>";
}
echo "</table>";

// Test 3: AdminController simulation
echo "<h2>3. AdminController Simulation:</h2>";
$adminData = $alatModel->select('id_hp, merk, tipe, harga, kondisi, status, id_category')
    ->orderBy('id_hp', 'DESC')
    ->limit(3)
    ->findAll();
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Merk</th><th>Tipe</th><th>Harga</th><th>Kondisi</th><th>Status</th></tr>";
foreach ($adminData as $row) {
    echo "<tr><td>{$row['id_hp']}</td><td>{$row['merk']}</td><td>{$row['tipe']}</td><td><strong>Rp " . number_format($row['harga'] ?? 0, 0, ',', '.') . "</strong></td><td>{$row['kondisi']}</td><td>{$row['status']}</td></tr>";
}
echo "</table>";

// Test 4: PeminjamanModel with harga
echo "<h2>4. PeminjamanModel with Harga:</h2>";
$peminjamanModel = new \App\Models\PeminjamanModel();
$peminjamanData = $peminjamanModel->select('peminjaman.id_peminjaman, peminjaman.nama_user, alat.merk, alat.tipe, alat.harga')
    ->join('alat', 'alat.id_hp = peminjaman.id_hp')
    ->limit(3)
    ->findAll();
echo "<table border='1'>";
echo "<tr><th>ID Peminjaman</th><th>Nama User</th><th>Merk</th><th>Tipe</th><th>Harga</th></tr>";
foreach ($peminjamanData as $row) {
    echo "<tr><td>{$row['id_peminjaman']}</td><td>{$row['nama_user']}</td><td>{$row['merk']}</td><td>{$row['tipe']}</td><td><strong>Rp " . number_format($row['harga'] ?? 0, 0, ',', '.') . "</strong></td></tr>";
}
echo "</table>";

echo "<h2>CONCLUSION:</h2>";
echo "<p>If all tables above show proper harga values (not Rp 0), then the fix is working!</p>";
?>