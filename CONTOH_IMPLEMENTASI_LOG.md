# 🔧 Contoh Implementasi Log di Controller yang Ada

## Daftar Controller yang Perlu Ditambahkan Log

### 1. AuthController.php

```php
public function login()
{
    if ($this->request->getMethod() === 'post') {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        $user = $this->userModel->where('username', $username)->first();
        
        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'id_user' => $user['id_user'],
                'nama_user' => $user['nama_user'],
                'role' => $user['role'],
                'logged_in' => true
            ]);
            
            // ✅ TAMBAHKAN LOG LOGIN BERHASIL
            logLogin($user['id_user'], $user['nama_user'], $user['role'], 'success');
            
            return redirect()->to('/dashboard');
        } else {
            // ✅ TAMBAHKAN LOG LOGIN GAGAL
            logLogin(0, $username, 'Unknown', 'failed');
            
            return redirect()->back()->with('error', 'Username atau password salah');
        }
    }
    
    return view('auth/login');
}

public function logout()
{
    // ✅ TAMBAHKAN LOG LOGOUT
    logLogout();
    
    session()->destroy();
    return redirect()->to('/login');
}
```

---

### 2. AlatController.php (Admin)

```php
public function index()
{
    // ✅ TAMBAHKAN LOG VIEW
    logView('daftar alat');
    
    $data['alat'] = $this->alatModel->findAll();
    return view('admin/alat/index', $data);
}

public function create()
{
    if ($this->request->getMethod() === 'post') {
        $data = $this->request->getPost();
        
        if ($this->alatModel->insert($data)) {
            // ✅ TAMBAHKAN LOG CREATE
            logCreate('alat', $data['nama_alat']);
            
            return redirect()->to('/admin/alat')->with('success', 'Alat berhasil ditambahkan');
        }
    }
    
    return view('admin/alat/create');
}

public function update($id)
{
    $alat = $this->alatModel->find($id);
    
    if ($this->request->getMethod() === 'post') {
        $data = $this->request->getPost();
        
        if ($this->alatModel->update($id, $data)) {
            // ✅ TAMBAHKAN LOG UPDATE
            logUpdate('alat', $alat['nama_alat']);
            
            return redirect()->to('/admin/alat')->with('success', 'Alat berhasil diupdate');
        }
    }
    
    return view('admin/alat/edit', ['alat' => $alat]);
}

public function delete($id)
{
    $alat = $this->alatModel->find($id);
    
    if ($this->alatModel->delete($id)) {
        // ✅ TAMBAHKAN LOG DELETE
        logDelete('alat', $alat['nama_alat']);
        
        return redirect()->to('/admin/alat')->with('success', 'Alat berhasil dihapus');
    }
}
```

---

### 3. UserController.php (Admin)

```php
public function index()
{
    // ✅ TAMBAHKAN LOG VIEW
    logView('daftar user');
    
    $data['users'] = $this->userModel->findAll();
    return view('user/index', $data);
}

public function create()
{
    if ($this->request->getMethod() === 'post') {
        $data = $this->request->getPost();
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        if ($this->userModel->insert($data)) {
            // ✅ TAMBAHKAN LOG CREATE
            logCreate('user', $data['nama_user']);
            
            return redirect()->to('/admin/user')->with('success', 'User berhasil ditambahkan');
        }
    }
    
    return view('user/create');
}

public function update($id)
{
    $user = $this->userModel->find($id);
    
    if ($this->request->getMethod() === 'post') {
        $data = $this->request->getPost();
        
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        
        if ($this->userModel->update($id, $data)) {
            // ✅ TAMBAHKAN LOG UPDATE
            logUpdate('user', $user['nama_user']);
            
            return redirect()->to('/admin/user')->with('success', 'User berhasil diupdate');
        }
    }
    
    return view('user/edit', ['user' => $user]);
}

public function delete($id)
{
    $user = $this->userModel->find($id);
    
    if ($this->userModel->delete($id)) {
        // ✅ TAMBAHKAN LOG DELETE
        logDelete('user', $user['nama_user']);
        
        return redirect()->to('/admin/user')->with('success', 'User berhasil dihapus');
    }
}
```

---

### 4. PeminjamanController.php (Admin)

