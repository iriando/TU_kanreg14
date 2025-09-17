<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\MaintenanceModel;


class ReminderUpdate extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Maintenance';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'reminder:update';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Update status pengingat maintenance';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'command:name [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $model = new MaintenanceModel();

        $today = date('Y-m-d H:i:s');

        // Hari ini
        $model->where('pengingat', 1)
              ->where('DATE(tanggal_pengingat)', $today)
              ->set('status', 'Hari ini')
              ->update();

        // Lewat
        $model->where('pengingat', 1)
              ->where('DATE(tanggal_pengingat) <', $today)
              ->set('status', 'Lewat')
              ->update();

        CLI::write('Status pengingat berhasil diperbarui!', 'green');
    }
}
