<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Edit Pengembalian</h2>
    <p class="page-subtitle">Perbarui data pengembalian HP</p>
</div>

<div class="card">
    <div class="card-body">
        <div class="alert alert-info">
            <strong>Peminjam:</strong> <?= esc($pengembalian['nama_user'] ?? 'N/A') ?> |
            <strong>HP:</strong> <?= esc($pengembalian['merk'] ?? '') ?> <?= esc($pengembalian['tipe'] ?? '') ?>
        </div>

        <form action="<?= base_url('/admin/pengembalian/update/' . $pengembalian['id_peminjaman']) ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label required">Kondisi HP</label>
                <select name="kondisi_hp" class="form-control" required>
                    <?php
                    $kondisiSaat = strtolower(trim($pengembalian['kondisi_hp'] ?? ''));
                    $options = ['Baik', 'Rusak Ringan', 'Rusak Berat'];
                    foreach ($options as $opt):
                        $selected = strtolower($opt) == $kondisiSaat ? 'selected' : '';
                    ?>
                        <option value="<?= $opt ?>" <?= $selected ?>><?= $opt ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Denda (Rp)</label>
                <input type="number" name="denda" class="form-control" value="<?= $pengembalian['denda'] ?? 0 ?>" min="0">
            </div>

            <div class="form-group">
                <label class="form-label required">Tanggal Dikembalikan</label>
                <input type="date" name="tanggal_kembali" class="form-control"
                    value="<?= $pengembalian['tanggal_kembali'] ?? date('Y-m-d') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label required">Status</label>
                <select name="status" class="form-control" required>
                    <option value="Menunggu Pengembalian" <?= $pengembalian['status'] == 'Menunggu Pengembalian' ? 'selected' : '' ?>>Menunggu Verifikasi</option>
                    <option value="Dikembalikan" <?= $pengembalian['status'] == 'Dikembalikan' ? 'selected' : '' ?>>Dikembalikan</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                <a href="<?= base_url('/admin/pengembalian') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
