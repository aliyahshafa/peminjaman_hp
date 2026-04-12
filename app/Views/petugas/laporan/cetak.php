<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman Alat</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        table th { background: #f8f9fa; font-weight: bold; }
        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PEMINJAMAN ALAT</h1>
        <p>Sistem Peminjaman Alat Laboratorium</p>
        <?php if (isset($date_from) && $date_from && isset($date_to) && $date_to): ?>
        <p>Periode: <?= date('d/m/Y', strtotime($date_from)) ?> - <?= date('d/m/Y', strtotime($date_to)) ?></p>
        <?php endif; ?>
        <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Pinjam</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($peminjaman) && count($peminjaman) > 0): ?>
                <?php $no = 1; foreach ($peminjaman as $p): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <?php
                        $tsW = strtotime($p['waktu'] ?? '');
                        echo ($tsW && $tsW > 86400) ? date('d/m/Y', $tsW) : date('d/m/Y');
                        ?>
                    </td>
                    <td><?= esc($p['nama_user'] ?? 'N/A') ?></td>
                    <td><?= isset($p['merk']) ? esc($p['merk'] . ' ' . $p['tipe']) : 'N/A' ?></td>
                    <td><?= $p['tanggal_kembali'] ? date('d/m/Y', strtotime($p['tanggal_kembali'])) : '-' ?></td>
                    <td><?= esc($p['status']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Sistem Peminjaman Alat - Laporan Peminjaman</p>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
