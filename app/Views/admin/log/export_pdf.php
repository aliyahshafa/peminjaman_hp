<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin-bottom: 5px; }
        .header p { color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        table th { background: #f8f9fa; font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1><?= $title ?></h1>
        <p>Dicetak pada: <?= $date ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu</th>
                <th>Nama User</th>
                <th>Role</th>
                <th>Aktivitas</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($logs as $log): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></td>
                <td><?= esc($log['nama_user']) ?></td>
                <td><?= esc($log['role']) ?></td>
                <td><?= esc($log['aktivitas']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Sistem Peminjaman Alat - Log Aktivitas</p>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
