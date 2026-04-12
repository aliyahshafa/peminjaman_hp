<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Data HP</h2>
    <p class="page-subtitle">Daftar handphone</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-mobile-alt"></i> Daftar HP</h3>
    </div>
    <div class="card-body">
        <!-- Search Box -->
        <div class="search-box">
            <form method="get" action="<?= base_url('/petugas/alat') ?>" style="display:flex; gap:8px; align-items:center;">
                <i class="fas fa-search"></i>
                <input type="text" name="keyword" class="form-control" placeholder="Cari tipe HP..." value="<?= $keyword ?? '' ?>">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
                <a href="<?= base_url('/petugas/alat') ?>" class="btn btn-secondary btn-sm"><i class="fas fa-times"></i> Reset</a>
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
                        <th>Harga</th>
                        <th>Kategori</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($alat) && count($alat) > 0): ?>
                        <?php $no = 1; foreach ($alat as $item): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= esc($item['merk']) ?></strong></td>
                            <td><?= esc($item['tipe']) ?></td>
                            <td><strong class="text-success">Rp <?= number_format($item['harga'] ?? 0, 0, ',', '.') ?></strong></td>
                            <td><?= esc($item['nama_category'] ?? '-') ?></td>
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
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-mobile-alt"></i>
                                    <h3>Tidak ada data HP</h3>
                                    <p>Belum ada HP yang terdaftar</p>
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
