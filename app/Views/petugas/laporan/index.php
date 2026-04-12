<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Laporan Peminjaman</h2>
    <p class="page-subtitle">Generate laporan peminjaman alat</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-file-alt"></i> Filter Laporan</h3>
    </div>
    <div class="card-body">
        <form method="get" action="<?= base_url('/petugas/laporan') ?>">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="date_from" class="form-control" value="<?= $date_from ?? '' ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="date_to" class="form-control" value="<?= $date_to ?? '' ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="Diajukan" <?= ($status ?? '') == 'Diajukan' ? 'selected' : '' ?>>Diajukan</option>
                        <option value="Disetujui" <?= ($status ?? '') == 'Disetujui' ? 'selected' : '' ?>>Disetujui</option>
                        <option value="Ditolak" <?= ($status ?? '') == 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                        <option value="Dikembalikan" <?= ($status ?? '') == 'Dikembalikan' ? 'selected' : '' ?>>Dikembalikan</option>
                        <option value="Menunggu Pengembalian" <?= ($status ?? '') == 'Menunggu Pengembalian' ? 'selected' : '' ?>>Menunggu Pengembalian</option>
                    </select>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Tampilkan
                </button>
                <a href="<?= base_url('/petugas/laporan') ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<?php if (isset($peminjaman)): ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-table"></i> Hasil Laporan</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Pinjam</th>
                        <th>Peminjam</th>
                        <th>HP</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($peminjaman) > 0): ?>
                        <?php $no = 1; foreach ($peminjaman as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= ($ts = strtotime($p['waktu'] ?? '')) && $ts > 0 ? date('d/m/Y', $ts) : '-' ?></td>
                            <td><?= esc($p['nama_user'] ?? 'N/A') ?></td>
                            <td><?= isset($p['merk']) ? esc($p['merk'] . ' ' . $p['tipe']) : 'N/A' ?></td>
                            <td><?= $p['tanggal_kembali'] ? date('d/m/Y', strtotime($p['tanggal_kembali'])) : '-' ?></td>
                            <td>
                                <?php if ($p['status'] == 'Diajukan'): ?>
                                    <span class="badge badge-warning">Diajukan</span>
                                <?php elseif ($p['status'] == 'Disetujui'): ?>
                                    <span class="badge badge-success">Disetujui</span>
                                <?php elseif ($p['status'] == 'Ditolak'): ?>
                                    <span class="badge badge-danger">Ditolak</span>
                                <?php elseif ($p['status'] == 'Dikembalikan'): ?>
                                    <span class="badge badge-secondary">Dikembalikan</span>
                                <?php elseif ($p['status'] == 'Menunggu Pengembalian'): ?>
                                    <span class="badge badge-info">Menunggu Pengembalian</span>
                                <?php else: ?>
                                    <span class="badge badge-primary"><?= esc($p['status']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $params = http_build_query([
                                    'date_from' => $date_from ?? '',
                                    'date_to'   => $date_to ?? '',
                                    'status'    => $status ?? '',
                                    'id'        => $p['id_peminjaman'],
                                ]);
                                ?>
                                <a href="<?= base_url('/petugas/laporan/cetak?' . $params) ?>" class="btn btn-success btn-sm" target="_blank">
                                    <i class="fas fa-print"></i> Cetak
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
