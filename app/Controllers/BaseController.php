<?php

namespace App\Controllers;

use Config\Services;
use CodeIgniter\Controller;
use Psr\Log\LoggerInterface;
use App\Models\MaintenanceModel;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    // protected $helpers = [];
    protected $helpers = ['auth', 'form', 'url'];
    

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;
    protected $maintenanceModel;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $this->maintenanceModel = new MaintenanceModel();
        $notifikasi = [];

        $notifikasi = $this->maintenanceModel
            ->where('pengingat', 1) // hanya yg aktif
            ->where('tanggal_pengingat <=', date('Y-m-d H:i:s'))
            ->groupStart() // mulai group
                ->where('status', 'Hari ini')
                ->orWhere('status', 'Lewat')
            ->groupEnd()   // tutup group
            ->findAll();
        // dd($notifikasi);
        // die;
        Services::renderer()->setVar('notifikasi', $notifikasi);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
    }
}
