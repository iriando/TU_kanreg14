<?php

namespace App\Models;

use CodeIgniter\Model;

class AtkModel extends Model
{
    protected $table            = 'atk';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_barang',
        'nama_barang',
        'satuan',
        'jumlah',
        'didistribusi',
        'sisa',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'kode_barang' => 'required|is_unique[atk.kode_barang]',
        'nama_barang' => 'required',
        'satuan'      => 'required',
        'jumlah'      => 'required|integer',
    ];
    protected $validationMessages   = [
        'kode_barang' => [
            'required'  => 'Kode barang wajib diisi.',
            'is_unique' => 'Kode barang sudah digunakan.'
        ],
        'nama_barang' => [
            'required' => 'Nama barang wajib diisi.'
        ],
        'satuan' => [
            'required' => 'Satuan wajib diisi.'
        ],
        'jumlah' => [
            'required' => 'Jumlah wajib diisi.',
            'integer'  => 'Jumlah harus berupa angka.'
        ]
    ];
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

    public function getValidationRules(array $data = []): array
    {
        return [
            'kode_barang' => 'required',
            'nama_barang' => 'required',
            'satuan'      => 'required',
            'jumlah'      => 'required|integer',
        ];
    }
    
    public function getAll()
    {
        return $this->findAll();
    }

    public function getByKode($kode)
    {
        return $this->where('kode_barang', $kode)->first();
    }

    public function updateByKode($kode_barang, $data)
    {
        return $this->where('kode_barang', $kode_barang)->set($data)->update();
    }
}
