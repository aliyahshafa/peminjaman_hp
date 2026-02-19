<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Detail Pembayaran</h2>
    <p class="page-subtitle">Informasi lengkap pembayaran denda</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-receipt"></i> Detail Pembayaran #<?= $pembayaran['id_pembayaran'] ?></h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5><i class="fas fa-info-circle"></i> Informasi Peminjaman</h5>
                <table class="table table-borderless">
                    <tr>
                        <td><strong>HP:</strong></td>
                        <td><?= esc($pembayaran['merk']) ?> - <?= esc($pembayaran['tipe']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Harga HP:</strong></td>
                        <td><span class="text-success">Rp <?= number_format($pembayaran['harga_alat'] ?? 0, 0, ',', '.') ?></span></td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Pinjam:</strong></td>
                        <td><?= date('d/m/Y H:i', strtotime($pembayaran['waktu_pinjam'])) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Kondisi HP:</strong></td>
                        <td>
                            <?php if ($pembayaran['kondisi_hp'] == 'baik'): ?>
                                <span class="badge badge-success">Baik</span>
                            <?php elseif ($pembayaran['kondisi_hp'] == 'rusak ringan'): ?>
                                <span class="badge badge-warning">Rusak Ringan</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Rusak Berat</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="col-md-6">
                <h5><i class="fas fa-money-bill"></i> Informasi Pembayaran</h5>
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Tanggal Bayar:</strong></td>
                        <td><?= date('d/m/Y H:i', strtotime($pembayaran['tanggal_bayar'])) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Jumlah Denda:</strong></td>
                        <td><span class="text-danger"><strong>Rp <?= number_format($pembayaran['subtotal'], 0, ',', '.') ?></strong></span></td>
                    </tr>
                    <tr>
                        <td><strong>Metode Bayar:</strong></td>
                        <td><?= esc($pembayaran['metode_pembayaran']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <?php if ($pembayaran['status'] == 'Lunas'): ?>
                                <span class="badge badge-success">Lunas</span>
                            <?php else: ?>
                                <span class="badge badge-warning"><?= esc($pembayaran['status']) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Status Lunas -->
        <?php if ($pembayaran['status'] == 'Lunas'): ?>
        <div class="alert alert-success text-center" style="margin-top: 20px;">
            <i class="fas fa-check-circle" style="font-size: 48px; margin-bottom: 10px;"></i>
            <h4>Pembayaran Lunas</h4>
            <p>Pembayaran denda telah berhasil diselesaikan. Pengembalian HP telah selesai dan HP sudah tersedia kembali untuk dipinjam.</p>
        </div>
        <?php endif; ?>
        
        <div class="d-flex gap-2 mt-3">
            <a href="<?= base_url('/peminjam/pembayaran') ?>" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Kembali ke Pembayaran
            </a>
            <button onclick="window.print()" class="btn btn-secondary">
                <i class="fas fa-print"></i> Cetak
            </button>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .page-header, .card-header {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>

<?= $this->endSection() ?>