```php
public function index()
{
    // ✅ TAMBAHKAN LOG VIEW
    logView('daftar peminjaman');
    
    $data['peminjaman'] = $this->peminjamanModel->findAll();
    return view('admin/peminjaman/index', $data);
}

public function approve($id)
{
    $peminjaman = $this->peminjamanModel->find($id);
    
    $data = [
        'status' => 'Disetujui',
        'tanggal_disetujui' => date('Y-m-d H:i:s')
    ];
    
    if ($this->peminjamanModel->update($id, $data)) {
        // ✅ TAMBAHKAN LOG PEMINJAMAN
        logPeminjaman('approve', $peminjaman['id_hp'], $peminjaman['nama_peminjam']);
        
        return redirect()->to('/admin/peminjaman')->with('success', 'Peminjaman berhasil disetujui');
    }
}

public function reject($id)
{
    $peminjaman = $this->peminjamanModel->find($id);
    
    $data = [
        'status' => 'Ditolak',
        'tanggal_ditolak' => date('Y-m-d H:i:s')
    ];
    
    if ($this->peminjamanModel->update($id, $data)) {
        // Update stok alat kembali
        $this->alatModel->increment('stok', $peminjaman['id_alat']);
        
        // ✅ TAMBAHKAN LOG PEMINJAMAN
        logPeminjaman('reject', $peminjaman['id_hp'], $peminjaman['nama_peminjam']);
        
        return redirect()->to('/admin/peminjaman')->with('success', 'Peminjaman ditolak');
    }
}

public function return($id)
{
    $peminjaman = $this->peminjamanModel->find($id);
    
    $data = [
        'status' => 'Dikembalikan',
        'tanggal_kembali' => date('Y-m-d H:i:s')
    ];
    
    if ($this->peminjamanModel->update($id, $data)) {
        // Update stok alat
        $this->alatModel->increment('stok', $peminjaman['id_alat']);
        
        // ✅ TAMBAHKAN LOG PEMINJAMAN
        logPeminjaman('return', $peminjaman['id_hp'], $peminjaman['nama_peminjam']);
        
        return redirect()->to('/admin/pengembalian')->with('success', 'Alat berhasil dikembalikan');
    }
}
```

---

### 5. PeminjamPeminjamanController.php (Peminjam)

```php
public function index()
{
    // ✅ TAMBAHKAN LOG VIEW
    logView('riwayat peminjaman saya');
    
    $idUser = session()->get('id_user');
    $data['peminjaman'] = $this->peminjamanModel->where('id_user', $idUser)->findAll();
    return view('peminjam/peminjaman/index', $data);
}

public function create()
{
    if ($this->request->getMethod() === 'post') {
        $data = $this->request->getPost();
        $data['id_user'] = session()->get('id_user');
        $data['status'] = 'Menunggu';
        
        if ($this->peminjamanModel->insert($data)) {
            // Kurangi stok alat
            $this->alatModel->decrement('stok', $data['id_alat']);
            
            // ✅ TAMBAHKAN LOG PEMINJAMAN
            $alat = $this->alatModel->find($data['id_alat']);
            logPeminjaman('create', $alat['id_hpt'], session()->get('nama_user'));
            
            return redirect()->to('/peminjam/peminjaman')->with('success', 'Peminjaman berhasil diajukan');
        }
    }
    
    $data['alat'] = $this->alatModel->where('stok >', 0)->findAll();
    return view('peminjam/peminjaman/create', $data);
}

public function cancel($id)
{
    $peminjaman = $this->peminjamanModel->find($id);
    
    // Pastikan peminjaman milik user yang login
    if ($peminjaman['id_user'] != session()->get('id_user')) {
        return redirect()->back()->with('error', 'Akses ditolak');
    }
    
    if ($peminjaman['status'] == 'Menunggu') {
        $data = ['status' => 'Dibatalkan'];
        
        if ($this->peminjamanModel->update($id, $data)) {
            // Kembalikan stok alat
            $this->alatModel->increment('stok', $peminjaman['id_alat']);
            
            // ✅ TAMBAHKAN LOG PEMINJAMAN
            logPeminjaman('cancel', $peminjaman['id_hpt'], session()->get('nama_user'));
            
            return redirect()->to('/peminjam/peminjaman')->with('success', 'Peminjaman dibatalkan');
        }
    }
}
```

---

### 6. PetugasPeminjamanController.php (Petugas)

```php
public function index()
{
    // ✅ TAMBAHKAN LOG VIEW
    logView('daftar peminjaman (petugas)');
    
    $data['peminjaman'] = $this->peminjamanModel->findAll();
    return view('petugas/peminjaman/index', $data);
}
```

---

