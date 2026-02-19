<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Pengembalian HP</h2>
    <p class="page-subtitle">Kelola dan verifikasi pengembalian HP</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-undo"></i> Daftar Pengembalian</h3>
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
                        <th>Kondisi HP</th>
                        <th>Denda</th>
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
                            <td><?= esc($p['merk']) ?> - <?= esc($p['tipe']) ?></td>
                            <td><strong class="text-success">Rp <?= number_format($p['harga'] ?? 0, 0, ',', '.') ?></strong></td>
                            <td>
                                <?php if (isset($p['kondisi_hp'])): ?>
                                    <?php 
                                    $kondisi = strtolower(trim($p['kondisi_hp']));
                                    if ($kondisi == 'baik'): 
                                    ?>
                                        <span class="badge badge-success">Baik</span>
                                    <?php elseif ($kondisi == 'rusak ringan'): ?>
                                        <span class="badge badge-warning">Rusak Ringan</span>
                                    <?php elseif ($kondisi == 'rusak berat'): ?>
                                        <span class="badge badge-danger">Rusak Berat</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary"><?= ucwords($p['kondisi_hp']) ?></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge badge-secondary">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                $denda = $p['denda'] ?? 0;
                                
                                // Jika denda 0 tapi kondisi rusak, hitung otomatis
                                if ($denda == 0 && isset($p['kondisi_hp'])) {
                                    $kondisi = strtolower(trim($p['kondisi_hp']));
                                    if ($kondisi == 'rusak ringan') {
                                        $denda = 10000;
                                    } elseif ($kondisi == 'rusak berat') {
                                        $denda = 20000;
                                    }
                                }
                                
                                if ($denda > 0): 
                                ?>
                                    <span class="text-danger">Rp <?= number_format($denda, 0, ',', '.') ?></span>
                                <?php else: ?>
                                    <span class="text-success">Rp 0</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($p['status'] == 'Menunggu Pengembalian'): ?>
                                    <span class="badge badge-warning">Menunggu Verifikasi</span>
                                <?php elseif ($p['status'] == 'Dikembalikan'): ?>
                                    <span class="badge badge-success">Dikembalikan</span>
                                <?php elseif ($p['status'] == 'Disetujui'): ?>
                                    <span class="badge badge-info">Sedang Dipinjam</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary"><?= esc($p['status']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($p['status'] == 'Menunggu Pengembalian'): ?>
                                    <a href="<?= base_url('/petugas/peminjaman/edit/' . $p['id_peminjaman']) ?>" class="btn btn-warning btn-sm" title="Edit & Verifikasi">
                                        <i class="fas fa-edit"></i> Verifikasi
                                    </a>
                                    <span class="text-success">Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="fas fa-undo"></i>
                                    <h3>Tidak ada data pengembalian</h3>
                                    <p>Belum ada HP yang perlu diverifikasi</p>
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
