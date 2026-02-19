<?php 
// Extend dari template utama untuk menggunakan layout yang sama
echo $this->extend('layouts/template'); 
?>

<?php 
// Mulai section content yang akan diisi ke dalam template
echo $this->section('content'); 
?>

<!-- Header halaman dashboard petugas -->
<div class="page-header">
    <!-- Judul halaman -->
    <h2 class="page-title">Dashboard Petugas</h2>
    <!-- Subtitle dengan nama user dari session -->
    <p class="page-subtitle">Selamat datang, <?= session()->get('nama_user') ?>!</p>
</div>

<!-- Grid untuk menampilkan kartu statistik -->
<div class="stats-grid">
    <!-- Kartu statistik total alat -->
    <div class="stat-card">
        <!-- Icon box dengan warna primary (biru) -->
        <div class="stat-icon primary">
            <i class="fas fa-box"></i>
        </div>
        <!-- Detail statistik -->
        <div class="stat-details">
            <!-- Tampilkan total alat dari controller, jika tidak ada tampilkan 0 -->
            <h3><?= $totalAlat ?? 0 ?></h3>
            <!-- Label statistik -->
            <p>Total Alat</p>
        </div>
    </div>
    
    <!-- Kartu statistik peminjaman menunggu persetujuan -->
    <div class="stat-card">
        <!-- Icon handshake dengan warna warning (kuning) -->
        <div class="stat-icon warning">
            <i class="fas fa-handshake"></i>
        </div>
        <!-- Detail statistik -->
        <div class="stat-details">
            <!-- Tampilkan jumlah peminjaman menunggu dari controller, jika tidak ada tampilkan 0 -->
            <h3><?= $peminjamanMenunggu ?? 0 ?></h3>
            <!-- Label statistik -->
            <p>Menunggu Persetujuan</p>
        </div>
    </div>
    
    <!-- Kartu statistik peminjaman disetujui -->
    <div class="stat-card">
        <!-- Icon check-circle dengan warna success (hijau) -->
        <div class="stat-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <!-- Detail statistik -->
        <div class="stat-details">
            <!-- Tampilkan jumlah peminjaman disetujui dari controller, jika tidak ada tampilkan 0 -->
            <h3><?= $peminjamanDisetujui ?? 0 ?></h3>
            <!-- Label statistik -->
            <p>Peminjaman Disetujui</p>
        </div>
    </div>
    
    <!-- Kartu statistik total pengembalian -->
    <div class="stat-card">
        <!-- Icon undo dengan warna info (biru muda) -->
        <div class="stat-icon info">
            <i class="fas fa-undo"></i>
        </div>
        <!-- Detail statistik -->
        <div class="stat-details">
            <!-- Tampilkan total pengembalian dari controller, jika tidak ada tampilkan 0 -->
            <h3><?= $totalPengembalian ?? 0 ?></h3>
            <!-- Label statistik -->
            <p>Total Pengembalian</p>
        </div>
    </div>
</div>

<!-- Card untuk menampilkan peminjaman menunggu persetujuan -->
<div class="card">
    <!-- Header card dengan judul dan tombol -->
    <div class="card-header">
        <!-- Judul card dengan icon clock -->
        <h3 class="card-title"><i class="fas fa-clock"></i> Peminjaman Menunggu Persetujuan</h3>
        <!-- Tombol untuk melihat semua peminjaman -->
        <a href="<?= base_url('/petugas/peminjaman') ?>" class="btn btn-primary btn-sm">
            Lihat Semua
        </a>
    </div>
    <!-- Body card berisi tabel -->
    <div class="card-body">
        <!-- Wrapper untuk tabel responsive -->
        <div class="table-responsive">
            <!-- Tabel data peminjaman menunggu -->
            <table class="table">
                <!-- Header tabel -->
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <!-- Body tabel -->
                <tbody>
                    <?php 
                    // Cek apakah ada data peminjaman menunggu dan jumlahnya lebih dari 0
                    if (isset($peminjamanMenungguList) && count($peminjamanMenungguList) > 0): 
                    ?>
                        <?php 
                        // Loop setiap peminjaman dalam array peminjamanMenungguList
                        foreach ($peminjamanMenungguList as $p): 
                        ?>
                        <tr>
                            <!-- Tampilkan tanggal peminjaman dengan format dd/mm/yyyy -->
                            <td><?= date('d/m/Y', strtotime($p['waktu'])) ?></td>
                            <!-- Tampilkan nama peminjam dengan escape untuk keamanan -->
                            <td><?= esc($p['nama_user']) ?></td>
                            <!-- Tampilkan merk dan tipe alat dengan escape untuk keamanan -->
                            <td><?= esc($p['merk']) ?> - <?= esc($p['tipe']) ?></td>
                            <!-- Tampilkan harga dengan format rupiah, jika null tampilkan 0 -->
                            <td><strong class="text-success">Rp <?= number_format($p['harga'] ?? 0, 0, ',', '.') ?></strong></td>
                            <!-- Tampilkan status dengan badge kuning untuk menunggu -->
                            <td><span class="badge badge-warning">Menunggu</span></td>
                            <!-- Kolom aksi dengan tombol lihat detail -->
                            <td>
                                <!-- Tombol untuk melihat detail peminjaman -->
                                <a href="<?= base_url('/petugas/peminjaman') ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php 
                    // Jika tidak ada data peminjaman menunggu
                    else: 
                    ?>
                        <tr>
                            <!-- Tampilkan pesan tidak ada data, colspan 6 untuk merge semua kolom -->
                            <td colspan="6" class="text-center">Tidak ada peminjaman menunggu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Card untuk aksi cepat -->
<div class="card">
    <!-- Header card dengan judul -->
    <div class="card-header">
        <!-- Judul card dengan icon bolt (petir) -->
        <h3 class="card-title"><i class="fas fa-bolt"></i> Aksi Cepat</h3>
    </div>
    <!-- Body card berisi tombol-tombol aksi -->
    <div class="card-body">
        <!-- Container flex dengan gap untuk spacing antar tombol -->
        <div class="d-flex gap-2">
            <!-- Tombol untuk melihat daftar alat -->
            <a href="<?= base_url('/petugas/alat') ?>" class="btn btn-primary">
                <i class="fas fa-box"></i> Lihat Alat
            </a>
            <!-- Tombol untuk mengelola peminjaman -->
            <a href="<?= base_url('/petugas/peminjaman') ?>" class="btn btn-warning">
                <i class="fas fa-handshake"></i> Kelola Peminjaman
            </a>
            <!-- Tombol untuk mengelola pengembalian -->
            <a href="<?= base_url('/petugas/pengembalian') ?>" class="btn btn-success">
                <i class="fas fa-undo"></i> Pengembalian
            </a>
            <!-- Tombol untuk melihat laporan -->
            <a href="<?= base_url('/petugas/laporan') ?>" class="btn btn-secondary">
                <i class="fas fa-file-alt"></i> Laporan
            </a>
        </div>
    </div>
</div>

<?php 
// Akhiri section content
echo $this->endSection(); 
?>
