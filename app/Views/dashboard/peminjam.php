<?php 
// Extend dari template utama untuk menggunakan layout yang sama
echo $this->extend('layouts/template'); 
?>

<?php 
// Mulai section content yang akan diisi ke dalam template
echo $this->section('content'); 
?>

<!-- DASHBOARD PEMINJAM - UPDATED VERSION WITH MODERN TEMPLATE -->

<!-- Header halaman dashboard peminjam -->
<div class="page-header">
    <!-- Judul halaman -->
    <h2 class="page-title">Dashboard Peminjam</h2>
    <!-- Subtitle dengan nama user dari session -->
    <p class="page-subtitle">Selamat datang, <?= session()->get('nama_user') ?>!</p>
</div>

<?php 
// Hitung statistik dari data riwayat peminjaman
// Inisialisasi total peminjaman, jika ada data riwayat hitung jumlahnya, jika tidak set 0
$totalPeminjaman = isset($riwayat) ? count($riwayat) : 0;
// Inisialisasi peminjaman aktif dengan 0
$peminjamanAktif = 0;
// Inisialisasi total dikembalikan dengan 0
$totalDikembalikan = 0;

// Cek apakah ada data riwayat dan merupakan array
if (isset($riwayat) && is_array($riwayat)) {
    // Loop setiap riwayat untuk menghitung statistik
    foreach ($riwayat as $r) {
        // Jika status adalah Diajukan, Disetujui, atau Menunggu Pengembalian, hitung sebagai aktif
        if (in_array($r['status'], ['Diajukan', 'Disetujui', 'Menunggu Pengembalian'])) {
            $peminjamanAktif++; // Tambah counter peminjaman aktif
        }
        // Jika status adalah Dikembalikan, hitung sebagai dikembalikan
        if ($r['status'] == 'Dikembalikan') {
            $totalDikembalikan++; // Tambah counter total dikembalikan
        }
    }
}
?>

<!-- Grid untuk menampilkan kartu statistik -->
<div class="stats-grid">
    <!-- Kartu statistik total peminjaman -->
    <div class="stat-card">
        <!-- Icon handshake dengan warna primary (biru) -->
        <div class="stat-icon primary">
            <i class="fas fa-handshake"></i>
        </div>
        <!-- Detail statistik -->
        <div class="stat-details">
            <!-- Tampilkan total peminjaman yang sudah dihitung -->
            <h3><?= $totalPeminjaman ?></h3>
            <!-- Label statistik -->
            <p>Total Peminjaman</p>
        </div>
    </div>
    
    <!-- Kartu statistik peminjaman aktif -->
    <div class="stat-card">
        <!-- Icon clock dengan warna warning (kuning) -->
        <div class="stat-icon warning">
            <i class="fas fa-clock"></i>
        </div>
        <!-- Detail statistik -->
        <div class="stat-details">
            <!-- Tampilkan peminjaman aktif yang sudah dihitung -->
            <h3><?= $peminjamanAktif ?></h3>
            <!-- Label statistik -->
            <p>Peminjaman Aktif</p>
        </div>
    </div>
    
    <!-- Kartu statistik total dikembalikan -->
    <div class="stat-card">
        <!-- Icon check-circle dengan warna success (hijau) -->
        <div class="stat-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <!-- Detail statistik -->
        <div class="stat-details">
            <!-- Tampilkan total dikembalikan yang sudah dihitung -->
            <h3><?= $totalDikembalikan ?></h3>
            <!-- Label statistik -->
            <p>Dikembalikan</p>
        </div>
    </div>
    
    <!-- Kartu statistik belum dikembalikan -->
    <div class="stat-card">
        <!-- Icon mobile dengan warna info (biru muda) -->
        <div class="stat-icon info">
            <i class="fas fa-mobile-alt"></i>
        </div>
        <!-- Detail statistik -->
        <div class="stat-details">
            <!-- Hitung belum dikembalikan = total peminjaman - total dikembalikan -->
            <h3><?= $totalPeminjaman - $totalDikembalikan ?></h3>
            <!-- Label statistik -->
            <p>Belum Dikembalikan</p>
        </div>
    </div>
</div>

