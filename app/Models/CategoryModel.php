<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'category';
    protected $primaryKey       = 'id_category';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_category'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function index()
{
    $categoryModel = new CategoryModel();

    // =========================
    // KHUSUS PEMINJAM
    // =========================
    if (session()->get('role') === 'Peminjam') {

        $data = [
            'alat'      => $this->alatModel->getAlatWithStatus(),
            'category'  => $categoryModel->findAll(),
            'keyword'   => $this->request->getGet('keyword'),
            'catFilter' => $this->request->getGet('category')
        ];

        return view('peminjam/alat/index', $data);
    }

    // =========================
    // KHUSUS ADMIN
    // =========================
    $alatModel     = new AlatModel();
    $categoryModel = new CategoryModel();

    $keyword  = $this->request->getGet('keyword');
    $category = $this->request->getGet('category');

    $data = [
        'alat'      => $alatModel->getAlatFilteredWithStatus($keyword, $category),
        'category'  => $categoryModel->findAll(),
        'keyword'   => $keyword,
        'catFilter' => $category
    ];

    return view('admin/alat/index', $data);
}
}
