<?php

namespace App\Controllers;

use App\Models\BarangMasterModel;
use App\Models\BarangUnitModel;
use CodeIgniter\Controller;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;

class Barang extends Controller
{
    protected $barangMaster;
    protected $barangUnit;

    public function __construct()
    {
        $this->barangMaster = new BarangMasterModel();
        $this->barangUnit   = new BarangUnitModel();
    }

    public function index()
    {
        $data['barang'] = $this->barangMaster->getWithUnits();
        return view('admin/barang/index', $data);
    }

    // Halaman form create
    public function create()
    {
        return view('admin/barang/create');
    }

    public function edit($id)
    {
        $barang = $this->barangMaster->find($id);
        if (!$barang) {
            return redirect()->to('/barang')->with('error', 'Barang tidak ditemukan');
        }
        return view('admin/barang/edit', [
            'barang' => $barang
        ]);
    }

    public function update($id)
    {
        $barang = $this->barangMaster->find($id);
        if (!$barang) {
            return redirect()->to('/barang')->with('error', 'Barang tidak ditemukan');
        }
        $data = [
            'kode_barang' => $this->request->getPost('kode_barang'),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'kategori'    => $this->request->getPost('kategori'),
            'keterangan'  => $this->request->getPost('keterangan'),
        ];
        if ($this->barangMaster->update($id, $data)) {
            return redirect()->to('/barang')->with('success', 'Barang berhasil diperbarui');
        }
        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui barang');
    }

