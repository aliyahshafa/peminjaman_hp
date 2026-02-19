<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bersihkan Log</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 50px auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-bottom: 20px; text-align: center; }
        .warning-box { background: #fff3cd; border: 1px solid #ffc107; padding: 20px; border-radius: 4px; margin-bottom: 30px; }
        .warning-box h3 { color: #856404; margin-bottom: 10px; }
        .warning-box p { color: #856404; line-height: 1.6; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; }
        .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; }
        .btn-group { display: flex; gap: 10px; justify-content: center; }
        .btn { padding: 12px 30px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; text-decoration: none; display: inline-block; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn:hover { opacity: 0.9; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🗑️ Bersihkan Log Lama</h2>
        
        <div class="warning-box">
            <h3>⚠️ Peringatan</h3>
            <p>Tindakan ini akan menghapus log aktivitas yang lebih lama dari periode yang dipilih. Data yang sudah dihapus tidak dapat dikembalikan.</p>
        </div>

        <form method="post" action="<?= base_url('/admin/log/clear') ?>" onsubmit="return confirm('Apakah Anda yakin ingin menghapus log lama? Tindakan ini tidak dapat dibatalkan.')">
            <div class="form-group">
                <label>Hapus log lebih dari:</label>
                <select name="days" required>
                    <option value="7">7 hari</option>
                    <option value="14">14 hari</option>
                    <option value="30" selected>30 hari (1 bulan)</option>
                    <option value="60">60 hari (2 bulan)</option>
                    <option value="90">90 hari (3 bulan)</option>
                    <option value="180">180 hari (6 bulan)</option>
                    <option value="365">365 hari (1 tahun)</option>
                </select>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-danger">Hapus Log Lama</button>
                <a href="<?= base_url('/admin/log') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
