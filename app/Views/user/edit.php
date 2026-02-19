<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Edit User</h2>
    <p class="page-subtitle">Perbarui informasi user</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-edit"></i> Form Edit User</h3>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('/admin/user/update/' . $user['id_user']) ?>">
            <?= csrf_field() ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Nama Lengkap</label>
                    <input type="text" name="nama_user" class="form-control" value="<?= esc($user['nama_user']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= esc($user['email']) ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password" minlength="6">
                    <small class="form-text">Kosongkan jika tidak ingin mengubah password</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Role</label>
                    <select name="role" class="form-control" required>
                        <option value="Admin" <?= $user['role'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="Petugas" <?= $user['role'] == 'Petugas' ? 'selected' : '' ?>>Petugas</option>
                        <option value="Peminjam" <?= $user['role'] == 'Peminjam' ? 'selected' : '' ?>>Peminjam</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" value="<?= esc($user['no_hp'] ?? '') ?>" placeholder="08xxxxxxxxxx">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap"><?= esc($user['alamat'] ?? '') ?></textarea>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="<?= base_url('/admin/user') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
