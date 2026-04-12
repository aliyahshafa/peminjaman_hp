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
<div id="detailModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:8px; padding:24px; width:100%; max-width:600px; margin:auto; position:relative; top:50%; transform:translateY(-50%); max-height:80vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h4 style="margin:0;">Detail Data Terhapus</h4>
            <button type="button" onclick="document.getElementById('detailModal').style.display='none'" style="background:none; border:none; font-size:20px; cursor:pointer;">&times;</button>
        </div>
        <div id="detailContent" style="font-size:13px;"></div>
        <div style="text-align:right; margin-top:16px;">
            <button type="button" onclick="document.getElementById('detailModal').style.display='none'" class="btn btn-secondary">Tutup</button>
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
    <?php if (isset($trash)): ?>
    const trashData = <?= json_encode($trash) ?>;
    const item = trashData.find(t => t.id_trash == id);
    
    if (item) {
        // Coba ambil data_decoded, fallback ke parse data_backup langsung
        let data = item.data_decoded;
        if (!data && item.data_backup) {
            try { data = JSON.parse(item.data_backup); } catch(e) { data = null; }
        }

        let html = '<table style="width:100%; border-collapse:collapse; font-size:14px;">';

        // Info trash dulu
        const infoRows = [
            ['Tabel', item.table_name],
            ['Dihapus Oleh', 'Admin ID: ' + item.deleted_by],
            ['Waktu Hapus', item.deleted_at],
            ['Alasan', item.reason || '-'],
        ];
        infoRows.forEach(([k, v]) => {
            html += `<tr style="border-bottom:1px solid #eee; background:#e8f4fd;">
                <td style="padding:8px 12px; font-weight:bold; color:#0c5460; width:40%;">${k}</td>
                <td style="padding:8px 12px; color:#0c5460;">${v || '-'}</td>
            </tr>`;
        });

        // Separator
        html += `<tr><td colspan="2" style="padding:6px 12px; background:#f0f0f0; font-weight:bold; font-size:12px; color:#666;">DATA BACKUP</td></tr>`;

        // Isi data backup
        if (data && typeof data === 'object') {
            for (const key in data) {
                const val = (data[key] !== null && data[key] !== '') ? data[key] : '<span style="color:#999">-</span>';
                html += `<tr style="border-bottom:1px solid #eee;">
                    <td style="padding:8px 12px; font-weight:bold; color:#555; width:40%; background:#f8f9fa;">${key}</td>
                    <td style="padding:8px 12px;">${val}</td>
                </tr>`;
            }
        } else {
            html += `<tr><td colspan="2" style="padding:12px; color:#999; text-align:center;">Data tidak tersedia</td></tr>`;
        }

        html += '</table>';
        document.getElementById('detailContent').innerHTML = html;
        document.getElementById('detailModal').style.display = 'flex';
    }
    <?php endif; ?>
}
</script>
<?= $this->endSection() ?>