    public function delete($id = null)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'ID barang tidak ditemukan');
        }

        // ambil barang master dulu
        $barang = $this->barangMaster->find($id);
        if (!$barang) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan');
        }

        // hapus barang master (barang_unit akan otomatis terhapus karena FK CASCADE)
        $this->barangMaster->delete($id);

        return redirect()->to('/barang')->with('success', 'Barang berhasil dihapus');
    }

    public function store()
    {
        $dataMaster = [
            'kode_barang' => $this->request->getPost('kode_barang'),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'kategori'    => $this->request->getPost('kategori'),
            'keterangan'  => $this->request->getPost('keterangan'),
        ];

        $units = $this->request->getPost('units');
        $success = $this->barangMaster->saveWithUnits($dataMaster, $units);
        if ($success) {
            return redirect()->to('/barang')->with('success', 'Barang berhasil ditambahkan');
        }
        return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data');
    }

    public function detail($kode_barang)
    {
        $barangMaster = $this->barangMaster->where('kode_barang', $kode_barang)->first();
        if (!$barangMaster) {
            return redirect()->to('/barang')->with('error', 'Barang tidak ditemukan');
        }
        $units = $this->barangUnit->getByKodeBarang($kode_barang);
        $data = [
            'barang' => $barangMaster,
            'units'  => $units
        ];
        return view('admin/barang/detail', $data);
    }

    public function createUnit($kode_barang)
    {
        $barang = $this->barangMaster->where('kode_barang', $kode_barang)->first();
        if (!$barang) {
            return redirect()->to('/barang')->with('error', 'Barang tidak ditemukan.');
        }
        return view('admin/barang/create_unit', [
            'kode_barang' => $kode_barang,
            'barang' => $barang
        ]);
    }
    
    public function storeUnit($kode_barang)
    {
        $barang = $this->barangMaster->where('kode_barang', $kode_barang)->first();
        $kode_unit = $this->request->getPost('kode_unit');
        if (!$barang) {
            return redirect()->to('/barang')->with('error', 'Barang tidak ditemukan.');
        }
        $data = [
            'kode_barang' => $kode_barang,
            'kode_unit'   => $kode_unit,
            'merk'        => $this->request->getPost('merk'),
            'status'      => $this->request->getPost('status'),
            'kondisi'     => $this->request->getPost('kondisi'),
            'keterangan'  => $this->request->getPost('keterangan'),
            'slug'        => strtolower($kode_barang . '-' . $kode_unit),
        ];
        if ($this->barangUnit->insert($data)) {
            // Ambil ID unit setelah insert
            $unitId = $this->barangUnit->getInsertID();

            // Panggil fungsi generate QR code
            $this->generateQRCodeForUnit($unitId);

            return redirect()->to('/barang/detail/' . $kode_barang)
                ->with('success', 'Unit berhasil ditambahkan.');
        }
        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan unit.');
    }

    public function editUnit($id)
    {
        $unit = $this->barangUnit->find($id);
        if (!$unit) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Unit tidak ditemukan.");
        }
        return view('admin/barang/edit_unit', [
            'unit' => $unit
        ]);
    }

    public function updateUnit($id)
    {
        $unit = $this->barangUnit->find($id);
        $kode_unit = $this->request->getPost('kode_unit');

        if (!$unit) {
            return redirect()->to('/barang')->with('error', 'Unit tidak ditemukan.');
        }
        $newSlug = strtolower($unit['kode_barang'] . '-' . $kode_unit);
        $data = [
            'kode_unit' => $kode_unit,
            'merk'      => $this->request->getPost('merk'),
            'status'    => $this->request->getPost('status'),
            'kondisi'   => $this->request->getPost('kondisi'),
            'keterangan'  => $this->request->getPost('keterangan'),
            'slug'      => $newSlug,
        ];

        if ($this->barangUnit->update($id, $data)) {

            $updatedUnit = $this->barangUnit->find($id);
            if ($unit['slug'] !== $newSlug || empty($unit['qr_code'])) {
                $this->generateQrCodeForUnit($updatedUnit);
            }

            return redirect()->to('/barang/detail/' . $unit['kode_barang'])
                ->with('success', 'Unit berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui unit.');
    }

    public function deleteUnit($id)
    {
        $unit = $this->barangUnit->find($id);

        if (!$unit) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Unit tidak ditemukan.");
        }

        if ($this->barangUnit->delete($id)) {
            return redirect()->to('/barang/detail/' . $unit['kode_barang'])
                ->with('success', 'Unit berhasil dihapus.');
        } else {
            return redirect()->back()
                ->with('error', 'Gagal menghapus unit.');
        }
    }

    public function getUnits($kode_barang)
    {
        $units = $this->barangUnit
            ->where('kode_barang', $kode_barang)
            ->findAll();

        return $this->response->setJSON($units);
    }

    private function generateQrCodeForUnit($unit)
    {
        // Jika unit yang dikirim adalah ID, ambil datanya dulu
        if (is_int($unit)) {
            $unit = $this->barangUnit->find($unit);
        }

        if (!$unit || !is_array($unit)) {
            return;
        }

        $url = base_url('/barang/view-unit/' . $unit['slug']);

        $fileName = 'qr-' . $unit['id'] . '.png';
        $saveDir  = WRITEPATH . 'uploads/qr/';
        $savePath = $saveDir . $fileName;

        if (!is_dir($saveDir)) {
            mkdir($saveDir, 0777, true);
        }

        // ====== QR CODE VERSION 6 ======
        $qr = new QrCode(
            data: $url,
            encoding: new Encoding('UTF-8'),
            size: 300,
            margin: 10
        );

        $writer = new PngWriter();
        $writer->write($qr)->saveToFile($savePath);

        // Simpan nama file ke database
        $this->barangUnit->update($unit['id'], [
            'qr_code' => $fileName
        ]);
    }

    public function downloadQr($slug)
    {
        $unit = $this->barangUnit->where('slug', $slug)->first();
        // dd($unit);
        // die;

        if (!$unit || empty($unit['qr_code'])) {
            // throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            return redirect()->back()
                ->with('error', 'Halaman tidak ditemukan. Mohon Update data!');
        }

        $filePath = WRITEPATH . 'uploads/qr/' . $unit['qr_code'];

        if (!file_exists($filePath)) {
            // throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("QR Code tidak ditemukan!");
            return redirect()->back()
                ->with('error', 'QR Code belum dibuat. Mohon update data!');
        }

        return $this->response->download($filePath, null);
    }

}
