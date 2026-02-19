<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Pembayaran Denda</h2>
    <p class="page-subtitle">Selesaikan pembayaran denda untuk menyelesaikan pengembalian</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-money-bill"></i> Detail Pembayaran</h3>
    </div>
    <div class="card-body">
        <!-- Info Peminjaman -->
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <strong>Informasi Peminjaman</strong><br>
            Peminjam: <?= esc(session()->get('nama_user')) ?><br>
            HP: <?= esc($peminjaman['merk']) ?> - <?= esc($peminjaman['tipe']) ?><br>
            Harga HP: <strong class="text-success">Rp <?= number_format($peminjaman['harga'] ?? 0, 0, ',', '.') ?></strong><br>
            Tanggal Pinjam: <?= date('d/m/Y H:i', strtotime($peminjaman['waktu'])) ?><br>
            Kondisi HP: <strong><?= ucwords(esc($peminjaman['kondisi_hp'] ?? 'baik')) ?></strong>
        </div>

        <!-- Total Denda -->
        <div class="alert alert-danger" style="text-align: center; padding: 30px;">
            <h4 style="margin: 0; color: #721c24;">Total Denda yang Harus Dibayar</h4>
            <h1 style="font-size: 48px; margin: 20px 0; color: #721c24;">
                Rp <?= number_format($peminjaman['denda'], 0, ',', '.') ?>
            </h1>
            <p style="color: #721c24; margin: 0;">
                <i class="fas fa-exclamation-triangle"></i>
                Pembayaran denda harus diselesaikan sebelum pengembalian dapat diproses
            </p>
        </div>
        
        <!-- Form Konfirmasi Pembayaran -->
        <form method="post" action="<?= base_url('/peminjam/pembayaran/proses/' . $peminjaman['id_peminjaman']) ?>">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label class="form-label required">Metode Pembayaran</label>
                <select name="metode_bayar" class="form-control" required>
                    <option value="">Pilih Metode Pembayaran</option>
                    <option value="Tunai">Tunai</option>
                    <option value="Transfer Bank">Transfer Bank</option>
                </select>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Informasi Denda:</strong><br>
                - Baik: Tidak ada denda<br>
                - Rusak Ringan: Rp 10.000<br>
                - Rusak Berat: Rp 20.000
            </div>
            
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Perhatian:</strong> Setelah mengklik tombol "Konfirmasi Pembayaran", denda akan dianggap lunas dan status pengembalian akan diselesaikan. Pastikan Anda sudah melakukan pembayaran sesuai metode yang dipilih.
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success" onclick="return confirm('Konfirmasi pembayaran denda sebesar Rp <?= number_format($peminjaman['denda'], 0, ',', '.') ?>?\n\nSetelah konfirmasi, pengembalian akan diselesaikan dan HP akan tersedia kembali.')">
                    <i class="fas fa-check"></i> Konfirmasi Pembayaran
                </button>
                <a href="<?= base_url('/peminjam/pembayaran') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>