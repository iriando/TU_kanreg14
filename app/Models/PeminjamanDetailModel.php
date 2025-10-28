<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanDetailModel extends Model
{
    protected $table            = 'log_peminjaman_detail';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'peminjaman_id',
        'kode_barang',
        'kode_unit',
        'status_unit',
        'tanggal_pinjam',
        'tanggal_kembali',
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

    public function getByKodeUnit($kode_unit)
    {
        return $this->where('kode_unit', $kode_unit)->findAll();
    }

    public function getByPeminjamanId($id)
    {
        return $this->where('peminjaman_id', $id)->findAll();
    }
}
