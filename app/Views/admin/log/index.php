<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1400px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-bottom: 20px; }
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 14px; }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn:hover { opacity: 0.9; }
        .filter-box { background: #f8f9fa; padding: 20px; border-radius: 4px; margin-bottom: 20px; }
        .filter-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group label { margin-bottom: 5px; font-weight: bold; font-size: 14px; }
        .form-group input, .form-group select { padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px; }
        .stat-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px; text-align: center; }
        .stat-card h3 { font-size: 32px; margin-bottom: 5px; }
        .stat-card p { font-size: 14px; opacity: 0.9; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        table th { background: #f8f9fa; font-weight: bold; color: #333; }
        table tr:hover { background: #f8f9fa; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-admin { background: #dc3545; color: white; }
        .badge-petugas { background: #ffc107; color: #333; }
        .badge-peminjam { background: #28a745; color: white; }
        .pagination { display: flex; justify-content: center; gap: 5px; margin-top: 20px; }
        .pagination a { padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: #333; }
        .pagination a.active { background: #007bff; color: white; border-color: #007bff; }
        .alert { padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .no-data { text-align: center; padding: 40px; color: #999; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-actions">
            <h2>📋 Log Aktivitas Sistem</h2>
            <div>
                <a href="<?= base_url('/admin/log/stats') ?>" class="btn btn-primary">📊 Statistik</a>
                <a href="<?= base_url('/admin/log/export?format=csv') ?>" class="btn btn-success">📥 Export CSV</a>
                <a href="<?= base_url('/admin/log/clear') ?>" class="btn btn-danger">🗑️ Bersihkan Log</a>
                <a href="<?= base_url('/admin') ?>" class="btn btn-secondary">← Kembali</a>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <!-- Filter -->
        <div class="filter-box">
            <h3 style="margin-bottom: 15px;">🔍 Filter Log</h3>
            <form method="get" action="<?= base_url('/admin/log') ?>">
                <div class="filter-row">
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role">
                            <option value="">Semua Role</option>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= $r['role'] ?>" <?= $filters['role'] == $r['role'] ? 'selected' : '' ?>>
                                    <?= $r['role'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>User</label>
                        <select name="user">
                            <option value="">Semua User</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id_user'] ?>" <?= $filters['user'] == $u['id_user'] ? 'selected' : '' ?>>
                                    <?= $u['nama_user'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Dari Tanggal</label>
                        <input type="date" name="date_from" value="<?= $filters['date_from'] ?>">
                    </div>
                    <div class="form-group">
                        <label>Sampai Tanggal</label>
                        <input type="date" name="date_to" value="<?= $filters['date_to'] ?>">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Cari Aktivitas</label>
                    <input type="text" name="search" placeholder="Cari aktivitas..." value="<?= $filters['search'] ?>">
                </div>
                <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                <a href="<?= base_url('/admin/log') ?>" class="btn btn-secondary">Reset</a>
            </form>
        </div>

        <!-- Table -->
        <?php if (count($logs) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Aktivitas</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1 + (($pager->getCurrentPage() - 1) * $pager->getPerPage());
                    foreach ($logs as $log): 
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?></td>
                        <td><?= esc($log['nama_user']) ?></td>
                        <td>
                            <span class="badge badge-<?= strtolower($log['role']) ?>">
                                <?= esc($log['role']) ?>
                            </span>
                        </td>
                        <td><?= esc($log['aktivitas']) ?></td>
                        <td><?= esc($log['ip_address'] ?? '-') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination">
                <?= $pager->links() ?>
            </div>
        <?php else: ?>
            <div class="no-data">
                <p>Tidak ada log aktivitas yang ditemukan</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
