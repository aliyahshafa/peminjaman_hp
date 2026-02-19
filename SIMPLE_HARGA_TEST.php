<?php
// SIMPLE TEST: Check if database has harga values
// Akses: http://localhost/SIMPLE_HARGA_TEST.php

// Simple database connection test
$host = 'localhost';
$dbname = 'peminjaman_hp';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>SIMPLE HARGA TEST</h2>";
    
    // Test 1: Check if harga column exists
    echo "<h3>1. Check Table Structure:</h3>";
    $stmt = $pdo->query("DESCRIBE alat");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>" . $col['Field'] . "</td>";
        echo "<td>" . $col['Type'] . "</td>";
        echo "<td>" . $col['Null'] . "</td>";
        echo "<td>" . $col['Key'] . "</td>";
        echo "<td>" . $col['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test 2: Check actual data
    echo "<h3>2. Check Actual Data:</h3>";
    $stmt = $pdo->query("SELECT id_hp, merk, tipe, harga, deleted_at FROM alat LIMIT 5");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Merk</th><th>Tipe</th><th>Harga</th><th>Deleted At</th></tr>";
    foreach ($data as $row) {
        echo "<tr>";
        echo "<td>" . $row['id_hp'] . "</td>";
        echo "<td>" . $row['merk'] . "</td>";
        echo "<td>" . $row['tipe'] . "</td>";
        echo "<td><strong>Rp " . number_format($row['harga'], 0, ',', '.') . "</strong></td>";
        echo "<td>" . ($row['deleted_at'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test 3: Count records with harga > 0
    echo "<h3>3. Count Records with Harga > 0:</h3>";
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM alat WHERE harga > 0");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Records with harga > 0: <strong>" . $count['total'] . "</strong><br>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM alat WHERE harga = 0 OR harga IS NULL");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Records with harga = 0 or NULL: <strong>" . $count['total'] . "</strong><br>";
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>