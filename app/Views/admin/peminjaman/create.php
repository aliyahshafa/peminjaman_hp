<?php echo $this->extend('layouts/template'); ?>

<?php echo $this->section('content'); ?>

<div class="page-header">
    <h2 class="page-title">Tambah Peminjaman</h2>
    <p class="page-subtitle">Form untuk menambah data peminjaman baru</p>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('/admin/peminjaman/store') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="id_user">Peminjam <span class="text-danger">*</span></label>
                <select name="id_user" id="id_user" class="form-control" required>
                    <option value="">-- Pilih Peminjam --</option>
                    <?php foreach ($users as $user): ?>
                        <?php if ($user['role'] === 'Peminjam'): ?>
                            <option value="<?= $user['id_user'] ?>">
                                <?= esc($user['nama_user']) ?> (<?= esc($user['email']) ?>)
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_hp">HP <span class="text-danger">*</span></label>
                <select name="id_hp" id="id_hp" class="form-control" required>
                    <option value="">-- Pilih HP --</option>
                    <?php foreach ($alat as $hp): ?>
                        <?php if ($hp['status'] === 'Tersedia'): ?>
                            <option value="<?= $hp['id_hp'] ?>" data-harga="<?= $hp['harga'] ?>">
                                <?= esc($hp['merk']) ?> <?= esc($hp['tipe']) ?> - Rp <?= number_format($hp['harga'], 0, ',', '.') ?>/hari
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="waktu">Tanggal Pinjam <span class="text-danger">*</span></label>
                <input type="date" name="waktu" id="waktu" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="form-group">
                <label for="durasi">Durasi (hari) <span class="text-danger">*</span></label>
                <input type="number" name="durasi" id="durasi" class="form-control" min="1" value="1" required>
                <small class="form-text text-muted">Masukkan jumlah hari peminjaman</small>
            </div>

            <div class="form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-control" required>
                    <option value="Diajukan">Diajukan</option>
                    <option value="Disetujui">Disetujui</option>
                    <option value="Ditolak">Ditolak</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="<?= base_url('/admin/peminjaman') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?php echo $this->endSection(); ?>
