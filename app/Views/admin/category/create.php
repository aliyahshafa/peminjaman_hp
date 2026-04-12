<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Tambah Kategori</h2>
    <p class="page-subtitle">Tambah kategori HP baru</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus"></i> Form Tambah Kategori</h3>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('/admin/category/store') ?>">
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label required">Nama Kategori</label>
                <input type="text" name="nama_category" class="form-control" placeholder="Masukkan nama kategori..." required>
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