### 7. PetugasLaporanController.php (Petugas)

```php
public function index()
{
    // ✅ TAMBAHKAN LOG VIEW
    logView('laporan peminjaman');
    
    $dateFrom = $this->request->getGet('date_from');
    $dateTo = $this->request->getGet('date_to');
    
    $builder = $this->peminjamanModel;
    
    if ($dateFrom) {
        $builder->where('tanggal_pinjam >=', $dateFrom);
    }
    if ($dateTo) {
        $builder->where('tanggal_pinjam <=', $dateTo);
    }
    
    $data['peminjaman'] = $builder->findAll();
    return view('petugas/laporan/index', $data);
}

public function cetak()
{
    // ✅ TAMBAHKAN LOG EXPORT
    logExport('laporan peminjaman', 'PDF');
    
    $dateFrom = $this->request->getGet('date_from');
    $dateTo = $this->request->getGet('date_to');
    
    $builder = $this->peminjamanModel;
    
    if ($dateFrom) {
        $builder->where('tanggal_pinjam >=', $dateFrom);
    }
    if ($dateTo) {
        $builder->where('tanggal_pinjam <=', $dateTo);
    }
    
    $data['peminjaman'] = $builder->findAll();
    $data['date_from'] = $dateFrom;
    $data['date_to'] = $dateTo;
    
    return view('petugas/laporan/cetak', $data);
}
```

---

### 8. ProfileController.php

```php
public function index()
{
    // ✅ TAMBAHKAN LOG VIEW
    logView('profil saya');
    
    $idUser = session()->get('id_user');
    $data['user'] = $this->userModel->find($idUser);
    return view('profile/index', $data);
}

public function update()
{
    if ($this->request->getMethod() === 'post') {
        $idUser = session()->get('id_user');
        $data = $this->request->getPost();
        
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        
        if ($this->userModel->update($idUser, $data)) {
            // Update session jika nama berubah
            if (isset($data['nama_user'])) {
                session()->set('nama_user', $data['nama_user']);
            }
            
            // ✅ TAMBAHKAN LOG UPDATE
            logUpdate('profil', session()->get('nama_user'));
            
            return redirect()->to('/profile')->with('success', 'Profil berhasil diupdate');
        }
    }
}
```

---

### 9. DashboardController.php

```php
public function index()
{
    $role = session()->get('role');
    
    // ✅ TAMBAHKAN LOG VIEW
    logView('dashboard ' . strtolower($role));
    
    if ($role == 'Admin') {
        return $this->adminDashboard();
    } elseif ($role == 'Petugas') {
        return $this->petugasDashboard();
    } else {
        return $this->peminjamDashboard();
    }
}
```

---

## Checklist Implementasi

### ✅ Yang Sudah Dibuat:
- [x] LogController dengan fitur lengkap
- [x] LogModel dengan kolom tambahan
- [x] Helper functions untuk berbagai jenis log
- [x] View untuk tampilan log
- [x] View untuk statistik
- [x] View untuk export
- [x] View untuk clear log
- [x] SQL untuk update database
- [x] Dokumentasi lengkap

### 📝 Yang Perlu Dilakukan:
1. Jalankan SQL untuk update tabel `log_aktivitas`
2. Tambahkan routes untuk log di `app/Config/Routes.php`
3. Load helper log di `BaseController.php` atau controller yang membutuhkan
4. Implementasikan log di setiap controller sesuai contoh di atas
5. Test semua fitur log
6. Setup cron job untuk pembersihan log otomatis (opsional)

---

## Tips Implementasi

1. **Konsisten**: Gunakan helper function yang sesuai untuk setiap jenis aktivitas
2. **Deskriptif**: Buat deskripsi log yang jelas dan informatif
3. **Minimal**: Jangan log aktivitas yang terlalu detail atau tidak penting
4. **Performa**: Gunakan pagination dan cleanup berkala untuk menjaga performa

---

## Testing

Setelah implementasi, test fitur-fitur berikut:

1. ✅ Login/Logout tercatat
2. ✅ CRUD alat tercatat
3. ✅ CRUD user tercatat
4. ✅ Peminjaman (create, approve, reject, return) tercatat
5. ✅ Filter log berfungsi
6. ✅ Statistik menampilkan data yang benar
7. ✅ Export CSV berfungsi
8. ✅ Export PDF berfungsi
9. ✅ Clear log berfungsi
10. ✅ Pagination berfungsi

Selamat mengimplementasikan! 🚀
