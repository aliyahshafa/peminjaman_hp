<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Data Terhapus (Trash)</h2>
    <p class="page-subtitle">Kelola data yang telah dihapus</p>
    
    <?php if (isset($debug)): ?>
        <div class="alert alert-info">
            <strong>Debug Info:</strong> 
            Total records: <?= $debug['total_records'] ?> | 
            Table exists: <?= $debug['table_exists'] ? 'Yes' : 'No' ?> | 
            User ID: <?= $debug['user_id'] ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <strong>Error:</strong> <?= esc($error) ?>
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-trash"></i> Daftar Data Terhapus</h3>
        <div class="d-flex gap-2">
            <?php if (isset($trash) && count($trash) > 0): ?>
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmClearTrash()">
                    <i class="fas fa-trash-alt"></i> Kosongkan Trash
                </button>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tabel</th>
                        <th>Data</th>
                        <th>Dihapus Oleh</th>
                        <th>Tanggal Hapus</th>
                        <th>Alasan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($trash) && count($trash) > 0): ?>
                        <?php $no = 1; foreach ($trash as $item): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <span class="badge badge-info"><?= ucfirst($item['table_name']) ?></span>
                            </td>
                            <td>
                                <?php 
                                $data = $item['data_decoded'];
                                if ($item['table_name'] == 'alat'): 
                                ?>
                                    <strong><?= esc($data['merk'] ?? 'N/A') ?> <?= esc($data['tipe'] ?? '') ?></strong><br>
                                    <small class="text-muted">ID: <?= $data['id_hp'] ?? 'N/A' ?></small>
                                <?php elseif ($item['table_name'] == 'users'): ?>
                                    <strong><?= esc($data['nama_user'] ?? 'N/A') ?></strong><br>
                                    <small class="text-muted">Email: <?= esc($data['email'] ?? 'N/A') ?></small>
                                <?php elseif ($item['table_name'] == 'peminjaman'): ?>
                                    <strong>Peminjaman ID: <?= $data['id_peminjaman'] ?? 'N/A' ?></strong><br>
                                    <small class="text-muted">User: <?= esc($data['nama_user'] ?? 'N/A') ?></small>
                                <?php else: ?>
                                    <small class="text-muted">ID: <?= $item['record_id'] ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small class="text-muted">Admin ID: <?= $item['deleted_by'] ?></small>
                            </td>
                            <td>
                                <small><?= date('d/m/Y H:i', strtotime($item['deleted_at'])) ?></small>
                            </td>
                            <td>
                                <small class="text-muted"><?= esc($item['reason'] ?: 'Tidak ada alasan') ?></small>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-success btn-sm" onclick="confirmRestore(<?= $item['id_trash'] ?>, '<?= $item['table_name'] ?>')" title="Pulihkan">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmPermanentDelete(<?= $item['id_trash'] ?>, '<?= $item['table_name'] ?>')" title="Hapus Permanen">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm" onclick="showDetails(<?= $item['id_trash'] ?>)" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-trash"></i>
                                    <h3>Trash Kosong</h3>
                                    <p>Tidak ada data yang dihapus</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Data Terhapus</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <pre id="detailContent"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmRestore(id, tableName) {
    if (confirm('Yakin ingin memulihkan data dari ' + tableName + '?')) {
        window.location.href = '<?= base_url('/admin/trash/restore/') ?>' + id;
    }
}

function confirmPermanentDelete(id, tableName) {
    if (confirm('PERINGATAN: Data akan dihapus PERMANEN dari ' + tableName + '!\n\nTindakan ini tidak dapat dibatalkan. Yakin ingin melanjutkan?')) {
        window.location.href = '<?= base_url('/admin/trash/permanent-delete/') ?>' + id;
    }
}

function confirmClearTrash() {
    if (confirm('PERINGATAN: Semua data di trash akan dihapus PERMANEN!\n\nTindakan ini tidak dapat dibatalkan. Yakin ingin mengosongkan trash?')) {
        window.location.href = '<?= base_url('/admin/trash/clear') ?>';
    }
}

function showDetails(id) {
    // Ambil data dari PHP dan tampilkan di modal
    <?php if (isset($trash)): ?>
    const trashData = <?= json_encode($trash) ?>;
    const item = trashData.find(t => t.id_trash == id);
    
    if (item) {
        document.getElementById('detailContent').textContent = JSON.stringify(item.data_decoded, null, 2);
        $('#detailModal').modal('show');
    }
    <?php endif; ?>
}
</script>
<?= $this->endSection() ?>