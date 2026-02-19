<?php 
// Extend dari template utama untuk menggunakan layout yang sama
echo $this->extend('layouts/template'); 
?>

<?php 
// Mulai section content yang akan diisi ke dalam template
echo $this->section('content'); 
?>

<!-- Header halaman dashboard admin -->
<div class="page-header">
    <!-- Judul halaman -->
    <h2 class="page-title">Dashboard Admin</h2>
    <!-- Subtitle dengan nama user dari session -->
    <p class="page-subtitle">Selamat datang, <?= session()->get('nama_user') ?>!</p>
</div>

<!-- Grid untuk menampilkan kartu statistik -->
<div class="stats-grid">
    <!-- Kartu statistik total user -->
    <div class="stat-card">
        <!-- Icon user dengan warna primary (biru) -->
        <div class="stat-icon primary">
            <i class="fas fa-users"></i>
        </div>
        <!-- Detail statistik -->
        <div class="stat-details">
            <!-- Tampilkan total user dari controller, jika tidak ada tampilkan 0 -->
            <h3><?= $totalUsers ?? 0 ?></h3>
            <!-- Label statistik -->
            <p>Total User</p>
        </div>
    </div>
    
    <!-- Kartu statistik total HP/alat -->
    <div class="stat-card">
        <!-- Icon mobile dengan warna success (hijau) -->
        <div class="stat-icon success">
            <i class="fas fa-mobile-alt"></i>
        </div>
        <!-- Detail statistik -->
        <div class="stat-details">
            <!-- Tampilkan total alat dari controller, jika tidak ada tampilkan 0 -->
            <h3><?= $totalAlat ?? 0 ?></h3>
            <!-- Label statistik -->
            <p>Total HP</p>
        </div>
    </div>
    
    <!-- Kartu statistik total peminjaman -->
    <div class="stat-card">
        <!-- Icon handshake dengan warna warning (kuning) -->
        <div class="stat-icon warning">
            <i class="fas fa-handshake"></i>
        </div>
        <!-- Detail statistik -->
        <div class="stat-details">
            <!-- Tampilkan total peminjaman dari controller, jika tidak ada tampilkan 0 -->
            <h3><?= $totalPeminjaman ?? 0 ?></h3>
            <!-- Label statistik -->
            <p>Total Peminjaman</p>
        </div>
    </div>
    
    <!-- Kartu statistik peminjaman aktif -->
    <div class="stat-card">
        <!-- Icon clock dengan warna info (biru muda) -->
        <div class="stat-icon info">
            <i class="fas fa-clock"></i>
        </div>
        <!-- Detail statistik -->
        <div class="stat-details">
            <!-- Tampilkan peminjaman aktif dari controller, jika tidak ada tampilkan 0 -->
            <h3><?= $peminjamanAktif ?? 0 ?></h3>
            <!-- Label statistik -->
            <p>Peminjaman Aktif</p>
        </div>
    </div>
</div>

<!-- Card untuk menampilkan user terbaru -->
<div class="card">
    <!-- Header card dengan judul dan tombol -->
    <div class="card-header">
        <!-- Judul card dengan icon -->
        <h3 class="card-title"><i class="fas fa-users"></i> User Terbaru</h3>
        <!-- Tombol untuk melihat semua user -->
        <a href="<?= base_url('/admin/user') ?>" class="btn btn-primary btn-sm">
            Lihat Semua
        </a>
    </div>
    <!-- Body card berisi tabel -->
    <div class="card-body">
        <!-- Wrapper untuk tabel responsive -->
        <div class="table-responsive">
            <!-- Tabel data user -->
            <table class="table">
                <!-- Header tabel -->
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <!-- Body tabel -->
                <tbody>
                    <?php 
                    // Cek apakah ada data user terbaru dan jumlahnya lebih dari 0
                    if (isset($recentUsers) && count($recentUsers) > 0): 
                    ?>
                        <?php 
                        // Loop setiap user dalam array recentUsers
                        foreach ($recentUsers as $user): 
                        ?>
                        <tr>
                            <!-- Tampilkan nama user dengan escape untuk keamanan -->
                            <td><?= esc($user['nama_user']) ?></td>
                            <!-- Tampilkan email user dengan escape untuk keamanan -->
                            <td><?= esc($user['email']) ?></td>
                            <!-- Kolom role dengan badge berwarna sesuai role -->
                            <td>
                                <?php 
                                // Jika role adalah Admin, tampilkan badge merah
                                if ($user['role'] == 'Admin'): 
                                ?>
                                    <span class="badge badge-danger"><?= $user['role'] ?></span>
                                <?php 
                                // Jika role adalah Petugas, tampilkan badge kuning
                                elseif ($user['role'] == 'Petugas'): 
                                ?>
                                    <span class="badge badge-warning"><?= $user['role'] ?></span>
                                <?php 
                                // Jika role lainnya (Peminjam), tampilkan badge hijau
                                else: 
                                ?>
                                    <span class="badge badge-success"><?= $user['role'] ?></span>
                                <?php endif; ?>
                            </td>
                            <!-- Kolom aksi dengan tombol edit dan hapus -->
                            <td>
                                <!-- Tombol edit user -->
                                <a href="<?= base_url('/admin/user/edit/' . $user['id_user']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Tombol hapus user dengan konfirmasi JavaScript -->
                                <a href="<?= base_url('/admin/user/delete/' . $user['id_user']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus user ini?')" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php 
                    // Jika tidak ada data user
                    else: 
                    ?>
                        <tr>
                            <!-- Tampilkan pesan tidak ada data, colspan 4 untuk merge semua kolom -->
                            <td colspan="4" class="text-center">Belum ada user</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Card untuk menampilkan HP terbaru -->
