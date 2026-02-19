<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Tambah HP Baru</h2>
    <p class="page-subtitle">Lengkapi form di bawah untuk menambahkan HP baru</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-mobile-alt"></i> Form Tambah HP</h3>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('/admin/alat/store') ?>">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label class="form-label required">Kategori</label>
                <select name="id_category" class="form-control" required>
                    <option value="">Pilih Kategori</option>
                    <?php if (isset($category)): ?>
                        <?php foreach ($category as $cat): ?>
                            <option value="<?= $cat['id_category'] ?>"><?= esc($cat['nama_category']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Merk</label>
                    <input type="text" name="merk" class="form-control" placeholder="Contoh: Samsung" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Tipe</label>
                    <input type="text" name="tipe" class="form-control" placeholder="Contoh: Galaxy S21" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Kondisi</label>
                    <select name="kondisi" class="form-control" required>
                        <option value="">Pilih Kondisi</option>
                        <option value="Baik">Baik</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Harga Sewa per Hari</label>
                    <input type="number" name="harga" class="form-control" placeholder="Contoh: 50000" min="0" step="1000" required>
                    <small class="form-text text-muted">Harga sewa per hari (Rp 50.000 - Rp 75.000)</small>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label required">Status</label>
                <select name="status" class="form-control" required>
                    <option value="">Pilih Status</option>
                    <option value="Tersedia" selected>Tersedia</option>
                    <option value="Dipinjam">Dipinjam</option>
                </select>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="<?= base_url('/admin/alat') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
