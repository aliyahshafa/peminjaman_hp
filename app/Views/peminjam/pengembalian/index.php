<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Pengembalian HP</h2>
    <p class="page-subtitle">Verifikasi dan riwayat pengembalian HP</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-undo"></i> Data Pengembalian</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Peminjam</th>
                        <th>HP</th>
                        <th>Harga</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Dikembalikan</th>
                        <th>Kondisi HP</th>
                        <th>Denda</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($pengembalian) && count($pengembalian) > 0): ?>
                        <?php $no = 1; foreach ($pengembalian as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($p['nama_user'] ?? 'N/A') ?></td>
                            <td><?= esc($p['merk']) ?> - <?= esc($p['tipe']) ?></td>
                            <td><strong class="text-success">Rp <?= number_format($p['harga'] ?? 0, 0, ',', '.') ?></strong></td>
                            <td><?= isset($p['waktu']) ? date('d/m/Y', strtotime($p['waktu'])) : '-' ?></td>
                            <td><?= isset($p['tanggal_kembali']) && $p['tanggal_kembali'] ? date('d/m/Y', strtotime($p['tanggal_kembali'])) : '-' ?></td>
                            <td>
                                <?php if (isset($p['kondisi_hp'])): ?>
                                    <?php if ($p['kondisi_hp'] == 'Baik' || $p['kondisi_hp'] == 'baik'): ?>
                                        <span class="badge badge-success">Baik</span>
                                    <?php elseif ($p['kondisi_hp'] == 'rusak ringan'): ?>
                                        <span class="badge badge-warning">Rusak Ringan</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Rusak Berat</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge badge-secondary">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (isset($p['denda']) && $p['denda'] > 0): ?>
                                    <span class="text-danger">Rp <?= number_format($p['denda'], 0, ',', '.') ?></span>
                                <?php else: ?>
                                    <span class="text-success">Rp 0</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($p['status'] == 'Menunggu Pengembalian' || $p['status'] == 'Menunggu_Pengembalian'): ?>
                                    <span class="badge badge-warning">Menunggu Verifikasi</span>
                                <?php elseif ($p['status'] == 'Dikembalikan'): ?>
                                    <span class="badge badge-success">Dikembalikan</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary"><?= esc($p['status']) ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <i class="fas fa-undo"></i>
                                    <h3>Tidak ada data pengembalian</h3>
                                    <p>Belum ada HP yang dikembalikan</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
