<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangUnitModel extends Model
{
    protected $table            = 'barang_unit';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_barang',
        'kode_unit',
        'merk',
        'status',
        'kondisi',
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
    protected $skipValidation       = true;
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

    public function getByKodeBarang($kode_barang)
    {
        return $this->where('kode_barang', $kode_barang)->findAll();
    }

    public function getAvailableByKodeBarang($kode_barang)
    {
        return $this->where('kode_barang', $kode_barang)
                    ->where('status', 'tersedia')
                    ->findAll();
    }

    public function setDipinjam(array $ids)
    {
        return $this->whereIn('id', $ids)
                    ->set(['status' => 'dipinjam'])
                    ->update();
    }

    public function setTersedia(array $ids)
    {
        return $this->whereIn('id', $ids)
                    ->set(['status' => 'tersedia'])
                    ->update();
    }

    public function getByStatus($kodeBarang, $status)
    {
        return $this->where('kode_barang', $kodeBarang)
                    ->where('status', $status)
                    ->findAll();
    }
}
