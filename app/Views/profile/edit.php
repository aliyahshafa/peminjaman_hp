<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Edit Profil</h2>
    <p class="page-subtitle">Perbarui informasi profil Anda</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-edit"></i> Form Edit Profil</h3>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('/profile/update') ?>">
            <?= csrf_field() ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Nama Lengkap</label>
                    <input type="text" name="nama_user" class="form-control" value="<?= esc($user['nama_user'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= esc($user['email'] ?? '') ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah" minlength="6">
                    <small class="form-text">Kosongkan jika tidak ingin mengubah password</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" value="<?= esc($user['no_hp'] ?? '') ?>" placeholder="08xxxxxxxxxx">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap"><?= esc($user['alamat'] ?? '') ?></textarea>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="<?= base_url('/profile') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
