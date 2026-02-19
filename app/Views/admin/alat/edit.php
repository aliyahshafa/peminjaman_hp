<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Edit HP</h2>
    <p class="page-subtitle">Perbarui informasi HP</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit"></i> Form Edit HP</h3>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('/admin/alat/update/' . $alat['id_hp']) ?>">
            <?= csrf_field() ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Merk</label>
                    <input type="text" name="merk" class="form-control" value="<?= esc($alat['merk']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Tipe</label>
                    <input type="text" name="tipe" class="form-control" value="<?= esc($alat['tipe']) ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Kategori</label>
                    <select name="id_category" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        <?php if (isset($category)): ?>
                            <?php foreach ($category as $cat): ?>
                                <option value="<?= $cat['id_category'] ?>" <?= $alat['id_category'] == $cat['id_category'] ? 'selected' : '' ?>>
                                    <?= esc($cat['nama_category']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Kondisi</label>
                    <select name="kondisi" class="form-control" required>
                        <option value="Baik" <?= $alat['kondisi'] == 'Baik' ? 'selected' : '' ?>>Baik</option>
                        <option value="Rusak Ringan" <?= $alat['kondisi'] == 'Rusak Ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
                        <option value="Rusak Berat" <?= $alat['kondisi'] == 'Rusak Berat' ? 'selected' : '' ?>>Rusak Berat</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label required">Status</label>
                <select name="status" class="form-control" required>
                    <option value="Tersedia" <?= $alat['status'] == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="Dipinjam" <?= $alat['status'] == 'Dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                </select>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="<?= base_url('/admin/alat') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
