<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class CategoryController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        if (session()->get('role') !== 'Admin') {
            return redirect()->to('/login');
        }

        $keyword = $this->request->getGet('keyword');

        $builder = $this->categoryModel->db->table('category');

        if ($keyword) {
            $builder->like('nama_category', $keyword);
        }

        $data['category'] = $builder->get()->getResultArray();
        $data['keyword']  = $keyword;

        return view('admin/category/index', $data);
    }

    public function create()
    {
        if (session()->get('role') !== 'Admin') {
            return redirect()->to('/login');
        }

        return view('admin/category/create');
    }

    public function store()
    {
        if (session()->get('role') !== 'Admin') {
            return redirect()->to('/login');
        }

        $this->categoryModel->insert([
            'nama_category' => $this->request->getPost('nama_category'),
        ]);

        logAktivitas('Admin menambahkan kategori: ' . $this->request->getPost('nama_category'));

        return redirect()->to('/admin/category')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit($id)
    {
        if (session()->get('role') !== 'Admin') {
            return redirect()->to('/login');
        }

        $category = $this->categoryModel->find($id);

        if (!$category) {
            return redirect()->to('/admin/category')->with('error', 'Kategori tidak ditemukan');
        }

        return view('admin/category/edit', ['category' => $category]);
    }

    public function update($id)
    {
        if (session()->get('role') !== 'Admin') {
            return redirect()->to('/login');
        }

        $this->categoryModel->update($id, [
            'nama_category' => $this->request->getPost('nama_category'),
        ]);

        logAktivitas('Admin mengubah kategori ID: ' . $id);

        return redirect()->to('/admin/category')->with('success', 'Kategori berhasil diperbarui');
    }

    public function delete($id)
    {
        if (session()->get('role') !== 'Admin') {
            return redirect()->to('/login');
        }

        $category = $this->categoryModel->find($id);

        if (!$category) {
            return redirect()->to('/admin/category')->with('error', 'Kategori tidak ditemukan');
        }

        $this->categoryModel->delete($id);

        logAktivitas('Admin menghapus kategori: ' . $category['nama_category']);

        return redirect()->to('/admin/category')->with('success', 'Kategori berhasil dihapus');
    }
}
