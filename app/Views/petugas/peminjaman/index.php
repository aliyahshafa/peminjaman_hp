<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Kelola Peminjaman</h2>
    <p class="page-subtitle">Verifikasi dan kelola peminjaman HP</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-handshake"></i> Daftar Peminjaman</h3>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <div class="filter-box">
            <form method="get" action="<?= base_url('/petugas/peminjaman') ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="Diajukan" <?= ($status ?? '') == 'Diajukan' ? 'selected' : '' ?>>Diajukan</option>
                            <option value="Disetujui" <?= ($status ?? '') == 'Disetujui' ? 'selected' : '' ?>>Disetujui</option>
                        </select>
                    </div>
                    <div class="form-group" style="display: flex; align-items: flex-end; gap: 8px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="<?= base_url('/petugas/peminjaman') ?>" class="btn btn-secondary">
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
                        <th>Tanggal</th>
                        <th>Peminjam</th>
                        <th>HP</th>
                        <th>Harga</th>
                        <th>Catatan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($peminjaman) && count($peminjaman) > 0): ?>
                        <?php $no = 1; foreach ($peminjaman as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= ($ts = strtotime($p['waktu'])) && $ts > 0 ? date('d/m/Y', $ts) : date('d/m/Y') ?></td>
                            <td><?= esc($p['nama_user']) ?></td>
                            <td><?= esc($p['merk']) ?> <?= esc($p['tipe']) ?></td>
                            <td><strong class="text-success">Rp <?= number_format($p['harga'] ?? 0, 0, ',', '.') ?></strong></td>
                            <td><?= esc($p['catatan'] ?? '-') ?></td>
                            <td>
                                <?php if ($p['status'] == 'Diajukan'): ?>
                                    <span class="badge badge-warning">Diajukan</span>
                                <?php elseif ($p['status'] == 'Disetujui'): ?>
                                    <span class="badge badge-success">Disetujui</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary"><?= $p['status'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 5px; align-items: flex-start;">
                                    <div style="display: flex; gap: 5px;">
                                        <?php if ($p['status'] == 'Diajukan'): ?>
                                            <form method="post" action="<?= base_url('/petugas/peminjaman/update/' . $p['id_peminjaman']) ?>" style="display: inline; margin: 0;">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="status" value="Disetujui">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-check"></i> Setujui
                                                </button>
                                            </form>
                                            <form method="post" action="<?= base_url('/petugas/peminjaman/tolak/' . $p['id_peminjaman']) ?>" style="display: inline; margin: 0;">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menolak peminjaman ini?')">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </form>
                                            <a href="<?= base_url('/petugas/peminjaman/detail/' . $p['id_peminjaman']) ?>" class="btn btn-info btn-sm" title="Lihat Detail">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
                                        <?php elseif ($p['status'] == 'Disetujui'): ?>
                                            <a href="<?= base_url('/petugas/peminjaman/detail/' . $p['id_peminjaman']) ?>" class="btn btn-info btn-sm" title="Lihat Detail">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= base_url('/petugas/peminjaman/detail/' . $p['id_peminjaman']) ?>" class="btn btn-info btn-sm" title="Lihat Detail">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (in_array($p['status'], ['Diajukan', 'Disetujui'])): ?>
                                        <a href="<?= base_url('/petugas/peminjaman/edit/' . $p['id_peminjaman']) ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-handshake"></i>
                                    <h3>Tidak ada data peminjaman</h3>
                                    <p>Belum ada peminjaman yang perlu diverifikasi</p>
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
