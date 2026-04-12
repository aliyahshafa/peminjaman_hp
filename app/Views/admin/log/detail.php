<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Log Aktivitas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-bottom: 24px; }
        .btn { padding: 8px 18px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 14px; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn:hover { opacity: 0.9; }
        .detail-table { width: 100%; border-collapse: collapse; }
        .detail-table tr { border-bottom: 1px solid #eee; }
        .detail-table td { padding: 14px 12px; font-size: 15px; }
        .detail-table td:first-child { width: 180px; font-weight: bold; color: #555; }
        .badge { padding: 4px 10px; border-radius: 4px; font-size: 13px; font-weight: bold; }
        .badge-admin { background: #dc3545; color: white; }
        .badge-petugas { background: #ffc107; color: #333; }
        .badge-peminjam { background: #28a745; color: white; }
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-actions">
            <h2><i class="fas fa-info-circle"></i> Detail Log Aktivitas</h2>
            <a href="<?= base_url('/admin/log') ?>" class="btn btn-secondary">← Kembali</a>
        </div>

        <table class="detail-table">
            <tr>
                <td>ID Log</td>
                <td>#<?= esc($log['id_log']) ?></td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td><?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?></td>
            </tr>
            <tr>
                <td>Nama User</td>
                <td><?= esc($log['nama_user']) ?></td>
            </tr>
            <tr>
                <td>Role</td>
                <td>
                    <span class="badge badge-<?= strtolower($log['role']) ?>">
                        <?= esc($log['role']) ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Aktivitas</td>
                <td><?= esc($log['aktivitas']) ?></td>
            </tr>
            <tr>
                <td>IP Address</td>
                <td><?= esc($log['ip_address'] ?? '-') ?></td>
            </tr>
        </table>
    </div>
</body>
</html>
