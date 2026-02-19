# ADMIN & PEMINJAM DASHBOARD FIX

## Problem
Dashboard petugas sudah menampilkan harga dengan benar, tapi dashboard admin dan peminjam masih menampilkan Rp 0.

## Solution Applied
Menggunakan **pendekatan yang sama persis** seperti PetugasController yang sudah berhasil.

## Changes Made

### 1. AdminController.php
**BEFORE:**
```php
$data['recentAlat'] = $alatModel->getAlatWithHargaForced();
// Complex method with potential issues
```

**AFTER (Same as PetugasController):**
```php
// Query langsung seperti di PetugasController
$recentAlatRaw = $alatModel->db->table('alat')
    ->select('*')
    ->where('deleted_at IS NULL')
    ->orderBy('id_hp', 'DESC')
    ->limit(5)
    ->get()
    ->getResultArray();

// Tambahkan nama kategori manual seperti di PetugasController
$categoryModel = new \App\Models\CategoryModel();
foreach ($recentAlatRaw as &$alat) {
    if (isset($alat['id_category'])) {
        $category = $categoryModel->find($alat['id_category']);
        $alat['nama_category'] = $category ? $category['nama_category'] : '-';
    }
}
```

### 2. DashboardPeminjam.php
**BEFORE:**
```php
$data['riwayat'] = $this->peminjamanModel->getRiwayatByUser(session()->get('id_user'));
// Using model method that might have issues
```

**AFTER (Same as PetugasController):**
```php
// Ambil data peminjaman dulu seperti di PetugasController
$riwayatRaw = $peminjamanModel
    ->where('id_user', $idUser)
    ->whereIn('status', ['Diajukan', 'Disetujui', 'Menunggu Pengembalian', 'Dikembalikan'])
    ->findAll();

// Tambahkan data alat manual seperti di PetugasController
foreach ($riwayatRaw as &$r) {
    $alatData = $alatModel->db->table('alat')
        ->select('merk, tipe, harga')
        ->where('id_hp', $r['id_hp'])
        ->get()
        ->getRowArray();
    
    if ($alatData) {
        $r['merk'] = $alatData['merk'];
        $r['tipe'] = $alatData['tipe'];
        $r['harga'] = $alatData['harga'];
    }
}
```

## Key Strategy
**Copy the exact same approach** that works in PetugasController:
1. Use `$alatModel->db->table('alat')` for direct queries
2. Manual data fetching and assignment
3. Same query structure and logic

## Expected Result
Now all three dashboards should show correct harga values:
- ✅ **Dashboard Petugas** - Already working
- ✅ **Dashboard Admin** - Now fixed with same approach
- ✅ **Dashboard Peminjam** - Now fixed with same approach

## Test File
Run `TEST_ADMIN_PEMINJAM_FIX.php` to verify both fixes are working.