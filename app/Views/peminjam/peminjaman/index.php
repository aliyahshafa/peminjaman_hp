<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

        <i class="fas fa-check-circle"></i>
        <strong>Berhasil!</strong> <?= session()->getFlashdata('success') ?>
    

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger" role="alert" style="border-left: 4px solid #dc3545;">
        <i class="fas fa-exclamation-circle"></i>
        <strong>Error!</strong> <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="page-header">
    <h2 class="page-title">Peminjaman Saya</h2>
    <p class="page-subtitle">Riwayat peminjaman HP Anda</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-handshake"></i> Daftar Peminjaman</h3>
        <a href="<?= base_url('/peminjam/alat') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Pinjam HP
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Pinjam</th>
                        <th>HP</th>
                        <th>Durasi</th>
                        <th>Harga/Hari</th>
                        <th>Tanggal Kembali</th>
                        <th>Biaya Sewa</th>
                        <th>Denda</th>
                        <th>Total Bayar</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($peminjaman) && count($peminjaman) > 0): ?>
                        <?php 
                        $no = 1;
                        foreach ($peminjaman as $p): 
                            // Ambil data pembayaran untuk peminjaman ini menggunakan database builder
                            $db = \Config\Database::connect();
                            $pembayaran = $db->table('pembayaran')
                                ->where('id_peminjaman', $p['id_peminjaman'])
                                ->get()
                                ->getRowArray();
                            
                            // Hitung durasi dari selisih tanggal
                            $waktuTs = strtotime($p['waktu']);
                            $tanggalPinjamStr = ($waktuTs && $waktuTs > 0) ? date('Y-m-d', $waktuTs) : date('Y-m-d');
                            try {
                                $tanggalPinjam = new DateTime($tanggalPinjamStr);
                            } catch (Exception $e) {
                                $tanggalPinjam = new DateTime(date('Y-m-d'));
                            }
                            $kembaliTs = $p['tanggal_kembali'] ? strtotime($p['tanggal_kembali']) : false;
                            try {
                                $tanggalKembali = ($kembaliTs && $kembaliTs > 0) ? new DateTime(date('Y-m-d', $kembaliTs)) : new DateTime(date('Y-m-d'));
                            } catch (Exception $e) {
                                $tanggalKembali = new DateTime(date('Y-m-d'));
                            }
                            $durasi = $tanggalPinjam->diff($tanggalKembali)->days;
                            $durasi = max(1, $durasi);
                            
                            // Batasi maksimal 3 hari
                            if ($durasi > 3) {
                                $durasi = 3;
                            }
                            
                            $biayaSewa = ($p['harga'] ?? 0) * $durasi;
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= ($waktuTs && $waktuTs > 0) ? date('d/m/Y', $waktuTs) : date('d/m/Y') ?></td>
                            <td><?= esc($p['merk']) ?> <?= esc($p['tipe']) ?></td>
                            <td><strong><?= $durasi ?> hari</strong></td>
                            <td><strong class="text-success">Rp <?= number_format($p['harga'] ?? 0, 0, ',', '.') ?></strong></td>
                            <td><?= $p['tanggal_kembali'] ? date('d/m/Y', strtotime($p['tanggal_kembali'])) : '-' ?></td>
                            <td>
                                <?php if ($pembayaran): ?>
                                    <strong class="text-primary">Rp <?= number_format($biayaSewa, 0, ',', '.') ?></strong>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($pembayaran && isset($p['denda']) && $p['denda'] > 0): ?>
                                    <span class="text-danger"><strong>Rp <?= number_format($p['denda'], 0, ',', '.') ?></strong></span>
                                <?php elseif ($pembayaran): ?>
                                    <span class="text-success">Rp 0</span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($pembayaran): ?>
                                    <strong class="text-success" style="font-size: 1.1em;">Rp <?= number_format($pembayaran['subtotal'], 0, ',', '.') ?></strong>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($pembayaran): ?>
                                    <span class="badge badge-info"><?= esc($pembayaran['metode_pembayaran']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($p['status'] == 'Diajukan'): ?>
                                    <span class="badge badge-warning">Diajukan</span>
                                <?php elseif ($p['status'] == 'Disetujui'): ?>
                                    <span class="badge badge-success">Disetujui</span>
                                <?php elseif ($p['status'] == 'Menunggu Pengembalian'): ?>
                                    <span class="badge badge-info">Menunggu Pengembalian</span>
                                <?php elseif ($p['status'] == 'Dikembalikan'): ?>
                                    <span class="badge badge-secondary">Dikembalikan</span>
                                <?php else: ?>
                                    <span class="badge badge-primary"><?= $p['status'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                // Normalisasi status untuk perbandingan
                                $statusLower = strtolower(trim($p['status']));
                                ?>
                                <?php if ($statusLower == 'disetujui' || $statusLower == 'dipinjam'): ?>
                                    <a href="<?= base_url('/peminjam/peminjaman/kembalikan/' . $p['id_peminjaman']) ?>" class="btn btn-success btn-sm">
                                        <i class="fas fa-undo"></i> Kembalikan
                                    </a>
                                <?php elseif ($p['status'] == 'Menunggu Pengembalian' && isset($p['denda']) && $p['denda'] > 0): ?>
                                    <a href="<?= base_url('/peminjam/pembayaran/' . $p['id_peminjaman']) ?>" class="btn btn-danger btn-sm">
                                        <i class="fas fa-money-bill"></i> Bayar Denda
                                    </a>
                                <?php elseif ($p['status'] == 'Menunggu Pengembalian' && (!isset($p['denda']) || $p['denda'] == 0)): ?>
                                    <span class="badge badge-info">Menunggu Verifikasi Petugas</span>
                                <?php elseif ($p['status'] == 'Diajukan'): ?>
                                    <span class="text-secondary">Menunggu persetujuan</span>
                                <?php elseif ($p['status'] == 'Dikembalikan'): ?>
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Selesai</span>
                                <?php else: ?>
                                    <span class="text-secondary">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="12">
                                <div class="empty-state">
                                    <i class="fas fa-handshake"></i>
                                    <h3>Belum ada peminjaman</h3>
                                    <p>Anda belum pernah meminjam HP</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Riwayat Pembayaran Terakhir (Dipindahkan ke bawah) -->
<?php if (isset($riwayatPembayaran) && count($riwayatPembayaran) > 0): ?>
<div class="card" style="margin-top: 20px; border-left: 4px solid #28a745;">
    <div class="card-header" style="background-color: #d4edda;">
        <h3 class="card-title" style="color: #155724;">
            <i class="fas fa-receipt"></i> Riwayat Pembayaran Terakhir
        </h3>
    </div>
    <div class="card-body">
        <?php foreach ($riwayatPembayaran as $index => $payment): ?>
            <?php 
            // Hitung biaya sewa dari harga dan durasi (dari tanggal)
            try {
                $tanggalPinjam = new DateTime(date('Y-m-d', strtotime($payment['waktu_pinjam'])));
            } catch (Exception $e) {
                $tanggalPinjam = new DateTime(date('Y-m-d'));
            }
            try {
                $tanggalKembali = isset($payment['tanggal_kembali']) ? new DateTime(date('Y-m-d', strtotime($payment['tanggal_kembali']))) : new DateTime(date('Y-m-d'));
            } catch (Exception $e) {
                $tanggalKembali = new DateTime(date('Y-m-d'));
            }
            $durasi = $tanggalPinjam->diff($tanggalKembali)->days;
            $durasi = max(1, $durasi);
            
            // Batasi maksimal 3 hari
            if ($durasi > 3) {
                $durasi = 3;
            }
            
            $biayaSewa = $payment['harga'] * $durasi;
            $denda = $payment['subtotal'] - $biayaSewa;
            ?>
            <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 15px; border: 1px solid #dee2e6;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h5 style="margin: 0; color: #28a745;">
                        <i class="fas fa-mobile-alt"></i> <?= esc($payment['merk']) ?> <?= esc($payment['tipe']) ?>
                    </h5>
                    <span class="badge badge-success">Lunas</span>
                </div>
                <small class="text-muted">
                    <i class="fas fa-calendar"></i> <?= date('d/m/Y H:i', strtotime($payment['tanggal_bayar'])) ?>
                </small>
                <hr style="margin: 10px 0;">
                <table style="width: 100%; font-size: 14px;">
                    <tr>
                        <td style="padding: 5px 0;"><i class="fas fa-calendar-alt text-primary"></i> Biaya Sewa</td>
                        <td style="padding: 5px 10px;">:</td>
                        <td style="padding: 5px 0;"><strong>Rp <?= number_format($biayaSewa, 0, ',', '.') ?></strong> <span class="text-muted">(<?= $durasi ?> hari)</span></td>
                    </tr>
                    <?php if ($denda > 0): ?>
                    <tr>
                        <td style="padding: 5px 0;"><i class="fas fa-exclamation-triangle text-warning"></i> Denda</td>
                        <td style="padding: 5px 10px;">:</td>
                        <td style="padding: 5px 0;"><strong class="text-danger">Rp <?= number_format($denda, 0, ',', '.') ?></strong></td>
                    </tr>
                    <?php endif; ?>
                    <tr style="border-top: 2px solid #28a745;">
                        <td style="padding: 8px 0;"><i class="fas fa-money-bill-wave text-success"></i> <strong>Total</strong></td>
                        <td style="padding: 8px 10px;">:</td>
                        <td style="padding: 8px 0;"><strong style="font-size: 1.1em; color: #28a745;">Rp <?= number_format($payment['subtotal'], 0, ',', '.') ?></strong></td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0;"><i class="fas fa-credit-card text-info"></i> Metode</td>
                        <td style="padding: 5px 10px;">:</td>
                        <td style="padding: 5px 0;"><strong>Tunai</strong></td>
                    </tr>
                </table>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
