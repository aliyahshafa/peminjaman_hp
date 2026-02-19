<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Kelola Peminjaman</h2>
    <p class="page-subtitle">Manajemen data peminjaman HP</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-handshake"></i> Daftar Peminjaman</h3>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <div class="filter-box">
            <form method="get" action="<?= base_url('/admin/peminjaman') ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="Diajukan" <?= ($status ?? '') == 'Diajukan' ? 'selected' : '' ?>>Diajukan</option>
                            <option value="Disetujui" <?= ($status ?? '') == 'Disetujui' ? 'selected' : '' ?>>Disetujui</option>
                            <option value="Menunggu Pengembalian" <?= ($status ?? '') == 'Menunggu Pengembalian' ? 'selected' : '' ?>>Menunggu Pengembalian</option>
                            <option value="Dikembalikan" <?= ($status ?? '') == 'Dikembalikan' ? 'selected' : '' ?>>Dikembalikan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cari</label>
                        <input type="text" name="keyword" class="form-control" placeholder="Nama peminjam..." value="<?= $keyword ?? '' ?>">
                    </div>
                    <div class="form-group" style="display: flex; align-items: flex-end;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
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
                        <th>Tanggal Pinjam</th>
                        <th>Peminjam</th>
                        <th>HP</th>
                        <th>Harga</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($peminjaman) && count($peminjaman) > 0): ?>
                        <?php $no = 1; foreach ($peminjaman as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= date('d/m/Y', strtotime($p['waktu'])) ?></td>
                            <td><?= esc($p['nama_user']) ?></td>
                            <td><?= esc($p['merk']) ?> <?= esc($p['tipe']) ?></td>
                            <td><strong class="text-success">Rp <?= number_format($p['harga'] ?? 0, 0, ',', '.') ?></strong></td>
                            <td><?= $p['tanggal_kembali'] ? date('d/m/Y', strtotime($p['tanggal_kembali'])) : '-' ?></td>
                            <td>
                                <?php if ($p['status'] == 'Diajukan'): ?>
                                    <span class="badge badge-warning">Diajukan</span>
                                <?php elseif ($p['status'] == 'Disetujui'): ?>
                                    <span class="badge badge-success">Disetujui</span>
                                <?php elseif ($p['status'] == 'Menunggu Pengembalian'): ?>
                                    <span class="badge badge-info">Menunggu Pengembalian</span>
                                <?php elseif ($p['status'] == 'Dikembalikan'): ?>
                                    <span class="badge badge-secondary">Dikembalikan</span>
                                <?php else: ?>
                                    <span class="badge badge-primary"><?= $p['status'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= base_url('/admin/peminjaman/edit/' . $p['id_peminjaman']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i> 
                                </a> 
                                <br><br>
                                <?php if ($p['status'] == 'Dikembalikan'): ?>
                                <a href="<?= base_url('/admin/peminjaman/delete/' . $p['id_peminjaman']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data peminjaman ini?')" title="Hapus">
                                    <i class="fas fa-trash"></i> 
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-handshake"></i>
                                    <h3>Tidak ada data peminjaman</h3>
                                    <p>Belum ada peminjaman yang tercatat</p>
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
