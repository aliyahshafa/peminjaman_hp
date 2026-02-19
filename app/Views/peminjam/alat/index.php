<!-- FILE UPDATED: <?= date('Y-m-d H:i:s') ?> -->
<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Daftar HP - TAMPILAN BARU</h2>
    <p class="page-subtitle">Pilih HP yang ingin Anda pinjam</p>
</div>

<!-- Search Box -->
<div class="card">
    <div class="card-body">
        <form method="get" action="<?= base_url('/peminjam/alat') ?>" class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" name="keyword" class="form-control" placeholder="Cari merk atau tipe HP..." value="<?= $keyword ?? '' ?>">
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>
    </div>
</div>

<!-- Grid HP Cards -->
<div class="row">
    <?php if (isset($alat) && count($alat) > 0): ?>
        <?php foreach ($alat as $item): ?>
        <div class="col-md-4 col-lg-3">
            <div class="card hp-card">
                <div class="card-body text-center">
                    <!-- Icon HP -->
                    <div class="hp-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    
                    <!-- Info HP -->
                    <h4 class="hp-merk"><?= esc($item['merk']) ?></h4>
                    <p class="hp-tipe"><?= esc($item['tipe']) ?></p>
                            <td><strong class="text-success">Rp <?= number_format($item['harga'] ?? 0, 0, ',', '.') ?></strong></td>
                    
                    <div class="hp-details">
                        <div class="detail-item">
                            <i class="fas fa-tag"></i>
                            <span><?= esc($item['nama_category'] ?? 'Uncategorized') ?></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-check-circle"></i>
                            <span class="badge badge-<?= $item['kondisi'] == 'Baik' ? 'success' : 'warning' ?>">
                                <?= esc($item['kondisi']) ?>
                            </span>
                        </div>
                        <div class="detail-item">
                            <?php if ($item['status'] == 'Tersedia'): ?>
                                <span class="badge badge-success">
                                    <i class="fas fa-check"></i> Tersedia
                                </span>
                            <?php else: ?>
                                <span class="badge badge-danger">
                                    <i class="fas fa-times"></i> Dipinjam
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Action Button -->
                    <?php if ($item['status'] == 'Tersedia'): ?>
                        <a href="<?= base_url('/peminjam/peminjaman/create/' . $item['id_hp']) ?>" class="btn btn-primary btn-block mt-3">
                            <i class="fas fa-handshake"></i> Ajukan Peminjaman
                        </a>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-block mt-3" disabled>
                            <i class="fas fa-ban"></i> Tidak Tersedia
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-mobile-alt" style="font-size: 64px; color: #cbd5e1; margin-bottom: 20px;"></i>
                    <h3 style="color: #64748b;">Tidak ada HP tersedia</h3>
                    <p style="color: #94a3b8;">Saat ini belum ada HP yang dapat dipinjam</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Back Button -->
<div class="mt-4">
    <a href="<?= base_url('/dashboard') ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.hp-card {
    transition: transform 0.3s, box-shadow 0.3s;
    height: 100%;
    margin-bottom: 20px;
}

.hp-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.hp-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.hp-icon i {
    font-size: 40px;
    color: white;
}

.hp-merk {
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 5px;
}

.hp-tipe {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 15px;
}

.hp-details {
    background: #f8fafc;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 10px;
}

.detail-item {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 8px;
    font-size: 14px;
    color: #475569;
}

.detail-item:last-child {
    margin-bottom: 0;
}

.detail-item i {
    color: #667eea;
}

.search-box {
    display: flex;
    align-items: center;
    gap: 10px;
    position: relative;
}

.search-box i {
    position: absolute;
    left: 15px;
    color: #94a3b8;
    z-index: 1;
}

.search-box input {
    flex: 1;
    padding-left: 45px;
}

.row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -10px;
}

.col-md-4, .col-lg-3, .col-12 {
    padding: 0 10px;
}

.col-12 {
    width: 100%;
}

.col-md-4 {
    width: 33.333%;
}

.col-lg-3 {
    width: 25%;
}

@media (max-width: 992px) {
    .col-lg-3 {
        width: 33.333%;
    }
}

@media (max-width: 768px) {
    .col-md-4, .col-lg-3 {
        width: 50%;
    }
}

@media (max-width: 576px) {
    .col-md-4, .col-lg-3 {
        width: 100%;
    }
}

.btn-block {
    width: 100%;
    display: block;
}

.mt-3 {
    margin-top: 15px;
}

.mt-4 {
    margin-top: 20px;
}

.py-5 {
    padding-top: 3rem;
    padding-bottom: 3rem;
}

.text-center {
    text-align: center;
}
</style>
<?= $this->endSection() ?>
