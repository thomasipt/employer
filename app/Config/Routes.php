<?php
    use CodeIgniter\Router\RouteCollection;

    #Library
    use App\Libraries\APIRespondFormat;

    /**
     * @var RouteCollection $routes
     */

    $routes->setAutoRoute(false);

    $routes->get('/', 'Home::index');
    $routes->set404Override(function($exceptionMessage){
        $arf            =   new APIRespondFormat(false, $exceptionMessage, null);
        $apiRespond     =   $arf->getRespond();

        $response       =   response();
        $response->setStatusCode(404);
        $response->setHeader('Content-Type', 'application/json');
        echo json_encode($apiRespond);
    });

    #Administrator
    $routes->group(adminController(), static function($adminRoutes){
        $additionalOptions  =   [
            'namespace' =>  'App\Controllers\admin',
            'filter'    =>  'auth-filter'
        ];

        $adminRoutes->get('', 'Home::index', $additionalOptions);
        $adminRoutes->get('login', 'Auth::index', ['namespace' =>  'App\Controllers\admin']);
        $adminRoutes->post('auth-process', 'Auth::authProcess', ['namespace' =>  'App\Controllers\admin']);
        $adminRoutes->get('logout', 'Auth::logout', ['namespace' => 'App\Controllers\admin']);
        $adminRoutes->get('not-authorized', 'Auth::notAuthorized', ['namespace' => 'App\Controllers\admin']);

        $adminRoutes->get('lupa-password', 'Auth::lupaPassword', ['namespace' => 'App\Controllers\admin']);
        $adminRoutes->post('lupa-password', 'Auth::lupaPassword', ['namespace' => 'App\Controllers\admin']);

        #Log
        $adminRoutes->group('log', static function($logRoutes){
            $additionalOptions  =   [
                'namespace' =>  'App\Controllers\admin',
                'filter'    =>  'auth-filter'
            ];

            $logRoutes->get('', 'AdministratorLog::listLog', $additionalOptions);
            $logRoutes->get('list-log', 'AdministratorLog::listLog', $additionalOptions);
            $logRoutes->get('get-list-log', 'AdministratorLog::getListLog', $additionalOptions);
        });

        #JenisLoker
        $adminRoutes->group('jenis-loker', static function($jenisLokerRoutes){
            $additionalOptions  =   [
                'namespace' =>  'App\Controllers\admin',
                'filter'    =>  'auth-filter'
            ];

            $jenisLokerRoutes->get('', 'JenisLoker::index', $additionalOptions);
            $jenisLokerRoutes->get('get-list-jenis-loker', 'JenisLoker::getListJenisLoker', $additionalOptions);

            $jenisLokerRoutes->get('add', 'JenisLoker::add', $additionalOptions);
            $jenisLokerRoutes->get('edit/(:num)', 'JenisLoker::add/$1', $additionalOptions);
            $jenisLokerRoutes->post('save', 'JenisLoker::saveJenisLoker', $additionalOptions);
            $jenisLokerRoutes->post('save/(:num)', 'JenisLoker::saveJenisLoker/$1', $additionalOptions);
            $jenisLokerRoutes->post('delete/(:num)', 'JenisLoker::deleteJenisLoker/$1', $additionalOptions);
        });

        #KategoriLoker
        $adminRoutes->group('kategori-loker', static function($kategoriLokerRoutes){
            $additionalOptions  =   [
                'namespace' =>  'App\Controllers\admin',
                'filter'    =>  'auth-filter'
            ];

            $kategoriLokerRoutes->get('', 'KategoriLoker::index', $additionalOptions);
            $kategoriLokerRoutes->get('get-list-kategori-loker', 'KategoriLoker::getListKategoriLoker', $additionalOptions);

            $kategoriLokerRoutes->get('add', 'KategoriLoker::add', $additionalOptions);
            $kategoriLokerRoutes->get('edit/(:num)', 'KategoriLoker::add/$1', $additionalOptions);
            $kategoriLokerRoutes->post('save', 'KategoriLoker::saveKategoriLoker', $additionalOptions);
            $kategoriLokerRoutes->post('save/(:num)', 'KategoriLoker::saveKategoriLoker/$1', $additionalOptions);
            $kategoriLokerRoutes->post('delete/(:num)', 'KategoriLoker::deleteKategoriLoker/$1', $additionalOptions);
        });

        #Mitra
        $adminRoutes->group('mitra', static function($mitraRoutes){
            $additionalOptions  =   [
                'namespace' =>  'App\Controllers\admin',
                'filter'    =>  'auth-filter'
            ];

            $mitraRoutes->get('', 'Mitra::index', $additionalOptions);
            $mitraRoutes->get('get-list-mitra', 'Mitra::getListMitra', $additionalOptions);
            $mitraRoutes->get('need-approve', 'Mitra::needApprove', $additionalOptions);

            $mitraRoutes->post('approvement/(:num)', 'Mitra::approvement/$1', $additionalOptions);
        });

        #Transaksi
        $adminRoutes->group('transaksi', static function($transaksiRoutes){
            $additionalOptions  =   [
                'namespace' =>  'App\Controllers\admin',
                'filter'    =>  'auth-filter'
            ];

            $transaksiRoutes->get('pending', 'Transaksi::pending', $additionalOptions);
            $transaksiRoutes->post('approvement/(:num)', 'Transaksi::approvement/$1', $additionalOptions);
        });
    });

    #Website
    $routes->group(websiteController(), static function($websiteRoutes){
        $websiteRoutes->get('syarat-dan-ketentuan', 'Home::sk', ['namespace' =>  'App\Controllers\website']);
        $websiteRoutes->get('kebijakan-privasi', 'Home::kebijakanPrivasi', ['namespace' =>  'App\Controllers\website']);
    });

    #Mitra Dashboard
    $routes->group(mitraController(), static function($mitraRoutes){
        $additionalOptions  =   [
            'namespace' =>  'App\Controllers\mitra',
            'filter'    =>  'mitra-filter'
        ];

        $mitraRoutes->get('', 'Home::index', $additionalOptions); #ok
        $mitraRoutes->get('login', 'Auth::index', ['namespace' =>  'App\Controllers\mitra']); #ok
        $mitraRoutes->post('auth-process', 'Auth::authProcess', ['namespace' =>  'App\Controllers\mitra']); #ok
        $mitraRoutes->get('logout', 'Auth::logout', ['namespace' => 'App\Controllers\mitra']); #ok
        $mitraRoutes->get('not-authorized', 'Auth::notAuthorized', ['namespace' => 'App\Controllers\mitra']);

        $mitraRoutes->get('lupa-password', 'Auth::lupaPassword', ['namespace' => 'App\Controllers\mitra']); #ok
        $mitraRoutes->post('lupa-password', 'Auth::lupaPassword', ['namespace' => 'App\Controllers\mitra']); #ok

        #log
        $mitraRoutes->group('log', static function($logRoutes){
            $additionalOptions  =   [
                'namespace' =>  'App\Controllers\mitra',
                'filter'    =>  'mitra-filter'
            ];

            $logRoutes->get('', 'MitraLog::listLog', $additionalOptions);
            $logRoutes->get('list-log', 'MitraLog::listLog', $additionalOptions);
            $logRoutes->get('get-list-log', 'MitraLog::getListLog', $additionalOptions);
        });

        #loker
        $mitraRoutes->group('loker', static function($lokerRoutes){
            $additionalOptions  =   [
                'namespace' =>  'App\Controllers\mitra',
                'filter'    =>  'mitra-filter'
            ];

            $lokerRoutes->get('', 'Loker::listLoker', $additionalOptions);
            $lokerRoutes->get('list-loker', 'Loker::listLoker', $additionalOptions);
            $lokerRoutes->get('get-list-loker', 'Loker::getListLoker', $additionalOptions);
            
            $lokerRoutes->get('add', 'Loker::addLoker', $additionalOptions);
            $lokerRoutes->get('edit/(:num)', 'Loker::addLoker/$1', $additionalOptions);
            $lokerRoutes->post('save', 'Loker::saveLoker', $additionalOptions);
            $lokerRoutes->post('save/(:num)', 'Loker::saveLoker/$1', $additionalOptions);
            $lokerRoutes->post('delete/(:num)', 'Loker::deleteLoker/$1', $additionalOptions);
        });

        #transaksi
        $mitraRoutes->group('transaksi', static function($transaksiRoutes){
            $additionalOptions  =   [
                'namespace' =>  'App\Controllers\mitra',
                'filter'    =>  'mitra-filter'
            ];

            $transaksiRoutes->get('', 'Transaksi::listTransaksi', $additionalOptions);
            $transaksiRoutes->get('get-list-transaksi', 'Transaksi::getListTransaksi', $additionalOptions);
            $transaksiRoutes->get('checkout/(:alpha)', 'Transaksi::checkout/$1', $additionalOptions);
            $transaksiRoutes->post('checkout/(:alpha)', 'Transaksi::processCheckout/$1', $additionalOptions);
            $transaksiRoutes->post('upload-bukti-bayar', 'Transaksi::uploadBuktiBayar', $additionalOptions);
            $transaksiRoutes->get('invoice/(:num)', 'Transaksi::invoice/$1', $additionalOptions);
            $transaksiRoutes->get('pilihan-paket', 'Transaksi::pilihanPaket/$1', $additionalOptions);
        });
    });

    #Lainnya
    $routes->group('ajax', static function($ajaxRoutes){
        $ajaxRoutes->get('get-provinsi', 'AJAX::getProvinsi');
        $ajaxRoutes->get('get-kota-per-provinsi/(:num)', 'AJAX::getKotaPerProvinsi/$1');
    });