<div class="card">
    <!-- Header card dengan judul dan tombol -->
    <div class="card-header">
        <!-- Judul card dengan icon mobile -->
        <h3 class="card-title"><i class="fas fa-mobile-alt"></i> HP Terbaru</h3>
        <!-- Tombol untuk melihat semua HP -->
        <a href="<?= base_url('/admin/alat') ?>" class="btn btn-primary btn-sm">
            Lihat Semua
        </a>
    </div>
    <!-- Body card berisi tabel -->
    <div class="card-body">
        <!-- Wrapper untuk tabel responsive -->
        <div class="table-responsive">
            <!-- Tabel data HP -->
            <table class="table">
                <!-- Header tabel -->
                <thead>
                    <tr>
                        <th>Merk</th>
                        <th>Tipe</th>
                        <th>Harga</th>
                        <th>Kategori</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <!-- Body tabel -->
                <tbody>
                    <?php 
                    // Cek apakah ada data HP terbaru dan jumlahnya lebih dari 0
                    if (isset($recentAlat) && count($recentAlat) > 0): 
                    ?>
                        <?php 
                        // Loop setiap HP dalam array recentAlat
                        foreach ($recentAlat as $alat): 
                        ?>
                        <tr>
                            <!-- Tampilkan merk HP dengan bold dan escape untuk keamanan -->
                            <td><strong><?= esc($alat['merk']) ?></strong></td>
                            <!-- Tampilkan tipe HP dengan escape untuk keamanan -->
                            <td><?= esc($alat['tipe']) ?></td>
                            <!-- Tampilkan harga dengan format rupiah, jika null tampilkan 0 -->
                            <td><strong class="text-success">Rp <?= number_format($alat['harga'] ?? 0, 0, ',', '.') ?></strong></td>
                            <!-- Tampilkan nama kategori, jika tidak ada tampilkan tanda strip -->
                            <td><?= esc($alat['nama_category'] ?? '-') ?></td>
                            <!-- Kolom kondisi dengan badge berwarna sesuai kondisi -->
                            <td>
                                <?php 
                                // Jika kondisi Baik, tampilkan badge hijau
                                if ($alat['kondisi'] == 'Baik'): 
                                ?>
                                    <span class="badge badge-success">Baik</span>
                                <?php 
                                // Jika kondisi Rusak Ringan, tampilkan badge kuning
                                elseif ($alat['kondisi'] == 'Rusak Ringan'): 
                                ?>
                                    <span class="badge badge-warning">Rusak Ringan</span>
                                <?php 
                                // Jika kondisi Rusak Berat, tampilkan badge merah
                                else: 
                                ?>
                                    <span class="badge badge-danger">Rusak Berat</span>
                                <?php endif; ?>
                            </td>
                            <!-- Kolom status dengan badge berwarna -->
                            <td>
                                <?php 
                                // Jika status Tersedia, tampilkan badge hijau
                                if ($alat['status'] == 'Tersedia'): 
                                ?>
                                    <span class="badge badge-success">Tersedia</span>
                                <?php 
                                // Jika status Dipinjam, tampilkan badge merah
                                else: 
                                ?>
                                    <span class="badge badge-danger">Dipinjam</span>
                                <?php endif; ?>
                            </td>
                            <!-- Kolom aksi dengan tombol edit dan hapus -->
                            <td>
                                <!-- Tombol edit HP -->
                                <a href="<?= base_url('/admin/alat/edit/' . $alat['id_hp']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Tombol hapus HP dengan konfirmasi JavaScript -->
                                <a href="<?= base_url('/admin/alat/delete/' . $alat['id_hp']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus HP ini?')" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php 
                    // Jika tidak ada data HP
                    else: 
                    ?>
                        <tr>
                            <!-- Tampilkan pesan tidak ada data, colspan 7 untuk merge semua kolom -->
                            <td colspan="7" class="text-center">Belum ada HP</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Card untuk menampilkan peminjaman terbaru -->
