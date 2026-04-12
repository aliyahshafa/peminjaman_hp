<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Detail Peminjaman #<?= $peminjaman['id_peminjaman'] ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 4px 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table th, table td { padding: 10px 12px; border: 1px solid #ddd; text-align: left; }
        table th { background: #f0f0f0; width: 200px; font-weight: bold; }
        .footer { margin-top: 30px; text-align: right; font-size: 11px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <h1>DETAIL LAPORAN PEMINJAMAN</h1>
        <p>Sistem Peminjaman HP</p>
        <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
    </div>

    <table>
        <tr><th>ID Peminjaman</th><td>#<?= $peminjaman['id_peminjaman'] ?></td></tr>
        <tr><th>Peminjam</th><td><?= esc($peminjaman['nama_user'] ?? 'N/A') ?></td></tr>
        <tr><th>HP / Alat</th><td><?= esc(($peminjaman['merk'] ?? '') . ' ' . ($peminjaman['tipe'] ?? '')) ?: 'N/A' ?></td></tr>
        <tr><th>Harga Sewa/Hari</th><td>Rp <?= number_format($peminjaman['harga'] ?? 0, 0, ',', '.') ?></td></tr>
        <tr><th>Durasi</th><td><?= intval($peminjaman['waktu'] ?? 1) ?: 1 ?> hari</td></tr>
        <tr><th>Total Bayar</th><td>Rp <?= number_format(($peminjaman['harga'] ?? 0) * (intval($peminjaman['waktu'] ?? 1) ?: 1), 0, ',', '.') ?></td></tr>
        <tr><th>Tanggal Pinjam</th><td>
            <?php
            if (!empty($peminjaman['tanggal_pinjam']) && $peminjaman['tanggal_pinjam'] != '0000-00-00') {
                echo date('d/m/Y', strtotime($peminjaman['tanggal_pinjam']));
            } else { echo '-'; }
            ?>
        </td></tr>
        <tr><th>Tanggal Kembali</th><td><?= $peminjaman['tanggal_kembali'] ? date('d/m/Y', strtotime($peminjaman['tanggal_kembali'])) : '-' ?></td></tr>
        <tr><th>Kondisi HP Saat Kembali</th><td><?= esc($peminjaman['kondisi_hp'] ?? '-') ?></td></tr>
        <tr><th>Denda</th><td>Rp <?= number_format($peminjaman['denda'] ?? 0, 0, ',', '.') ?></td></tr>
        <tr><th>Status</th><td><?= esc($peminjaman['status']) ?></td></tr>
        <tr><th>Catatan</th><td><?= esc($peminjaman['catatan'] ?? '-') ?></td></tr>
    </table>

    <div class="footer">Sistem Peminjaman HP - Laporan Detail</div>

    <script>window.print();</script>
</body>
</html>
