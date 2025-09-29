<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\MaintenanceModel;

class ReminderUpdate extends BaseCommand
{
    protected $group       = 'Maintenance';
    protected $name        = 'reminder:update';
    protected $description = 'Update status pengingat maintenance';
    protected $usage       = 'reminder:update';

    public function run(array $params)
    {
        $model = new MaintenanceModel();

        // Hari ini (hanya tanggal, tanpa jam)
        $today = date('Y-m-d');

        // Update status "Hari ini"
        $model->where('pengingat', 1)
            ->where('DATE(tanggal_pengingat)', $today)
            ->set('status', 'Hari ini')
            ->update();

        // Update status "Lewat"
        $model->where('pengingat', 1)
            ->where('DATE(tanggal_pengingat) <', $today)
            ->set('status', 'Lewat')
            ->update();

        CLI::write('Status pengingat berhasil diperbarui!', 'green');
    }
}