<div class="card">
    <!-- Header card dengan judul dan tombol -->
    <div class="card-header">
        <!-- Judul card dengan icon handshake -->
        <h3 class="card-title"><i class="fas fa-handshake"></i> Peminjaman Terbaru</h3>
        <!-- Tombol untuk melihat semua peminjaman -->
        <a href="<?= base_url('/admin/peminjaman') ?>" class="btn btn-primary btn-sm">
            Lihat Semua
        </a>
    </div>
    <!-- Body card berisi tabel -->
    <div class="card-body">
        <!-- Wrapper untuk tabel responsive -->
        <div class="table-responsive">
            <!-- Tabel data peminjaman -->
            <table class="table">
                <!-- Header tabel -->
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Peminjam</th>
                        <th>HP</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <!-- Body tabel -->
                <tbody>
                    <?php 
                    // Cek apakah ada data peminjaman terbaru dan jumlahnya lebih dari 0
                    if (isset($recentPeminjaman) && count($recentPeminjaman) > 0): 
                    ?>
                        <?php 
                        // Loop setiap peminjaman dalam array recentPeminjaman
                        foreach ($recentPeminjaman as $p): 
                        ?>
                        <tr>
                            <!-- Tampilkan tanggal peminjaman dengan format dd/mm/yyyy -->
                            <td><?= date('d/m/Y', strtotime($p['waktu'])) ?></td>
                            <!-- Tampilkan nama peminjam dengan escape untuk keamanan -->
                            <td><?= esc($p['nama_user']) ?></td>
                            <!-- Tampilkan merk dan tipe HP dengan escape untuk keamanan -->
                            <td><?= esc($p['merk']) ?> <?= esc($p['tipe']) ?></td>
                            <!-- Kolom status dengan badge berwarna sesuai status -->
                            <td>
                                <?php 
                                // Jika status Diajukan, tampilkan badge kuning
                                if ($p['status'] == 'Diajukan'): 
                                ?>
                                    <span class="badge badge-warning">Diajukan</span>
                                <?php 
                                // Jika status Disetujui, tampilkan badge hijau
                                elseif ($p['status'] == 'Disetujui'): 
                                ?>
                                    <span class="badge badge-success">Disetujui</span>
                                <?php 
                                // Jika status Menunggu Pengembalian, tampilkan badge biru
                                elseif ($p['status'] == 'Menunggu Pengembalian'): 
                                ?>
                                    <span class="badge badge-info">Menunggu Pengembalian</span>
                                <?php 
                                // Jika status lainnya (Dikembalikan), tampilkan badge abu-abu
                                else: 
                                ?>
                                    <span class="badge badge-secondary">Dikembalikan</span>
                                <?php endif; ?>
                            </td>
                            <!-- Kolom aksi dengan tombol edit dan hapus -->
                            <td>
                                <!-- Tombol edit peminjaman -->
                                <a href="<?= base_url('/admin/peminjaman/edit/' . $p['id_peminjaman']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php 
                                // Tombol hapus hanya muncul jika status masih Diajukan
                                if ($p['status'] == 'Diajukan'): 
                                ?>
                                <!-- Tombol hapus peminjaman dengan konfirmasi JavaScript -->
                                <a href="<?= base_url('/admin/peminjaman/delete/' . $p['id_peminjaman']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus peminjaman ini?')" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php 
                    // Jika tidak ada data peminjaman
                    else: 
                    ?>
                        <tr>
                            <!-- Tampilkan pesan tidak ada data, colspan 5 untuk merge semua kolom -->
                            <td colspan="5" class="text-center">Belum ada peminjaman</td>
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
            <!-- Tombol untuk menambah user baru -->
            <a href="<?= base_url('/admin/user/create') ?>" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Tambah User
            </a>
            <!-- Tombol untuk menambah HP baru -->
            <a href="<?= base_url('/admin/alat/create') ?>" class="btn btn-success">
                <i class="fas fa-mobile-alt"></i> Tambah HP
            </a>
            <!-- Tombol untuk mengelola peminjaman -->
            <a href="<?= base_url('/admin/peminjaman') ?>" class="btn btn-warning">
                <i class="fas fa-handshake"></i> Kelola Peminjaman
            </a>
            <!-- Tombol untuk melihat log aktivitas -->
            <a href="<?= base_url('/admin/log') ?>" class="btn btn-secondary">
                <i class="fas fa-history"></i> Log Aktivitas
            </a>
        </div>
    </div>
</div>

<?php 
// Akhiri section content
echo $this->endSection(); 
?>
