<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Kelola Kategori</h2>
    <p class="page-subtitle">Manajemen data kategori HP</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-tags"></i> Daftar Kategori</h3>
        <a href="<?= base_url('/admin/category/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>
    </div>
    <div class="card-body">
        <!-- Search -->
        <div class="filter-box">
            <form method="get" action="<?= base_url('/admin/category') ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Cari</label>
                        <input type="text" name="keyword" class="form-control" placeholder="Cari nama kategori..." value="<?= $keyword ?? '' ?>">
                    </div>
                    <div class="form-group" style="display: flex; align-items: flex-end; gap: 8px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        <a href="<?= base_url('/admin/category') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($category) && count($category) > 0): ?>
                        <?php $no = 1; foreach ($category as $cat): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($cat['nama_category']) ?></td>
                            <td>
                                <a href="<?= base_url('/admin/category/edit/' . $cat['id_category']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('/admin/category/delete/' . $cat['id_category']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kategori ini?')" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">
                                <div class="empty-state">
                                    <i class="fas fa-tags"></i>
                                    <h3>Tidak ada data kategori</h3>
                                    <p>Belum ada kategori yang terdaftar</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
