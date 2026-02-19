<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Profil Saya</h2>
    <p class="page-subtitle">Informasi akun Anda</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user"></i> Informasi Profil</h3>
        <a href="<?= base_url('/profile/edit') ?>" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Profil
        </a>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" value="<?= esc($user['nama_user'] ?? '') ?>" readonly>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" value="<?= esc($user['email'] ?? '') ?>" readonly>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Role</label>
                <input type="text" class="form-control" value="<?= esc($user['role'] ?? '') ?>" readonly>
            </div>
            
            <div class="form-group">
                <label class="form-label">No. HP</label>
                <input type="text" class="form-control" value="<?= esc($user['no_hp'] ?? '-') ?>" readonly>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Alamat</label>
            <textarea class="form-control" rows="3" readonly><?= esc($user['alamat'] ?? '-') ?></textarea>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
