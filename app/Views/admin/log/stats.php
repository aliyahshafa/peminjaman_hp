<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Log Aktivitas</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        h2 { color: #333; margin-bottom: 20px; }
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn:hover { opacity: 0.9; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; }
        .stat-card.primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .stat-card.success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; }
        .stat-card h3 { font-size: 42px; margin-bottom: 10px; }
        .stat-card p { font-size: 16px; opacity: 0.9; }
        .chart-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .chart-container h3 { margin-bottom: 20px; color: #333; }
        .bar-chart { display: flex; flex-direction: column; gap: 15px; }
        .bar-item { display: flex; align-items: center; gap: 15px; }
        .bar-label { min-width: 120px; font-weight: bold; }
        .bar-wrapper { flex: 1; background: #e9ecef; border-radius: 4px; height: 30px; position: relative; }
        .bar-fill { background: linear-gradient(90deg, #667eea 0%, #764ba2 100%); height: 100%; border-radius: 4px; display: flex; align-items: center; justify-content: flex-end; padding-right: 10px; color: white; font-weight: bold; }
        .table-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        table th { background: #f8f9fa; font-weight: bold; }
        table tr:hover { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-actions">
            <h2>📊 Statistik Log Aktivitas</h2>
            <a href="<?= base_url('/admin/log') ?>" class="btn btn-secondary">← Kembali ke Log</a>
        </div>

        <!-- Summary Stats -->
        <div class="stats-grid">
            <div class="stat-card primary">
                <h3><?= number_format($total_logs) ?></h3>
                <p>Total Log Aktivitas</p>
            </div>
            <div class="stat-card success">
                <h3><?= number_format($today_logs) ?></h3>
                <p>Aktivitas Hari Ini</p>
            </div>
        </div>

        <!-- Aktivitas per Role -->
        <div class="chart-container">
            <h3>Aktivitas per Role</h3>
            <div class="bar-chart">
                <?php 
                $maxRole = max(array_column($by_role, 'total'));
                foreach ($by_role as $role): 
                    $percentage = ($role['total'] / $maxRole) * 100;
                ?>
                <div class="bar-item">
                    <div class="bar-label"><?= esc($role['role']) ?></div>
                    <div class="bar-wrapper">
                        <div class="bar-fill" style="width: <?= $percentage ?>%">
                            <?= number_format($role['total']) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Top 10 Users -->
        <div class="table-container">
            <h3>Top 10 User Paling Aktif</h3>
            <table>
                <thead>
                    <tr>
                        <th>Ranking</th>
                        <th>Nama User</th>
                        <th>Total Aktivitas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1;
                    foreach ($top_users as $user): 
                    ?>
                    <tr>
                        <td><?= $rank++ ?></td>
                        <td><?= esc($user['nama_user']) ?></td>
                        <td><?= number_format($user['total']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Aktivitas 7 Hari Terakhir -->
        <div class="chart-container">
            <h3>Aktivitas 7 Hari Terakhir</h3>
            <div class="bar-chart">
                <?php 
                $maxDay = max(array_column($last_7_days, 'count'));
                foreach ($last_7_days as $day): 
                    $percentage = $maxDay > 0 ? ($day['count'] / $maxDay) * 100 : 0;
                    $dayName = date('D, d M', strtotime($day['date']));
                ?>
                <div class="bar-item">
                    <div class="bar-label"><?= $dayName ?></div>
                    <div class="bar-wrapper">
                        <div class="bar-fill" style="width: <?= $percentage ?>%">
                            <?= number_format($day['count']) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
