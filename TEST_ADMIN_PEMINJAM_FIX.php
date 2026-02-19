<?php
// TEST: Verify Admin and Peminjam dashboard fixes
require_once 'vendor/autoload.php';

$app = \Config\Services::codeigniter();
$app->initialize();

echo "<h1>TEST ADMIN & PEMINJAM DASHBOARD FIX</h1>";

// Test 1: AdminController simulation
echo "<h2>1. ADMIN CONTROLLER SIMULATION</h2>";
$alatModel = new \App\Models\AlatModel();
$categoryModel = new \App\Models\CategoryModel();

// Simulate AdminController logic
$recentAlatRaw = $alatModel->db->table('alat')
    ->select('*')
    ->where('deleted_at IS NULL')
    ->orderBy('id_hp', 'DESC')
    ->limit(3)
    ->get()
    ->getResultArray();

// Add category names manually
foreach ($recentAlatRaw as &$alat) {
    if (isset($alat['id_category'])) {
        $category = $categoryModel->find($alat['id_category']);
        $alat['nama_category'] = $category ? $category['nama_category'] : '-';
    } else {
        $alat['nama_category'] = '-';
    }
}

echo "<table border='1'>";
echo "<tr><th>ID</th><th>Merk</th><th>Tipe</th><th>Harga</th><th>Kategori</th></tr>";
foreach ($recentAlatRaw as $alat) {
    echo "<tr>";
    echo "<td>{$alat['id_hp']}</td>";
    echo "<td>{$alat['merk']}</td>";
    echo "<td>{$alat['tipe']}</td>";
    echo "<td><strong>Rp " . number_format($alat['harga'] ?? 0, 0, ',', '.') . "</strong></td>";
    echo "<td>{$alat['nama_category']}</td>";
    echo "</tr>";
}
echo "</table>";

// Test 2: DashboardPeminjam simulation
echo "<h2>2. DASHBOARD PEMINJAM SIMULATION</h2>";
$peminjamanModel = new \App\Models\PeminjamanModel();

// Find a user with peminjaman data
$db = \Config\Database::connect();
$userQuery = $db->query("SELECT DISTINCT id_user FROM peminjaman LIMIT 1");
$users = $userQuery->getResultArray();

if (!empty($users)) {
    $idUser = $users[0]['id_user'];
    
    // Simulate DashboardPeminjam logic
    $riwayatRaw = $peminjamanModel
        ->where('id_user', $idUser)
        ->whereIn('status', ['Diajukan', 'Disetujui', 'Menunggu Pengembalian', 'Dikembalikan'])
        ->orderBy('id_peminjaman', 'DESC')
        ->limit(2)
        ->findAll();
    
    // Add alat data manually like PetugasController
    foreach ($riwayatRaw as &$r) {
        $alatData = $alatModel->db->table('alat')
            ->select('merk, tipe, harga')
            ->where('id_hp', $r['id_hp'])
            ->get()
            ->getRowArray();
        
        if ($alatData) {
            $r['merk'] = $alatData['merk'];
            $r['tipe'] = $alatData['tipe'];
            $r['harga'] = $alatData['harga'];
        }
    }
    
    echo "<table border='1'>";
    echo "<tr><th>ID Peminjaman</th><th>User ID</th><th>Merk</th><th>Tipe</th><th>Harga</th><th>Status</th></tr>";
    foreach ($riwayatRaw as $r) {
        echo "<tr>";
        echo "<td>{$r['id_peminjaman']}</td>";
        echo "<td>{$r['id_user']}</td>";
        echo "<td>{$r['merk']}</td>";
        echo "<td>{$r['tipe']}</td>";
        echo "<td><strong>Rp " . number_format($r['harga'] ?? 0, 0, ',', '.') . "</strong></td>";
        echo "<td>{$r['status']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No peminjaman data found</p>";
}

echo "<h2>RESULT</h2>";
echo "<p><strong>If both tables above show proper harga values, then Admin and Peminjam dashboards are now fixed!</strong></p>";
echo "<p>Both now use the same approach as PetugasController which is working.</p>";
?>