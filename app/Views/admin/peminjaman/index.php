<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Kelola Peminjaman</h2>
    <p class="page-subtitle">Manajemen data peminjaman HP</p>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<!-- Modal Pinjam HP -->
<div id="modalPinjam" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:8px; padding:24px; width:100%; max-width:480px; margin:auto; position:relative; top:50%; transform:translateY(-50%);">
        <h4 style="margin-bottom:16px;"><i class="fas fa-plus-circle"></i> Pinjamkan HP</h4>
        <form method="post" action="<?= base_url('/admin/peminjaman/pinjam') ?>">
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label">Nama Peminjam</label>
                <input type="text" name="nama_user" class="form-control" placeholder="Nama peminjam..." required>
            </div>
            <div class="form-group">
                <label class="form-label">Pilih HP</label>
                <select name="id_hp" class="form-control" required>
                    <option value="">-- Pilih HP Tersedia --</option>
                    <?php if (isset($alatTersedia) && count($alatTersedia) > 0): ?>
                        <?php foreach ($alatTersedia as $a): ?>
                            <option value="<?= $a['id_hp'] ?>">
                                <?= esc($a['merk']) ?> <?= esc($a['tipe']) ?> - Rp <?= number_format($a['harga'] ?? 0, 0, ',', '.') ?>/hari
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>Tidak ada HP tersedia</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Catatan</label>
                <input type="text" name="catatan" class="form-control" placeholder="Catatan (opsional)">
            </div>
            <div style="display:flex; gap:8px; justify-content:flex-end; margin-top:16px;">
                <button type="button" onclick="document.getElementById('modalPinjam').style.display='none'" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-handshake"></i> Pinjamkan</button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Peminjaman Aktif -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-handshake"></i> Peminjaman Aktif</h3>
        <button onclick="document.getElementById('modalPinjam').style.display='flex'" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Peminjaman
        </button>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <div class="filter-box">
            <form method="get" action="<?= base_url('/admin/peminjaman') ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="">Semua Status Aktif</option>
                            <option value="Diajukan" <?= ($status ?? '') == 'Diajukan' ? 'selected' : '' ?>>Diajukan</option>
                            <option value="Disetujui" <?= ($status ?? '') == 'Disetujui' ? 'selected' : '' ?>>Disetujui</option>
                            <option value="Dipinjam" <?= ($status ?? '') == 'Dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                            <option value="Menunggu Pengembalian" <?= ($status ?? '') == 'Menunggu Pengembalian' ? 'selected' : '' ?>>Menunggu Pengembalian</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cari</label>
                        <input type="text" name="keyword" class="form-control" placeholder="Nama peminjam..." value="<?= $keyword ?? '' ?>">
                    </div>
                    <div class="form-group" style="display: flex; align-items: flex-end; gap: 8px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="<?= base_url('/admin/peminjaman') ?>" class="btn btn-secondary">
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
                            <td><?= ($ts = strtotime($p['waktu'])) && $ts > 0 ? date('d/m/Y', $ts) : date('d/m/Y') ?></td>
                            <td><?= esc($p['nama_user']) ?></td>
                            <td><?= esc($p['merk']) ?> <?= esc($p['tipe']) ?></td>
                            <td><strong class="text-success">Rp <?= number_format($p['harga'] ?? 0, 0, ',', '.') ?></strong></td>
                            <td><?= $p['tanggal_kembali'] ? date('d/m/Y', strtotime($p['tanggal_kembali'])) : '-' ?></td>
                            <td>
                                <?php if ($p['status'] == 'Diajukan'): ?>
                                    <span class="badge badge-warning">Diajukan</span>
                                <?php elseif ($p['status'] == 'Disetujui'): ?>
                                    <span class="badge badge-success">Disetujui</span>
                                <?php elseif ($p['status'] == 'Dipinjam'): ?>
                                    <span class="badge badge-primary">Dipinjam</span>
                                <?php elseif ($p['status'] == 'Menunggu Pengembalian'): ?>
                                    <span class="badge badge-info">Menunggu Pengembalian</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary"><?= $p['status'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($p['status'] == 'Diajukan'): ?>
                                    <a href="<?= base_url('/admin/peminjaman/edit/' . $p['id_peminjaman']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('/admin/peminjaman/delete/' . $p['id_peminjaman']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data peminjaman ini?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php elseif ($p['status'] == 'Menunggu Pengembalian'): ?>
                                    <a href="<?= base_url('/admin/peminjaman/edit/' . $p['id_peminjaman']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-handshake"></i>
                                    <h3>Tidak ada peminjaman aktif</h3>
                                    <p>Belum ada peminjaman yang sedang berjalan</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tabel History Peminjaman -->
<div class="card" style="margin-top: 20px;">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-history"></i> History Peminjaman</h3>
    </div>
    <div class="card-body">
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
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($historyPeminjaman) && count($historyPeminjaman) > 0): ?>
                        <?php $no = 1; foreach ($historyPeminjaman as $h): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= ($ts = strtotime($h['waktu'] ?? '')) && $ts > 0 ? date('d/m/Y', $ts) : date('d/m/Y') ?></td>
                            <td><?= esc($h['nama_user']) ?></td>
                            <td><?= esc($h['merk']) ?> <?= esc($h['tipe']) ?></td>
                            <td><strong class="text-success">Rp <?= number_format($h['harga'] ?? 0, 0, ',', '.') ?></strong></td>
                            <td><?= $h['tanggal_kembali'] ? date('d/m/Y', strtotime($h['tanggal_kembali'])) : '-' ?></td>
                            <td>
                                <?php if ($h['status'] == 'Dikembalikan'): ?>
                                    <span class="badge badge-success">Dikembalikan</span>
                                <?php elseif ($h['status'] == 'Ditolak'): ?>
                                    <span class="badge badge-danger">Ditolak</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary"><?= esc($h['status']) ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-history"></i>
                                    <h3>Belum ada history peminjaman</h3>
                                    <p>Belum ada peminjaman yang selesai atau ditolak</p>
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
