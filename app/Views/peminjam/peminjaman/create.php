<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Ajukan Peminjaman</h2>
    <p class="page-subtitle">Lengkapi form untuk mengajukan peminjaman HP</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-handshake"></i> Form Peminjaman</h3>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('/peminjam/peminjaman/store') ?>">
            <?= csrf_field() ?>
            
            <!-- Info HP -->
            <?php if (isset($alat)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>HP yang akan dipinjam:</strong><br>
                Merk: <?= esc($alat['merk']) ?><br>
                Tipe: <?= esc($alat['tipe']) ?><br>
                Kondisi: <?= esc($alat['kondisi']) ?><br>
                Status: <?= esc($alat['status']) ?>
            </div>
            
            <input type="hidden" name="id_hp" value="<?= $alat['id_hp'] ?>">
            <?php endif; ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Tanggal Pengembalian</label>
                    <input type="date" name="tanggal_kembali" class="form-control" min="<?= date('Y-m-d') ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label required">Durasi Peminjaman</label>
                <select name="waktu" class="form-control" required>
                    <option value="">Pilih Durasi</option>
                    <option value="1">1 Hari</option>
                    <option value="2">2 Hari</option>
                    <option value="3">3 Hari</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Ajukan Peminjaman
                </button>
                <a href="<?= base_url('/peminjam/alat') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
