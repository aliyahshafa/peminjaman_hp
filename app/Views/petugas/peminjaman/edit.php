<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Edit Peminjaman</h2>
    <p class="page-subtitle">Update data peminjaman dan kondisi HP</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit"></i> Form Edit Peminjaman</h3>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('/petugas/peminjaman/update/' . $peminjaman['id_peminjaman']) ?>">
            <?= csrf_field() ?>
            
            <!-- Info Peminjaman -->
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Informasi Peminjaman</strong><br>
                Peminjam: <?= esc($peminjaman['nama_user'] ?? 'N/A') ?><br>
                HP: <?= esc($peminjaman['merk'] ?? 'N/A') ?> - <?= esc($peminjaman['tipe'] ?? 'N/A') ?>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Tanggal Peminjaman</label>
                    <input type="date" name="waktu" class="form-control" value="<?= date('Y-m-d', strtotime($peminjaman['waktu'])) ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="Diajukan" <?= $peminjaman['status'] == 'Diajukan' ? 'selected' : '' ?>>Diajukan</option>
                        <option value="Disetujui" <?= $peminjaman['status'] == 'Disetujui' ? 'selected' : '' ?>>Disetujui</option>
                        <option value="Menunggu Pengembalian" <?= $peminjaman['status'] == 'Menunggu Pengembalian' ? 'selected' : '' ?>>Menunggu Pengembalian</option>
                        <option value="Dikembalikan" <?= $peminjaman['status'] == 'Dikembalikan' ? 'selected' : '' ?>>Dikembalikan</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label required">Kondisi HP</label>
                <select name="kondisi_hp" class="form-control" id="kondisiHp" required onchange="hitungDenda()">
                    <option value="baik" <?= ($peminjaman['kondisi_hp'] ?? 'baik') == 'baik' ? 'selected' : '' ?>>Baik</option>
                    <option value="rusak ringan" <?= ($peminjaman['kondisi_hp'] ?? '') == 'rusak ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
                    <option value="rusak berat" <?= ($peminjaman['kondisi_hp'] ?? '') == 'rusak berat' ? 'selected' : '' ?>>Rusak Berat</option>
                </select>
                <small class="form-text text-muted">Pilih kondisi HP saat dikembalikan untuk menghitung denda otomatis</small>
            </div>
            
            <div class="alert alert-warning" id="dendaInfo">
                <i class="fas fa-money-bill"></i>
                <strong>Denda:</strong> <span id="dendaText">Rp 0</span>
                <input type="hidden" name="denda" id="dendaValue" value="0">
            </div>
            
            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control" rows="4" placeholder="Catatan tambahan (opsional)"><?= esc($peminjaman['catatan'] ?? '') ?></textarea>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="<?= base_url('/petugas/peminjaman') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function hitungDenda() {
    const kondisi = document.getElementById('kondisiHp').value.toLowerCase().trim();
    let denda = 0;
    
    console.log('Kondisi HP dipilih:', kondisi); // Debug log
    
    if (kondisi === 'rusak ringan') {
        denda = 10000;
    } else if (kondisi === 'rusak berat') {
        denda = 20000;
    }
    
    console.log('Denda dihitung:', denda); // Debug log
    
    document.getElementById('dendaText').textContent = 'Rp ' + denda.toLocaleString('id-ID');
    document.getElementById('dendaValue').value = denda;
    
    // Update alert color
    const alert = document.getElementById('dendaInfo');
    if (denda > 0) {
        alert.className = 'alert alert-danger';
    } else {
        alert.className = 'alert alert-success';
    }
}

// Hitung denda saat halaman load
window.onload = function() {
    hitungDenda();
};
</script>
<?= $this->endSection() ?>
