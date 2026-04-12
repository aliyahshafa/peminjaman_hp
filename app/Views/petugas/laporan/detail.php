<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Detail Laporan Peminjaman</h2>
    <p class="page-subtitle">Informasi lengkap peminjaman</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-file-alt"></i> Detail Peminjaman #<?= $peminjaman['id_peminjaman'] ?></h3>
    </div>
    <div class="card-body">
        <table class="table">
            <tr>
                <th style="width:200px;">Peminjam</th>
                <td><?= esc($peminjaman['nama_user'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <th>HP / Alat</th>
                <td><?= esc(($peminjaman['merk'] ?? '') . ' ' . ($peminjaman['tipe'] ?? '')) ?: 'N/A' ?></td>
            </tr>
            <tr>
                <th>Harga Sewa/Hari</th>
                <td>Rp <?= number_format($peminjaman['harga'] ?? 0, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <th>Kondisi HP</th>
                <td><?= esc($peminjaman['kondisi'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Durasi</th>
                <td><?= intval($peminjaman['waktu'] ?? 1) ?: 1 ?> hari</td>
            </tr>
            <tr>
                <th>Total Bayar</th>
                <td><strong class="text-success">Rp <?= number_format(($peminjaman['harga'] ?? 0) * (intval($peminjaman['waktu'] ?? 1) ?: 1), 0, ',', '.') ?></strong></td>
            </tr>
            <tr>
                <th>Tanggal Pinjam</th>
                <td>
                    <?php
                    if (!empty($peminjaman['tanggal_pinjam']) && $peminjaman['tanggal_pinjam'] != '0000-00-00') {
                        echo date('d/m/Y', strtotime($peminjaman['tanggal_pinjam']));
                    } else { echo '-'; }
                    ?>
                </td>
            </tr>
            <tr>
                <th>Tanggal Kembali</th>
                <td><?= $peminjaman['tanggal_kembali'] ? date('d/m/Y', strtotime($peminjaman['tanggal_kembali'])) : '-' ?></td>
            </tr>
            <tr>
                <th>Kondisi HP Saat Kembali</th>
                <td><?= esc($peminjaman['kondisi_hp'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Denda</th>
                <td><?= $peminjaman['denda'] > 0 ? '<span class="text-danger">Rp ' . number_format($peminjaman['denda'], 0, ',', '.') . '</span>' : '<span class="text-success">Rp 0</span>' ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <?php
                    $statusMap = [
                        'Diajukan' => 'badge-warning',
                        'Disetujui' => 'badge-success',
                        'Ditolak' => 'badge-danger',
                        'Menunggu Pengembalian' => 'badge-info',
                        'Dikembalikan' => 'badge-secondary',
                    ];
                    $badgeClass = $statusMap[$peminjaman['status']] ?? 'badge-primary';
                    ?>
                    <span class="badge <?= $badgeClass ?>"><?= esc($peminjaman['status']) ?></span>
                </td>
            </tr>
            <tr>
                <th>Catatan</th>
                <td><?= esc($peminjaman['catatan'] ?? '-') ?></td>
            </tr>
        </table>

        <div class="d-flex gap-2" style="margin-top: 20px;">
            <a href="<?= base_url('/petugas/laporan/cetak-detail/' . $peminjaman['id_peminjaman']) ?>" 
               class="btn btn-primary" target="_blank">
                <i class="fas fa-print"></i> Cetak
            </a>
            <a href="<?= base_url('/petugas/laporan') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
