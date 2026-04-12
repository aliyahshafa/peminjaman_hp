<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Kelola HP</h2>
    <p class="page-subtitle">Manajemen data handphone</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-mobile-alt"></i> Daftar Handphone</h3>
        <a href="<?= base_url('/admin/alat/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah HP
        </a>
    </div>
    <div class="card-body">
        <!-- Search & Filter -->
        <div class="filter-box">
            <form method="get" action="<?= base_url('/admin/alat') ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Cari</label>
                        <input type="text" name="keyword" class="form-control" placeholder="Cari tipe HP..." value="<?= $keyword ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori</label>
                        <select name="category" class="form-control">
                            <option value="">Semua Kategori</option>
                            <?php if (isset($category)): ?>
                                <?php foreach ($category as $cat): ?>
                                    <option value="<?= $cat['id_category'] ?>" <?= ($catFilter ?? '') == $cat['id_category'] ? 'selected' : '' ?>>
                                        <?= esc($cat['nama_category']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group" style="display: flex; align-items: flex-end; gap: 8px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        <a href="<?= base_url('/admin/alat') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Merk</th>
                        <th>Tipe</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($alat) && count($alat) > 0): ?>
                        <?php $no = 1; foreach ($alat as $item): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= esc($item['merk']) ?></strong></td>
                            <td><?= esc($item['tipe']) ?></td>
                            <td><?= esc($item['nama_category'] ?? '-') ?></td>
                            <td><strong class="text-success">Rp <?= number_format($item['harga'] ?? 0, 0, ',', '.') ?></strong></td>
                            <td>
                                <?php if ($item['kondisi'] == 'Baik'): ?>
                                    <span class="badge badge-success">Baik</span>
                                <?php elseif ($item['kondisi'] == 'Rusak Ringan'): ?>
                                    <span class="badge badge-warning">Rusak Ringan</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Rusak Berat</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($item['status'] == 'Tersedia'): ?>
                                    <span class="badge badge-success">Tersedia</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Dipinjam</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= base_url('/admin/alat/edit/' . $item['id_hp']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i> 
                                </a>
                                <?php if ($item['status'] != 'Dipinjam' && $item['status'] != 'dipinjam'): ?>
                                <a href="<?= base_url('/admin/alat/delete/' . $item['id_hp']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus HP ini?')" title="Hapus">
                                    <i class="fas fa-trash"></i> 
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-mobile-alt"></i>
                                    <h3>Tidak ada data HP</h3>
                                    <p>Belum ada HP yang terdaftar dalam sistem</p>
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
