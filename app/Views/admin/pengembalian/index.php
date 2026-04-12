<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Pengembalian HP</h2>
    <p class="page-subtitle">Verifikasi pengembalian HP</p>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<!-- Modal Tambah Pengembalian -->
<div id="modalTambahPengembalian" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:8px; padding:24px; width:100%; max-width:480px; margin:auto; position:relative; top:50%; transform:translateY(-50%);">
        <h4 style="margin-bottom:16px;"><i class="fas fa-undo"></i> Tambah Pengembalian</h4>
        <form method="post" action="<?= base_url('/admin/pengembalian/tambah') ?>">
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label">Pilih Peminjaman</label>
                <select name="id_peminjaman" class="form-control" required>
                    <option value="">-- Pilih Peminjaman Aktif --</option>
                    <?php if (isset($peminjamanAktif) && count($peminjamanAktif) > 0): ?>
                        <?php foreach ($peminjamanAktif as $pa): ?>
                            <option value="<?= $pa['id_peminjaman'] ?>">
                                <?= esc($pa['nama_user']) ?> - <?= esc($pa['merk']) ?> <?= esc($pa['tipe']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>Tidak ada peminjaman aktif</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Kondisi HP</label>
                <select name="kondisi_hp" class="form-control" required>
                    <option value="">-- Pilih Kondisi --</option>
                    <option value="baik">Baik (Tidak ada kerusakan)</option>
                    <option value="rusak ringan">Rusak Ringan (Denda Rp 10.000)</option>
                    <option value="rusak berat">Rusak Berat (Denda Rp 20.000)</option>
                </select>
            </div>
            <div style="display:flex; gap:8px; justify-content:flex-end; margin-top:16px;">
                <button type="button" onclick="document.getElementById('modalTambahPengembalian').style.display='none'" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Tambah</button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Menunggu Verifikasi -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-undo"></i> Menunggu Verifikasi</h3>
        <button onclick="document.getElementById('modalTambahPengembalian').style.display='flex'" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Pengembalian
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Peminjam</th>
                        <th>HP</th>
                        <th>Harga</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Dikembalikan</th>
                        <th>Kondisi HP</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($pengembalian) && count($pengembalian) > 0): ?>
                        <?php $no = 1; foreach ($pengembalian as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($p['nama_user'] ?? 'N/A') ?></td>
                            <td><?= esc($p['merk']) ?> - <?= esc($p['tipe']) ?></td>
                            <td><strong class="text-success">Rp <?= number_format($p['harga'] ?? 0, 0, ',', '.') ?></strong></td>
                            <td><?= ($ts = strtotime($p['waktu'] ?? '')) && $ts > 0 ? date('d/m/Y', $ts) : date('d/m/Y') ?></td>
                            <td><?= isset($p['tanggal_kembali']) && $p['tanggal_kembali'] ? date('d/m/Y', strtotime($p['tanggal_kembali'])) : '-' ?></td>
                            <td>
                                <?php if (isset($p['kondisi_hp'])): ?>
                                    <?php if ($p['kondisi_hp'] == 'Baik' || $p['kondisi_hp'] == 'baik'): ?>
                                        <span class="badge badge-success">Baik</span>
                                    <?php elseif ($p['kondisi_hp'] == 'rusak ringan'): ?>
                                        <span class="badge badge-warning">Rusak Ringan</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Rusak Berat</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge badge-secondary">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (isset($p['denda']) && $p['denda'] > 0): ?>
                                    <span class="text-danger">Rp <?= number_format($p['denda'], 0, ',', '.') ?></span>
                                <?php else: ?>
                                    <span class="text-success">Rp 0</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge badge-warning">Menunggu Verifikasi</span></td>
                            <td>
                                <a href="<?= base_url('/admin/pengembalian/setujui/' . $p['id_peminjaman']) ?>" 
                                   class="btn btn-success btn-sm"
                                   onclick="return confirm('Setujui pengembalian HP ini?')"
                                   title="Setujui">
                                    <i class="fas fa-check"></i> Setujui
                                </a>
                                <a href="<?= base_url('/admin/peminjaman/edit/' . $p['id_peminjaman']) ?>" 
                                   class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <i class="fas fa-undo"></i>
                                    <h3>Tidak ada pengembalian menunggu verifikasi</h3>
                                    <p>Semua pengembalian sudah diverifikasi</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tabel History Pengembalian -->
<div class="card" style="margin-top: 20px;">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-history"></i> History Pengembalian</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Peminjam</th>
                        <th>HP</th>
                        <th>Harga</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Dikembalikan</th>
                        <th>Kondisi HP</th>
                        <th>Denda</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($historyPengembalian) && count($historyPengembalian) > 0): ?>
                        <?php $no = 1; foreach ($historyPengembalian as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($p['nama_user'] ?? 'N/A') ?></td>
                            <td><?= esc($p['merk']) ?> - <?= esc($p['tipe']) ?></td>
                            <td><strong class="text-success">Rp <?= number_format($p['harga'] ?? 0, 0, ',', '.') ?></strong></td>
                            <td><?= ($ts = strtotime($p['waktu'] ?? '')) && $ts > 0 ? date('d/m/Y', $ts) : date('d/m/Y') ?></td>
                            <td><?= isset($p['tanggal_kembali']) && $p['tanggal_kembali'] ? date('d/m/Y', strtotime($p['tanggal_kembali'])) : '-' ?></td>
                            <td>
                                <?php if (isset($p['kondisi_hp'])): ?>
                                    <?php if ($p['kondisi_hp'] == 'Baik' || $p['kondisi_hp'] == 'baik'): ?>
                                        <span class="badge badge-success">Baik</span>
                                    <?php elseif ($p['kondisi_hp'] == 'rusak ringan'): ?>
                                        <span class="badge badge-warning">Rusak Ringan</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Rusak Berat</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge badge-secondary">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (isset($p['denda']) && $p['denda'] > 0): ?>
                                    <span class="text-danger">Rp <?= number_format($p['denda'], 0, ',', '.') ?></span>
                                <?php else: ?>
                                    <span class="text-success">Rp 0</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge badge-success">Dikembalikan</span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="fas fa-history"></i>
                                    <h3>Belum ada history pengembalian</h3>
                                    <p>Belum ada HP yang selesai dikembalikan</p>
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
