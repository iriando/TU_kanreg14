<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanModel extends Model
{
    protected $table            = 'log_peminjaman';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'transaksi_id',
        'nama_peminjam',
        'nama_barang',
        'kode_barang',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'petugas_pinjam',
        'petugas_kembalikan',
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

    public function getById($id)
    {
        return $this->where('id', $id)->first();
    }

    public function deletePeminjaman($id)
    {
        $peminjaman = $this->getById($id);
        if ($peminjaman) {
            $this->delete($id);
        }
    }

    public function getTotalPinjam($transaksiId)
    {
        return (int) $this->where('transaksi_id', $transaksiId)
                         ->where('status', 'pinjam')
                         ->selectSum('jumlah')
                         ->get()
                         ->getRow()
                         ->jumlah ?? 0;
    }

    // Hitung total dikembalikan
    public function getTotalKembali($transaksiId)
    {
        return (int) $this->where('transaksi_id', $transaksiId)
                         ->where('status', 'dikembalikan')
                         ->selectSum('jumlah')
                         ->get()
                         ->getRow()
                         ->jumlah ?? 0;
    }

    // Hitung sisa yang masih dipinjam
    public function getSisaPinjam($transaksiId)
    {
        $totalPinjam  = $this->getTotalPinjam($transaksiId);
        $totalKembali = $this->getTotalKembali($transaksiId);

        return $totalPinjam - $totalKembali;
    }
}
