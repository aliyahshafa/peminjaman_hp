<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Kelola User</h2>
    <p class="page-subtitle">Manajemen data pengguna sistem</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-users"></i> Daftar User</h3>
        <a href="<?= base_url('/admin/user/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah User
        </a>
    </div>
    <div class="card-body">
        <!-- Search Box -->
        <div class="search-box">
            <form method="get" action="<?= base_url('/admin/user') ?>" style="display:flex; gap:8px; align-items:center;">
                <i class="fas fa-search"></i>
                <input type="text" name="keyword" class="form-control" placeholder="Cari nama atau email..." value="<?= $keyword ?? '' ?>">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
                <a href="<?= base_url('/admin/user') ?>" class="btn btn-secondary btn-sm"><i class="fas fa-times"></i> Reset</a>
            </form>
        </div>
        
        <!-- Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($users) > 0): ?>
                        <?php $no = 1; foreach ($users as $user): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($user['nama_user']) ?></td>
                            <td><?= esc($user['email']) ?></td>
                            <td>
                                <?php if ($user['role'] == 'Admin'): ?>
                                    <span class="badge badge-danger"><?= $user['role'] ?></span>
                                <?php elseif ($user['role'] == 'Petugas'): ?>
                                    <span class="badge badge-warning"><?= $user['role'] ?></span>
                                <?php else: ?>
                                    <span class="badge badge-success"><?= $user['role'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($user['no_hp'] ?? '-') ?></td>
                            <td><?= esc($user['alamat'] ?? '-') ?></td>
                            <td>
                                <a href="<?= base_url('/admin/user/edit/' . $user['id_user']) ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('/admin/user/delete/' . $user['id_user']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus user ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-users"></i>
                                    <h3>Tidak ada data user</h3>
                                    <p>Belum ada user yang terdaftar dalam sistem</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
