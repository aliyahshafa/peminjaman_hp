<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Edit Kategori</h2>
    <p class="page-subtitle">Ubah data kategori HP</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit"></i> Form Edit Kategori</h3>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('/admin/category/update/' . $category['id_category']) ?>">
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label required">Nama Kategori</label>
                <input type="text" name="nama_category" class="form-control" value="<?= esc($category['nama_category']) ?>" required>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="<?= base_url('/admin/category') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
