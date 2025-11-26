<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Dashboard::index');
// $routes->get('/dashboard', 'Dashboard::index');

$routes->group('', ['filter' => 'login'], function($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index');
});

$routes->group('', ['namespace' => 'Myth\Auth\Controllers'], function ($routes) {
    $routes->get('login', 'AuthController::login', ['as' => 'login']);
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('logout', 'AuthController::logout');
    $routes->get('register', 'AuthController::register', ['as' => 'register']);
    $routes->post('register', 'AuthController::attemptRegister');
    $routes->get('forgot', 'AuthController::forgotPassword', ['as' => 'forgot']);
    $routes->post('forgot', 'AuthController::attemptForgot');
});

$routes->group('usermanager', [
    'namespace' => 'App\Controllers',
    'filter'    => 'role:admin'
], function($routes) {
    $routes->get('users', 'UserManager::index');
    $routes->get('users/create', 'UserManager::create');
    $routes->post('users/store', 'UserManager::store');
    $routes->get('users/edit/(:num)', 'UserManager::edit/$1');
    $routes->post('users/update/(:num)', 'UserManager::update/$1');
    $routes->get('users/delete/(:num)', 'UserManager::delete/$1');
});

$routes->group('template', [
    'namespace' => 'App\Controllers',
    'filter'    => 'role:admin'
], function($routes) {
    $routes->get('/', 'TemplateManager::index');
    $routes->get('create', 'TemplateManager::create');
    $routes->post('store', 'TemplateManager::store');
    $routes->get('delete/(:num)', 'TemplateManager::delete/$1');
});

$routes->group('peminjaman', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'Peminjaman::index');
    $routes->get('create', 'Peminjaman::create');
    $routes->get('getBarangUnit', 'Peminjaman::getBarangUnit');
    $routes->get('getUnitDipinjam', 'Peminjaman::getUnitDipinjam');// route untuk ajax
    $routes->post('store', 'Peminjaman::store');
    $routes->post('kembalikan', 'Peminjaman::kembalikan');
    $routes->get('bast/(:num)', 'Peminjaman::bast/$1');
    $routes->post('prosesBast/(:num)', 'Peminjaman::prosesBast/$1');
    $routes->group('', ['filter' => 'role:admin'], function($routes) {
        $routes->get('edit/(:num)', 'Peminjaman::edit/$1');
        $routes->post('update/(:num)', 'Peminjaman::update/$1');
        $routes->get('delete/(:num)', 'Peminjaman::delete/$1');
    });
});

$routes->group('barang', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'Barang::index');
    $routes->group('', ['filter' => 'role:admin'], function($routes) {
        $routes->get('create', 'Barang::create');
        $routes->post('store', 'Barang::store');
        $routes->get('edit/(:num)', 'Barang::edit/$1');
        $routes->post('update/(:num)', 'Barang::update/$1');
        $routes->get('delete/(:num)', 'Barang::delete/$1');
        $routes->get('detail/(:segment)', 'Barang::detail/$1');
        $routes->get('create-unit/(:segment)', 'Barang::createUnit/$1');
        $routes->post('store-unit/(:segment)', 'Barang::StoreUnit/$1');
        $routes->get('edit-unit/(:num)', 'Barang::editUnit/$1');
        $routes->post('update-unit/(:num)', 'Barang::updateUnit/$1');
        $routes->get('delete-unit/(:num)', 'Barang::deleteUnit/$1');
    });
});

$routes->get('barang/view-unit/(:segment)', 'Unit::view/$1');

$routes->group('distribusiatk', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'DistribusiAtk::index');
    $routes->get('create', 'DistribusiAtk::create');
    $routes->post('store', 'DistribusiAtk::store');
    $routes->group('', ['filter' => 'role:admin'], function($routes) {
        $routes->get('edit/(:num)', 'DistribusiAtk::edit/$1');
        $routes->post('update/(:num)', 'DistribusiAtk::update/$1');
        $routes->get('delete/(:num)', 'DistribusiAtk::delete/$1');
    });
});

$routes->group('atk', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'Atk::index');
    $routes->group('', ['filter' => 'role:admin'], function($routes) {
        $routes->get('create', 'Atk::create');
        $routes->post('store', 'Atk::store');
        $routes->get('edit/(:num)', 'Atk::edit/$1');
        $routes->post('update/(:num)', 'Atk::update/$1');
        $routes->get('delete/(:num)', 'Atk::delete/$1');
    });
});

$routes->group('distribusiobat', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'DistribusiObat::index');
    $routes->get('create', 'DistribusiObat::create');
    $routes->post('store', 'DistribusiObat::store');
    $routes->group('', ['filter' => 'role:admin'], function($routes) {
        $routes->get('edit/(:num)', 'DistribusiObat::edit/$1');
        $routes->post('update/(:num)', 'DistribusiObat::update/$1');
        $routes->get('delete/(:num)', 'DistribusiObat::delete/$1');
    });
});

$routes->group('obat', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'Obat::index');
    $routes->group('', ['filter' => 'role:admin'], function($routes) {
        $routes->get('create', 'Obat::create');
        $routes->post('store', 'Obat::store');
        $routes->get('edit/(:num)', 'Obat::edit/$1');
        $routes->post('update/(:num)', 'Obat::update/$1');
        $routes->get('delete/(:num)', 'Obat::delete/$1');
    });
});

$routes->group('maintenance', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'Maintenance::index');
    $routes->get('create', 'Maintenance::create');
    $routes->post('store', 'Maintenance::store');
    $routes->group('', ['filter' => 'role:admin'], function($routes) {
        $routes->get('edit/(:num)', 'Maintenance::edit/$1');
        $routes->post('update/(:num)', 'Maintenance::update/$1');
        $routes->get('delete/(:num)', 'Maintenance::delete/$1');
        $routes->get('selesai/(:num)', 'Maintenance::selesai/$1');
    });
});

$routes->group('pegawai', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'Pegawai::index');
    $routes->group('', ['filter' => 'role:admin'], function($routes) {
        $routes->get('create', 'Pegawai::create');
        $routes->post('store', 'Pegawai::store');
        $routes->get('edit/(:num)', 'Pegawai::edit/$1');
        $routes->post('update/(:num)', 'Pegawai::update/$1');
        $routes->get('delete/(:num)', 'Pegawai::delete/$1');
    });
});

$routes->group('logpegawai', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'LogPegawai::index');
    $routes->group('', ['filter' => 'role:admin'], function($routes) {
        $routes->get('logbaru', 'LogPegawai::logBaru');
        $routes->post('store', 'LogPegawai::storePerPegawai');
        $routes->post('update/(:num)', 'LogPegawai::updatePerPegawai/$1');
        $routes->get('batal/(:segment)', 'LogPegawai::batal/$1');
    });
});

$routes->group('rekammedis', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'RekamMedis::index');
    $routes->group('', ['filter' => 'role:admin'], function($routes) {
        $routes->get('create/(:num)', 'RekamMedis::create/$1');
        $routes->post('store', 'RekamMedis::store');
        $routes->get('view/(:num)', 'RekamMedis::view/$1');
        $routes->get('delete/(:num)', 'RekamMedis::delete/$1');
    });
});