<!-- Card untuk menampilkan riwayat peminjaman -->
<div class="card">
    <!-- Header card dengan judul dan tombol -->
    <div class="card-header">
        <!-- Judul card dengan icon history -->
        <h3 class="card-title"><i class="fas fa-history"></i> Riwayat Peminjaman Saya</h3>
        <!-- Tombol untuk melihat semua riwayat -->
        <a href="<?= base_url('/peminjam/peminjaman') ?>" class="btn btn-primary btn-sm">
            Lihat Semua
        </a>
    </div>
    <!-- Body card berisi tabel -->
    <div class="card-body">
        <!-- Wrapper untuk tabel responsive -->
        <div class="table-responsive">
            <!-- Tabel data riwayat peminjaman -->
            <table class="table">
                <!-- Header tabel -->
                <thead>
                    <tr>
                        <th>No</th>
                        <th>HP</th>
                        <th>Harga</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <!-- Body tabel -->
                <tbody>
                    <?php 
                    // Cek apakah ada data riwayat dan jumlahnya lebih dari 0
                    if (isset($riwayat) && count($riwayat) > 0): 
                    ?>
                        <?php 
                        // Inisialisasi nomor urut
                        $no = 1;
                        // Ambil hanya 5 data terakhir untuk ditampilkan di dashboard
                        $displayRiwayat = array_slice($riwayat, 0, 5);
                        // Loop setiap riwayat dalam array displayRiwayat
                        foreach ($displayRiwayat as $r): 
                        ?>
                        <tr>
                            <!-- Tampilkan nomor urut dan increment -->
                            <td><?= $no++ ?></td>
                            <!-- Tampilkan merk dan tipe HP, jika tidak ada tampilkan N/A -->
                            <td><?= esc($r['merk'] ?? 'N/A') ?> - <?= esc($r['tipe'] ?? '') ?></td>
                            <!-- Tampilkan harga dengan format rupiah, jika null tampilkan 0 -->
                            <td><strong class="text-success">Rp <?= number_format($r['harga'] ?? 0, 0, ',', '.') ?></strong></td>
                            <!-- Tampilkan tanggal pinjam dengan format dd/mm/yyyy HH:ii, jika tidak ada tampilkan tanda strip -->
                            <td><?= isset($r['waktu']) ? date('d/m/Y H:i', strtotime($r['waktu'])) : '-' ?></td>
                            <!-- Tampilkan tanggal kembali dengan format dd/mm/yyyy, jika tidak ada atau kosong tampilkan tanda strip -->
                            <td><?= isset($r['tanggal_kembali']) && $r['tanggal_kembali'] ? date('d/m/Y', strtotime($r['tanggal_kembali'])) : '-' ?></td>
                            <!-- Kolom status dengan badge berwarna sesuai status -->
                            <td>
                                <?php 
                                // Jika status Diajukan, tampilkan badge kuning
                                if ($r['status'] == 'Diajukan'): 
                                ?>
                                    <span class="badge badge-warning">Diajukan</span>
                                <?php 
                                // Jika status Disetujui, tampilkan badge biru
                                elseif ($r['status'] == 'Disetujui'): 
                                ?>
                                    <span class="badge badge-info">Disetujui</span>
                                <?php 
                                // Jika status Menunggu Pengembalian, tampilkan badge biru primary
                                elseif ($r['status'] == 'Menunggu Pengembalian'): 
                                ?>
                                    <span class="badge badge-primary">Menunggu Pengembalian</span>
                                <?php 
                                // Jika status Dikembalikan, tampilkan badge hijau
                                elseif ($r['status'] == 'Dikembalikan'): 
                                ?>
                                    <span class="badge badge-success">Dikembalikan</span>
                                <?php 
                                // Jika status lainnya, tampilkan badge abu-abu dengan status dinamis
                                else: 
                                ?>
                                    <span class="badge badge-secondary"><?= esc($r['status']) ?></span>
                                <?php endif; ?>
                            </td>
                            <!-- Kolom denda dengan perhitungan otomatis -->
                            <td>
                                <?php 
                                // Ambil nilai denda dari data, jika tidak ada set 0
                                $denda = $r['denda'] ?? 0;
                                
                                // Jika denda 0 tapi kondisi HP rusak, hitung otomatis berdasarkan kondisi
                                if ($denda == 0 && isset($r['kondisi_hp'])) {
                                    // Ubah kondisi ke lowercase dan trim spasi untuk perbandingan
                                    $kondisi = strtolower(trim($r['kondisi_hp']));
                                    // Jika kondisi rusak ringan, set denda 10000
                                    if ($kondisi == 'rusak ringan') {
                                        $denda = 10000;
                                    // Jika kondisi rusak berat, set denda 20000
                                    } elseif ($kondisi == 'rusak berat') {
                                        $denda = 20000;
                                    }
                                }
                                
                                // Jika ada denda, tampilkan dengan warna merah
                                if ($denda > 0): 
                                ?>
                                    <span class="text-danger">Rp <?= number_format($denda, 0, ',', '.') ?></span>
                                <?php 
                                // Jika tidak ada denda, tampilkan Rp 0 dengan warna hijau
                                else: 
                                ?>
                                    <span class="text-success">Rp 0</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php 
                    // Jika tidak ada data riwayat
                    else: 
                    ?>
                        <tr>
                            <!-- Tampilkan pesan tidak ada data, colspan 7 untuk merge semua kolom -->
                            <td colspan="7" class="text-center">Belum ada riwayat peminjaman</td>
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
            <!-- Tombol untuk melihat daftar HP -->
            <a href="<?= base_url('/peminjam/alat') ?>" class="btn btn-primary">
                <i class="fas fa-mobile-alt"></i> Lihat HP
            </a>
            <!-- Tombol untuk melihat peminjaman saya -->
            <a href="<?= base_url('/peminjam/peminjaman') ?>" class="btn btn-warning">
                <i class="fas fa-handshake"></i> Peminjaman Saya
            </a>
            <!-- Tombol untuk mengelola pengembalian -->
            <a href="<?= base_url('/peminjam/pengembalian') ?>" class="btn btn-success">
                <i class="fas fa-undo"></i> Pengembalian
            </a>
            <!-- Tombol untuk melihat profil saya -->
            <a href="<?= base_url('/profile') ?>" class="btn btn-secondary">
                <i class="fas fa-user"></i> Profil Saya
            </a>
        </div>
    </div>
</div>

<?php 
// Akhiri section content
echo $this->endSection(); 
?>
