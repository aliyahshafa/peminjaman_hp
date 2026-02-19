<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Edit Peminjaman</h2>
    <p class="page-subtitle">Perbarui status peminjaman</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit"></i> Form Edit Peminjaman</h3>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('/admin/peminjaman/update/' . $peminjaman['id_peminjaman']) ?>">
            <?= csrf_field() ?>
            
            <!-- Info Peminjaman -->
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Informasi Peminjaman</strong><br>
                Peminjam: <?= esc($peminjaman['nama_user'] ?? 'N/A') ?><br>
                Tanggal Pinjam: <?= date('d/m/Y', strtotime($peminjaman['waktu'])) ?>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Tanggal Peminjaman</label>
                    <input type="date" name="waktu" class="form-control" value="<?= date('Y-m-d', strtotime($peminjaman['waktu'])) ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="Diajukan" <?= $peminjaman['status'] == 'Diajukan' ? 'selected' : '' ?>>Diajukan</option>
                        <option value="Disetujui" <?= $peminjaman['status'] == 'Disetujui' ? 'selected' : '' ?>>Disetujui</option>
                        <option value="Menunggu Pengembalian" <?= $peminjaman['status'] == 'Menunggu Pengembalian' ? 'selected' : '' ?>>Menunggu Pengembalian</option>
                        <option value="Dikembalikan" <?= $peminjaman['status'] == 'Dikembalikan' ? 'selected' : '' ?>>Dikembalikan</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control" rows="4" placeholder="Catatan tambahan (opsional)"><?= esc($peminjaman['catatan'] ?? '') ?></textarea>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="<?= base_url('/admin/peminjaman') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
