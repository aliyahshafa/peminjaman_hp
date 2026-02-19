<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Pembayaran Denda</h2>
    <p class="page-subtitle">Kelola pembayaran denda untuk pengembalian HP</p>
</div>

<!-- Peminjaman yang Perlu Dibayar -->
<?php if (!empty($peminjamanPerluBayar)): ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-exclamation-triangle text-warning"></i> Perlu Pembayaran Denda</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>HP</th>
                        <th>Harga</th>
                        <th>Tanggal Pinjam</th>
                        <th>Kondisi HP</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($peminjamanPerluBayar as $p): ?>
                    <tr>
                        <td><strong><?= esc($p['merk'] ?? 'N/A') ?></strong><br><?= esc($p['tipe'] ?? '') ?></td>
                        <td><strong class="text-success">Rp <?= number_format($p['harga'] ?? 0, 0, ',', '.') ?></strong></td>
                        <td><?= date('d/m/Y H:i', strtotime($p['waktu'])) ?></td>
                        <td>
                            <?php if ($p['kondisi_hp'] == 'baik'): ?>
                                <span class="badge badge-success">Baik</span>
                            <?php elseif ($p['kondisi_hp'] == 'rusak ringan'): ?>
                                <span class="badge badge-warning">Rusak Ringan</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Rusak Berat</span>
                            <?php endif; ?>
                        </td>
                        <td><strong class="text-danger">Rp <?= number_format($p['denda'], 0, ',', '.') ?></strong></td>
                        <td>
                            <?php if ($p['sudah_bayar']): ?>
                                <?php if ($p['status_bayar'] == 'Lunas'): ?>
                                    <span class="badge badge-success">Lunas</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Menunggu</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="badge badge-danger">Belum Bayar</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!$p['sudah_bayar']): ?>
                                <a href="<?= base_url('/peminjam/pembayaran/bayar/' . $p['id_peminjaman']) ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-credit-card"></i> Bayar Sekarang
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Sudah Dibayar</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Riwayat Pembayaran -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-history"></i> Riwayat Pembayaran</h3>
    </div>
    <div class="card-body">
        <?php if (!empty($riwayatPembayaran)): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal Bayar</th>
                        <th>HP</th>
                        <th>Jumlah</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($riwayatPembayaran as $r): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($r['tanggal_bayar'])) ?></td>
                        <td><strong><?= esc($r['merk']) ?></strong><br><?= esc($r['tipe']) ?></td>
                        <td><strong class="text-success">Rp <?= number_format($r['subtotal'], 0, ',', '.') ?></strong></td>
                        <td><?= esc($r['metode_pembayaran']) ?></td>
                        <td>
                            <?php if ($r['status'] == 'Lunas'): ?>
                                <span class="badge badge-success">Lunas</span>
                            <?php else: ?>
                                <span class="badge badge-warning"><?= esc($r['status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= base_url('/peminjam/pembayaran/detail/' . $r['id_pembayaran']) ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-money-bill"></i>
            <h3>Belum Ada Pembayaran</h3>
            <p>Anda belum memiliki riwayat pembayaran denda</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
