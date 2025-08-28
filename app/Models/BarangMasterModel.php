<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangMasterModel extends Model
{
    protected $table            = 'barang_master';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['kode_barang', 'nama_barang', 'kategori', 'keterangan'];

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

    public function getRekap()
    {
        return $this->select('
                bm.id,
                bm.kode_barang,
                bm.nama_barang,
                bm.kategori,
                bm.keterangan,
                COUNT(bu.id) as jumlah,
                SUM(CASE WHEN bu.status = "dipinjam" THEN 1 ELSE 0 END) as dipinjam,
                COUNT(bu.id) - SUM(CASE WHEN bu.status = "dipinjam" THEN 1 ELSE 0 END) as sisa
            ')
            ->from('barang_master bm')
            ->join('barang_unit bu', 'bu.barang_id = bm.id', 'left')
            ->groupBy('bm.id, bm.kode_barang, bm.nama_barang, bm.kategori, bm.keterangan')
            ->findAll();
    }
}
