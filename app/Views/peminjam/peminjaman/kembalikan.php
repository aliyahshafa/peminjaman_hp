<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Ajukan Pengembalian</h2>
    <p class="page-subtitle">Lengkapi form untuk mengajukan pengembalian HP</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-undo"></i> Form Pengembalian</h3>
    </div>
    <div class="card-body">
        <?php if (isset($peminjaman)): ?>
        <!-- Info Peminjaman - PERMANEN, TIDAK BISA HILANG -->
        <div id="infoPeminjaman" style="display: block; margin-bottom: 20px;">
            <div class="card" style="background-color: #d1ecf1; border: 1px solid #bee5eb;">
                <div class="card-header" style="background-color: #bee5eb;">
                    <h5 class="card-title" style="margin: 0; color: #0c5460;">
                        <i class="fas fa-info-circle"></i> Informasi Peminjaman
                    </h5>
                </div>
                <div class="card-body">
                    <table style="width: 100%; font-size: 15px; line-height: 2;">
                        <tr>
                            <td style="width: 200px;"><strong>HP:</strong></td>
                            <td><?= esc($peminjaman['merk']) ?> - <?= esc($peminjaman['tipe']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Harga Sewa per Hari:</strong></td>
                            <td><strong class="text-success" style="font-size: 1.2em;">Rp <?= number_format($peminjaman['harga'] ?? 0, 0, ',', '.') ?></strong></td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Pinjam:</strong></td>
                            <td><?= ($ts = strtotime($peminjaman['waktu'])) && $ts > 0 ? date('d/m/Y', $ts) : date('d/m/Y') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Kembali:</strong></td>
                            <td><?= $peminjaman['tanggal_kembali'] ? date('d/m/Y', strtotime($peminjaman['tanggal_kembali'])) : date('d/m/Y') ?></td>
                        </tr>
                        <?php
                        // Hitung durasi dari tanggal_kembali yang tersimpan saat peminjaman diajukan
                        $tsP = strtotime($peminjaman['waktu']);
                        $tanggalPinjam = new DateTime(($tsP && $tsP > 0) ? date('Y-m-d', $tsP) : date('Y-m-d'));
                        $kembaliStr = $peminjaman['tanggal_kembali'] ? date('Y-m-d', strtotime($peminjaman['tanggal_kembali'])) : date('Y-m-d');
                        $tanggalKembali = new DateTime($kembaliStr);
                        $durasi = $tanggalPinjam->diff($tanggalKembali)->days;
                        $durasi = max(1, $durasi);
                        if ($durasi > 3) {
                            $durasi = 3;
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Hidden input untuk data perhitungan -->
        <input type="hidden" id="hargaPerHari" value="<?= $peminjaman['harga'] ?? 0 ?>">
        <input type="hidden" id="durasiHari" value="<?= $durasi ?>">
        
        <form method="post" action="<?= base_url('/peminjam/peminjaman/kembalikan/' . $peminjaman['id_peminjaman']) ?>">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label class="form-label required">Kondisi HP Saat Dikembalikan</label>
                <select name="kondisi_hp" class="form-control" id="kondisiHp" required onchange="hitungDenda()">
                    <option value="">Pilih Kondisi HP</option>
                    <option value="baik">Baik (Tidak ada kerusakan)</option>
                    <option value="rusak ringan">Rusak Ringan (Kerusakan kecil)</option>
                    <option value="rusak berat">Rusak Berat (Kerusakan parah)</option>
                </select>
                <small class="form-text text-muted">Pilih kondisi HP dengan jujur untuk perhitungan denda yang tepat</small>
            </div>
            
            <!-- Hidden input untuk denda (backup) -->
            <input type="hidden" name="denda_calculated" id="dendaCalculated" value="0">
            
            <!-- Info Pembayaran -->
            <div class="alert alert-success" id="biayaSewaInfo" style="display: block;">
                <i class="fas fa-money-bill"></i>
                <strong>Biaya Sewa:</strong> <span id="biayaSewaText">Rp <?= number_format(($peminjaman['harga'] ?? 0) * $durasi, 0, ',', '.') ?></span>
                <br><small>(Rp <?= number_format($peminjaman['harga'] ?? 0, 0, ',', '.') ?> × <?= $durasi ?> hari)</small>
            </div>
            
            <div class="alert alert-warning" id="dendaInfo" style="display: none;">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Denda Kerusakan:</strong> <span id="dendaText">Rp 0</span>
            </div>
            
            <div class="alert alert-primary" id="totalInfo" style="display: block; font-size: 1.1em;">
                <i class="fas fa-calculator"></i>
                <strong>TOTAL PEMBAYARAN:</strong> <strong><span id="totalText">Rp <?= number_format(($peminjaman['harga'] ?? 0) * $durasi, 0, ',', '.') ?></span></strong>
            </div>
            
            <!-- Form Pembayaran (selalu muncul karena ada biaya sewa) -->
            <div id="formPembayaran" style="display: block;">
                <div class="card" style="background-color: #d1ecf1; border: 1px solid #bee5eb;">
                    <div class="card-header" style="background-color: #bee5eb;">
                        <h5 class="card-title" style="margin: 0; color: #0c5460;">
                            <i class="fas fa-credit-card"></i> Metode Pembayaran
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label required">Metode Pembayaran</label>
                            <input type="text" class="form-control" value="Tunai" readonly>
                            <input type="hidden" name="metode_pembayaran" value="Tunai">
                            <small class="form-text text-muted">Pembayaran hanya bisa dilakukan secara tunai</small>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Catatan:</strong> Pembayaran mencakup biaya sewa HP dan denda kerusakan (jika ada). Setelah pembayaran dikonfirmasi, HP akan langsung tersedia kembali untuk dipinjam.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Informasi Denda:</strong><br>
                - Baik: Tidak ada denda<br>
                - Rusak Ringan: Rp 10.000<br>
                - Rusak Berat: Rp 20.000
            </div>
            
            <div class="form-group">
                <label class="form-label">Catatan (Opsional)</label>
                <textarea name="catatan" class="form-control" rows="3" placeholder="Jelaskan kondisi HP atau kerusakan yang terjadi..."></textarea>
            </div>
            
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Perhatian:</strong> Setelah mengajukan pengembalian, petugas akan memverifikasi kondisi HP. Pastikan Anda mengisi kondisi HP dengan jujur.
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Ajukan Pengembalian
                </button>
                <a href="<?= base_url('/peminjam/peminjaman') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
        <?php else: ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            Data peminjaman tidak ditemukan.
        </div>
        <a href="<?= base_url('/peminjam/peminjaman') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function hitungDenda() {
    const select = document.getElementById('kondisiHp');
    const kondisi = select.value;
    const hargaPerHari = parseInt(document.getElementById('hargaPerHari').value) || 0;
    const durasiHari = parseInt(document.getElementById('durasiHari').value) || 1;
    
    let denda = 0;
    
    console.log('Kondisi HP dipilih:', kondisi);
    console.log('Harga per hari:', hargaPerHari);
    console.log('Durasi:', durasiHari);
    
    // Hitung denda berdasarkan kondisi
    if (kondisi === 'rusak ringan') {
        denda = 10000;
    } else if (kondisi === 'rusak berat') {
        denda = 20000;
    } else if (kondisi === 'baik') {
        denda = 0;
    }
    
    // Hitung biaya sewa
    const biayaSewa = hargaPerHari * durasiHari;
    
    // Total pembayaran
    const total = biayaSewa + denda;
    
    console.log('Denda:', denda);
    console.log('Biaya sewa:', biayaSewa);
    console.log('Total:', total);
    
    // Set hidden input untuk backup
    document.getElementById('dendaCalculated').value = denda;
    
    // Update tampilan
    const dendaInfo = document.getElementById('dendaInfo');
    const dendaText = document.getElementById('dendaText');
    const biayaSewaText = document.getElementById('biayaSewaText');
    const totalText = document.getElementById('totalText');
    
    // Update biaya sewa
    biayaSewaText.textContent = 'Rp ' + biayaSewa.toLocaleString('id-ID');
    
    // Update denda
    if (denda > 0) {
        dendaText.textContent = 'Rp ' + denda.toLocaleString('id-ID');
        dendaInfo.style.display = 'block';
        dendaInfo.className = 'alert alert-danger';
    } else {
        dendaInfo.style.display = 'none';
    }
    
    // Update total
    totalText.textContent = 'Rp ' + total.toLocaleString('id-ID');
}

// Hitung saat halaman dimuat
window.addEventListener('DOMContentLoaded', function() {
    hitungDenda();
    
    // PROTEKSI: Pastikan box informasi peminjaman selalu terlihat
    const infoBox = document.getElementById('infoPeminjaman');
    if (infoBox) {
        // Paksa box selalu terlihat
        infoBox.style.display = 'block';
        infoBox.style.visibility = 'visible';
        infoBox.style.opacity = '1';
        
        // Monitor perubahan dan paksa tetap terlihat
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes') {
                    const target = mutation.target;
                    if (target.style.display === 'none' || target.style.visibility === 'hidden') {
                        target.style.display = 'block';
                        target.style.visibility = 'visible';
                        target.style.opacity = '1';
                    }
                }
            });
        });
        
        observer.observe(infoBox, {
            attributes: true,
            attributeFilter: ['style', 'class']
        });
    }
});
</script>
<?= $this->endSection() ?>
