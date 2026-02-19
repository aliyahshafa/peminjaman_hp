<form method="post" action="<?= base_url('admin/peminjaman/kembalikan/'.$peminjaman['id_peminjaman']) ?>">

    <label>Kondisi HP</label>
    <select name="kondisi_hp" required>
        <option value="Baik">Baik</option>
        <option value="Rusak Ringan">Rusak Ringan</option>
        <option value="Rusak Berat">Rusak Berat</option>
    </select>

    <button type="submit">Simpan Pengembalian</button>
</form>