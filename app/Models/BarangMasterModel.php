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
    protected $allowedFields    = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'keterangan',
        'created_at',
        'updated_at'
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

    protected $barangUnitModel;

    public function __construct()
    {
        parent::__construct();
        $this->barangUnitModel = new \App\Models\BarangUnitModel();
    }

    public function saveWithUnits(array $dataMaster, array $units = [])
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // simpan master
        $this->insert($dataMaster);

        $kodeBarang = $dataMaster['kode_barang'];

        // simpan unit
        if (!empty($units)) {
            foreach ($units as $unit) {
                $this->barangUnitModel->insert([
                    'kode_barang' => $kodeBarang,
                    'kode_unit'   => $unit['kode_unit'],
                    'merk'        => $unit['merk'] ?? null,
                    'status'      => $unit['status'] ?? null,
                ]);
            }
        }

        $db->transComplete();

        return $db->transStatus();
    }

    public function getWithUnits()
    {
        return $this->select('barang_master.*, COUNT(barang_unit.kode_unit) as total_unit')
                    ->join('barang_unit', 'barang_unit.kode_barang = barang_master.kode_barang', 'left')
                    ->groupBy('barang_master.kode_barang')
                    ->findAll();
    }
}
