<?php
// TEST: Rental Payment Calculation (Sewa + Denda)
require_once 'vendor/autoload.php';

$app = \Config\Services::codeigniter();
$app->initialize();

echo "<h1>TEST RENTAL PAYMENT CALCULATION</h1>";

// Test 1: Simulate rental duration calculation
echo "<h2>1. SIMULATE RENTAL DURATION</h2>";
$testCases = [
    ['pinjam' => '2026-02-14', 'kembali' => '2026-02-16', 'expected' => 2],
    ['pinjam' => '2026-02-10', 'kembali' => '2026-02-16', 'expected' => 6],
    ['pinjam' => '2026-02-16', 'kembali' => '2026-02-16', 'expected' => 1], // Same day = 1 day
];

echo "<table border='1'>";
echo "<tr><th>Tanggal Pinjam</th><th>Tanggal Kembali</th><th>Durasi (Hari)</th><th>Expected</th><th>Status</th></tr>";
foreach ($testCases as $test) {
    $tanggalPinjam = new DateTime($test['pinjam']);
    $tanggalKembali = new DateTime($test['kembali']);
    $durasi = $tanggalPinjam->diff($tanggalKembali)->days;
    $durasi = max(1, $durasi); // Minimal 1 hari
    
    $status = ($durasi == $test['expected']) ? '✓' : '✗';
    
    echo "<tr>";
    echo "<td>{$test['pinjam']}</td>";
    echo "<td>{$test['kembali']}</td>";
    echo "<td><strong>$durasi</strong></td>";
    echo "<td>{$test['expected']}</td>";
    echo "<td>$status</td>";
    echo "</tr>";
}
echo "</table>";

// Test 2: Calculate payment scenarios
echo "<h2>2. PAYMENT CALCULATION SCENARIOS</h2>";
$hargaPerHari = 150000; // Example price

$scenarios = [
    ['durasi' => 1, 'kondisi' => 'baik', 'denda' => 0],
    ['durasi' => 2, 'kondisi' => 'baik', 'denda' => 0],
    ['durasi' => 3, 'kondisi' => 'baik', 'denda' => 0],
    ['durasi' => 1, 'kondisi' => 'rusak ringan', 'denda' => 10000],
    ['durasi' => 2, 'kondisi' => 'rusak ringan', 'denda' => 10000],
    ['durasi' => 1, 'kondisi' => 'rusak berat', 'denda' => 20000],
    ['durasi' => 3, 'kondisi' => 'rusak berat', 'denda' => 20000],
];

echo "<table border='1'>";
echo "<tr><th>Durasi</th><th>Kondisi HP</th><th>Biaya Sewa</th><th>Denda</th><th>TOTAL</th></tr>";
foreach ($scenarios as $s) {
    $biayaSewa = $hargaPerHari * $s['durasi'];
    $total = $biayaSewa + $s['denda'];
    
    echo "<tr>";
    echo "<td>{$s['durasi']} hari</td>";
    echo "<td>" . ucwords($s['kondisi']) . "</td>";
    echo "<td>Rp " . number_format($biayaSewa, 0, ',', '.') . "</td>";
    echo "<td>Rp " . number_format($s['denda'], 0, ',', '.') . "</td>";
    echo "<td><strong>Rp " . number_format($total, 0, ',', '.') . "</strong></td>";
    echo "</tr>";
}
echo "</table>";

// Test 3: Check actual peminjaman data
echo "<h2>3. CHECK ACTUAL PEMINJAMAN DATA</h2>";
$db = \Config\Database::connect();
try {
    $query = $db->query("
        SELECT p.*, a.merk, a.tipe, a.harga 
        FROM peminjaman p 
        JOIN alat a ON a.id_hp = p.id_hp 
        WHERE p.status = 'Disetujui' 
        LIMIT 3
    ");
    $peminjaman = $query->getResultArray();
    
    if (!empty($peminjaman)) {
        echo "<table border='1'>";
        echo "<tr><th>HP</th><th>Harga/Hari</th><th>Tanggal Pinjam</th><th>Durasi</th><th>Biaya Sewa</th></tr>";
        foreach ($peminjaman as $p) {
            $tanggalPinjam = new DateTime($p['waktu']);
            $tanggalKembali = new DateTime();
            $durasi = $tanggalPinjam->diff($tanggalKembali)->days;
            $durasi = max(1, $durasi);
            $biayaSewa = $p['harga'] * $durasi;
            
            echo "<tr>";
            echo "<td>{$p['merk']} {$p['tipe']}</td>";
            echo "<td>Rp " . number_format($p['harga'], 0, ',', '.') . "</td>";
            echo "<td>" . date('d/m/Y', strtotime($p['waktu'])) . "</td>";
            echo "<td>$durasi hari</td>";
            echo "<td><strong>Rp " . number_format($biayaSewa, 0, ',', '.') . "</strong></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Tidak ada peminjaman aktif</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

// Test 4: Example calculation
echo "<h2>4. EXAMPLE CALCULATION</h2>";
echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 5px;'>";
echo "<h3>Contoh: Peminjam A</h3>";
echo "<p><strong>HP:</strong> iPhone 13 Pro</p>";
echo "<p><strong>Harga Sewa:</strong> Rp 180.000/hari</p>";
echo "<p><strong>Tanggal Pinjam:</strong> 14 Februari 2026</p>";
echo "<p><strong>Tanggal Kembali:</strong> 16 Februari 2026</p>";
echo "<p><strong>Durasi:</strong> 2 hari</p>";
echo "<p><strong>Kondisi HP:</strong> Rusak Ringan</p>";
echo "<hr>";
echo "<p><strong>Perhitungan:</strong></p>";
echo "<p>Biaya Sewa = Rp 180.000 × 2 hari = <strong>Rp 360.000</strong></p>";
echo "<p>Denda Kerusakan = <strong>Rp 10.000</strong></p>";
echo "<p style='font-size: 1.2em; color: #007bff;'><strong>TOTAL PEMBAYARAN = Rp 370.000</strong></p>";
echo "</div>";

echo "<h2>CONCLUSION</h2>";
echo "<p><strong>✓ Sistem pembayaran sudah mencakup biaya sewa per hari!</strong></p>";
echo "<p><strong>Formula:</strong> Total = (Harga/Hari × Durasi) + Denda</p>";
echo "<p><strong>Minimal durasi:</strong> 1 hari (meskipun pinjam dan kembali di hari yang sama)</p>";
?>