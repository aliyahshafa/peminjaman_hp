<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Tambah User Baru</h2>
    <p class="page-subtitle">Lengkapi form di bawah untuk menambahkan user baru</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-plus"></i> Form Tambah User</h3>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('/admin/user/store') ?>">
            <?= csrf_field() ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Nama Lengkap</label>
                    <input type="text" name="nama_user" class="form-control" placeholder="Masukkan nama lengkap" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="contoh@email.com" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required minlength="6">
                    <small class="form-text">Password minimal 6 karakter</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Role</label>
                    <select name="role" class="form-control" required>
                        <option value="">Pilih Role</option>
                        <option value="Admin">Admin</option>
                        <option value="Petugas">Petugas</option>
                        <option value="Peminjam">Peminjam</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="<?= base_url('/admin/user') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
