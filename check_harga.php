<?php
// Simple check for harga values
$pdo = new PDO('mysql:host=localhost;dbname=peminjaman_hp', 'root', '');
$stmt = $pdo->query('SELECT id_hp, merk, tipe, harga FROM alat LIMIT 5');
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Database Harga Values:\n";
foreach ($data as $row) {
    echo "ID: {$row['id_hp']}, Merk: {$row['merk']}, Harga: {$row['harga']}\n";
}
?>