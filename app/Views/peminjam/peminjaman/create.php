<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Ajukan Peminjaman</h2>
    <p class="page-subtitle">Lengkapi form untuk mengajukan peminjaman HP</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-handshake"></i> Form Peminjaman</h3>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('/peminjam/peminjaman/store') ?>">
            <?= csrf_field() ?>
            
            <!-- Info HP -->
            <?php if (isset($alat)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>HP yang akan dipinjam:</strong><br>
                Merk: <?= esc($alat['merk']) ?><br>
                Tipe: <?= esc($alat['tipe']) ?><br>
                Kondisi: <?= esc($alat['kondisi']) ?><br>
                Status: <?= esc($alat['status']) ?>
            </div>
            
            <input type="hidden" name="id_hp" value="<?= $alat['id_hp'] ?>">
            <?php endif; ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control" value="<?= date('Y-m-d') ?>" required readonly>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Durasi Peminjaman</label>
                    <select name="waktu" id="durasi" class="form-control" required>
                        <option value="">Pilih Durasi</option>
                        <option value="1">1 Hari</option>
                        <option value="2">2 Hari</option>
                        <option value="3">3 Hari</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Pengembalian</label>
                    <input type="text" id="tanggal_kembali_display" class="form-control" placeholder="Otomatis sesuai durasi" readonly>
                    <input type="hidden" name="tanggal_kembali" id="tanggal_kembali">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Ajukan Peminjaman
                </button>
                <a href="<?= base_url('/peminjam/alat') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('durasi').addEventListener('change', function () {
    var durasi = parseInt(this.value);
    var tanggalPinjam = document.getElementById('tanggal_pinjam').value;

    if (durasi && tanggalPinjam) {
        var tgl = new Date(tanggalPinjam);
        tgl.setDate(tgl.getDate() + durasi);

        var yyyy = tgl.getFullYear();
        var mm   = String(tgl.getMonth() + 1).padStart(2, '0');
        var dd   = String(tgl.getDate()).padStart(2, '0');
        var formatted = yyyy + '-' + mm + '-' + dd;
        var display = dd + '/' + mm + '/' + yyyy;

        document.getElementById('tanggal_kembali').value         = formatted;
        document.getElementById('tanggal_kembali_display').value = display;
    } else {
        document.getElementById('tanggal_kembali').value         = '';
        document.getElementById('tanggal_kembali_display').value = '';
    }
});
</script>

<?= $this->endSection() ?>
