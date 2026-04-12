<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Tambah Pengembalian</h2>
    <p class="page-subtitle">Form untuk mencatat pengembalian HP</p>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('/admin/pengembalian/store') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label required">Peminjaman</label>
                <select name="id_peminjaman" class="form-control" required>
                    <option value="">-- Pilih Peminjaman --</option>
                    <?php foreach ($peminjaman as $p): ?>
                        <option value="<?= $p['id_peminjaman'] ?>">
                            #<?= $p['id_peminjaman'] ?> - <?= esc($p['nama_user']) ?> | <?= esc($p['merk']) ?> <?= esc($p['tipe']) ?> (<?= $p['status'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label required">Kondisi HP</label>
                <select name="kondisi_hp" class="form-control" required>
                    <option value="Baik">Baik</option>
                    <option value="Rusak Ringan">Rusak Ringan</option>
                    <option value="Rusak Berat">Rusak Berat</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Denda (Rp)</label>
                <input type="number" name="denda" class="form-control" value="0" min="0">
            </div>

            <div class="form-group">
                <label class="form-label required">Tanggal Dikembalikan</label>
                <input type="date" name="tanggal_kembali" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="<?= base_url('/admin/pengembalian') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
