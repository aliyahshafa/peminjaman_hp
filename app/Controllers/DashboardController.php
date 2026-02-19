<?php  

namespace App\Controllers;  

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\AlatModel;

class DashboardController extends BaseController  
{
    public function dashboard()
{
    return view('dashboard/dashboard');
}

    // Admin
    public function admin() 
    {
        // Cek apakah user admin
        if (session()->get('role') != 'Admin') {
            return redirect()->to('/dashboard');
        }

        return view('dashboard/dashboard');
    }

    // Petugas
    public function petugas()
    {
        if (session()->get('role') != 'Petugas') {
            return redirect()->to('/dashboard');
        }

        return view('dashboard/petugas');
    }

    // User
    public function user()
    {
        if (session()->get('role') != 'Peminjam') {
            return redirect()->to('/dashboard');
        }

        return view('dashboard/user');
    }

    public function index()
{

    // ambil role dari session
        $role = session()->get('role');

        // arahkan ke dashboard sesuai role
        if ($role == 'Admin') {
            return redirect()->to('/admin');
        } elseif ($role == 'Petugas') {
            return redirect()->to('/petugas');
        } elseif ($role == 'Peminjam') {
            return redirect()->to('/peminjam');
        }

        return redirect()->to('/');
        

    // Instance model alat (HP)
    $alatModel = new AlatModel();

    // Instance model kategori
    $categoryModel = new CategoryModel();

    // Ambil keyword pencarian dari URL (?keyword=...)
    $keyword  = $this->request->getGet('tipe');

    // Ambil filter kategori dari URL (?category=...)
    $category = $this->request->getGet('category');

    // Query alat + join kategori + filter + pagination
    $alatModel->getAlatFiltered($keyword, $category); // 10 data per halaman

    // Data dikirim ke view
    $data = [
        'title'     => 'Dashboard Peminjaman Handphone',
        'alat'      => $alatModel->paginate(10, 'alat'),            // DATA YANG SUDAH DIFILTER
        'pager'     => $alatModel->pager,        // Pagination
        'category'  => $categoryModel->findAll(),
        'keyword'   => $keyword,
        'catFilter' => $category
    ];
    
    return view('dashboard/dashboard', $data);
}

    // Form tambah alat
    public function create()
    {
        $categoryModel = new CategoryModel();
        $data['category'] = $categoryModel->findAll();

        return view('admin/alat/create', $data);
    }

    // Simpan alat
    public function store()
    {
        $alatModel = new AlatModel();

        $alatModel->insert([
            'id_hp'       => $this->request->getPost('id_hp'),
            'id_category' => $this->request->getPost('id_category'),
            'merk'        => $this->request->getPost('merk'),
            'tipe'        => $this->request->getPost('tipe'),
            'kondisi'     => $this->request->getPost('kondisi'),
            'status'      => $this->request->getPost('status')
        ]);

        return redirect()->to('/alat')->with('succes', 'Alat Baru Berhasil Ditambahkan!');
    }

    // Form edit
    public function edit($id)
    {
        $alatModel = new AlatModel();
        $categoryModel = new CategoryModel();

        return view('admin/alat/edit', [
            'alat'     => $alatModel->find($id),
            'category' => $categoryModel->findAll()
        ]);
    }

    // Update alat
    public function update($id)
    {
        $alatModel = new AlatModel();

        $alatModel->update($id, [
            'id_category' => $this->request->getPost('id_category'),
            'merk'        => $this->request->getPost('merk'),
            'tipe'        => $this->request->getPost('tipe'),
        ]);

        return redirect()->to('/admin/alat')->with('success', 'Data berhasil update');
    }

    // Hapus alat
    public function delete($id)
    {
        $alatModel = new AlatModel();
        $alatModel->delete($id);

        return redirect()->to('/admin/alat')->with('success', 'Data Berhasil Dihapus');
    }
}

