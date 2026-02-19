<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Detail Peminjaman</h2>
    <p class="page-subtitle">Informasi lengkap peminjaman HP</p>
</div>

<!-- Data Peminjam -->
<div class="card">
    <div class="card-header" style="background-color: #17a2b8; color: white;">
        <h3 class="card-title" style="margin: 0;"><i class="fas fa-user"></i> Data Peminjam</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 150px;"><strong>Nama Lengkap</strong></td>
                        <td>: <?= esc($peminjaman['nama_user']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Username</strong></td>
                        <td>: <?= esc($peminjaman['username']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td>: <?= esc($peminjaman['email'] ?? '-') ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 150px;"><strong>No. Telepon</strong></td>
                        <td>: <?= esc($peminjaman['no_hp'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Alamat</strong></td>
                        <td>: <?= esc($peminjaman['alamat'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Role</strong></td>
                        <td>: <span class="badge badge-info"><?= esc($peminjaman['role']) ?></span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Data HP yang Dipinjam -->
<div class="card">
    <div class="card-header" style="background-color: #28a745; color: white;">
        <h3 class="card-title" style="margin: 0;"><i class="fas fa-mobile-alt"></i> Data HP yang Dipinjam</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 150px;"><strong>Merk</strong></td>
                        <td>: <?= esc($peminjaman['merk']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tipe</strong></td>
                        <td>: <?= esc($peminjaman['tipe']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Kategori</strong></td>
                        <td>: <?= esc($peminjaman['nama_category'] ?? '-') ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 150px;"><strong>Harga Sewa/Hari</strong></td>
                        <td>: <strong class="text-success" style="font-size: 1.2em;">Rp <?= number_format($peminjaman['harga'] ?? 0, 0, ',', '.') ?></strong></td>
                    </tr>
                    <tr>
                        <td><strong>Kondisi HP</strong></td>
                        <td>: 
                            <?php if ($peminjaman['kondisi'] == 'Baik'): ?>
                                <span class="badge badge-success">Baik</span>
                            <?php elseif ($peminjaman['kondisi'] == 'Rusak Ringan'): ?>
                                <span class="badge badge-warning">Rusak Ringan</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Rusak Berat</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Status HP</strong></td>
                        <td>: 
                            <?php if ($peminjaman['status_hp'] == 'Tersedia'): ?>
                                <span class="badge badge-success">Tersedia</span>
                            <?php else: ?>
                                <span class="badge badge-warning">Dipinjam</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Data Peminjaman -->
<div class="card">
    <div class="card-header" style="background-color: #ffc107; color: #333;">
        <h3 class="card-title" style="margin: 0;"><i class="fas fa-handshake"></i> Data Peminjaman</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 180px;"><strong>ID Peminjaman</strong></td>
                        <td>: #<?= esc($peminjaman['id_peminjaman']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Pinjam</strong></td>
                        <td>: <?= date('d/m/Y H:i', strtotime($peminjaman['waktu'])) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Kembali</strong></td>
                        <td>: <?= isset($peminjaman['tanggal_kembali']) && $peminjaman['tanggal_kembali'] ? date('d/m/Y', strtotime($peminjaman['tanggal_kembali'])) : '<span class="text-muted">Belum dikembalikan</span>' ?></td>
                    </tr>
                    <tr>
                        <td><strong>Durasi Peminjaman</strong></td>
                        <td>: 
                            <?php 
                            if (isset($peminjaman['tanggal_kembali']) && $peminjaman['tanggal_kembali']) {
                                $tanggalPinjam = new DateTime($peminjaman['waktu']);
                                $tanggalKembali = new DateTime($peminjaman['tanggal_kembali']);
                                $durasi = $tanggalPinjam->diff($tanggalKembali)->days;
                                $durasi = max(1, min(3, $durasi));
                                echo $durasi . ' hari';
                            } else {
                                echo '<span class="text-muted">-</span>';
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 180px;"><strong>Status Peminjaman</strong></td>
                        <td>: 
                            <?php if ($peminjaman['status'] == 'Diajukan'): ?>
                                <span class="badge badge-warning">Diajukan</span>
                            <?php elseif ($peminjaman['status'] == 'Disetujui'): ?>
                                <span class="badge badge-success">Disetujui</span>
                            <?php elseif ($peminjaman['status'] == 'Dikembalikan'): ?>
                                <span class="badge badge-info">Dikembalikan</span>
                            <?php else: ?>
                                <span class="badge badge-secondary"><?= esc($peminjaman['status']) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Kondisi HP Saat Kembali</strong></td>
                        <td>: <?= isset($peminjaman['kondisi_hp']) && $peminjaman['kondisi_hp'] ? esc($peminjaman['kondisi_hp']) : '<span class="text-muted">-</span>' ?></td>
                    </tr>
                    <tr>
                        <td><strong>Denda</strong></td>
                        <td>: 
                            <?php 
                            $denda = $peminjaman['denda'] ?? 0;
                            if ($denda > 0): 
                            ?>
                                <strong class="text-danger">Rp <?= number_format($denda, 0, ',', '.') ?></strong>
                            <?php else: ?>
                                <span class="text-success">Rp 0</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Catatan</strong></td>
                        <td>: <?= isset($peminjaman['catatan']) && $peminjaman['catatan'] ? esc($peminjaman['catatan']) : '<span class="text-muted">-</span>' ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tombol Aksi -->
<div class="card">
    <div class="card-body">
        <div class="d-flex gap-2">
            <a href="<?= base_url('/petugas/peminjaman') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            
            <?php if ($peminjaman['status'] == 'Diajukan'): ?>
                <form method="post" action="<?= base_url('/petugas/peminjaman/update/' . $peminjaman['id_peminjaman']) ?>" style="display: inline;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="status" value="Disetujui">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Setujui Peminjaman
                    </button>
                </form>
            <?php endif; ?>
            
            <?php if (in_array($peminjaman['status'], ['Diajukan', 'Disetujui'])): ?>
                <a href="<?= base_url('/petugas/peminjaman/edit/' . $peminjaman['id_peminjaman']) ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Data
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
