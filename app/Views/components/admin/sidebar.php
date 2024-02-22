<?php 
    $request                =   request();
    $administratorModel     =   model('Administrator');
    $mitraModel             =   model('Mitra');
    $transaksiModel         =   model('Transaksi');
    $appConfig              =   config('Config\App');
    
    $detailAdministrator    =   $request->administrator;
    $foto       =   $detailAdministrator['foto'];
    $nama       =   $detailAdministrator['nama'];
    $username   =   $detailAdministrator['username'];
    $id         =   $detailAdministrator['id'];

    $uri                    =   $request->getUri();
    $currentController      =   $uri->getSegment(2);
    $currentControllerLC    =   strtolower($currentController);

    $appName    =   $appConfig->appName;

    $listControllerMasterData   =   ['jenis-loker', 'kategori-loker'];
    $masterData                 =   in_array($currentController, $listControllerMasterData);
    
    $jumlahMitraButuhVerifikasi     =   $mitraModel->getJumlahMitraButuhVerifikasi();
    $jumlahTransaksiButuhVerifikasi =   $transaksiModel->getJumlahTransaksiPending();
?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="<?= base_url(assetsFolder('img/icon.png')) ?>" alt="<?=$appName?>" class="brand-image img-circle elevation-3" />
        <span class="brand-text font-weight-light"><?=$appName?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image pt-1">
                <img src="<?= base_url(uploadGambarAdmin('compress/'.$foto)) ?>" 
                    class="img-circle elevation-2" alt="User Image" 
                    style='width:2.85rem;' />
            </div>
            <div class="info">
                <a href="#" class="d-block">
                    <h5 class='mb-0 text-white'><?=$nama?></h5>
                </a>
                <p class="text-sm mb-0" style='color:#c8c8c8'><?=$username?></p>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="<?=site_url(adminController())?>" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Home</p>
                    </a>
                </li>
                <li class="nav-item <?=($masterData)? 'menu-is-opening menu-open' : ''?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                            Master Data
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item <?=($currentController == 'jenis-loker')? 'menu-is-opening menu-open' : ''?> ml-3">
                            <a href="<?=site_url(adminController('jenis-loker'))?>" class="nav-link text-sm">
                                <i class="far fa-circle nav-icon text-warning"></i>
                                <p>Jenis Loker</p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ml-3">
                                    <a href="<?=site_url(adminController('jenis-loker'))?>" class="nav-link text-sm">
                                        <i class="fa fa-layer-group nav-icon"></i>
                                        <p>List Jenis Loker</p>
                                    </a>
                                    <a href="<?=site_url(adminController('jenis-loker/add'))?>" class="nav-link text-sm">
                                        <i class="fa fa-plus-circle nav-icon"></i>
                                        <p>Add Jenis Loker</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class='nav-item <?=($currentController == 'kategori-loker')? 'menu-is-opening menu-open' : ''?> ml-3'>
                            <a href="<?=site_url(adminController('kategori-loker'))?>" class="nav-link text-sm">
                                <i class="far fa-circle nav-icon text-info"></i>
                                <p>Kategori Loker</p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ml-3">
                                    <a href="<?=site_url(adminController('kategori-loker'))?>" class="nav-link text-sm">
                                        <i class="fa fa-layer-group nav-icon"></i>
                                        <p>List Kategori Loker</p>
                                    </a>
                                    <a href="<?=site_url(adminController('kategori-loker/add'))?>" class="nav-link text-sm">
                                        <i class="fa fa-plus-circle nav-icon"></i>
                                        <p>Add Kategori Loker</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-item <?=($currentController == 'mitra')? 'menu-is-opening menu-open' : ''?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-user"></i>
                        <p>
                            Mitra
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item ml-3">
                            <a href="<?=site_url(adminController('mitra'))?>" class="nav-link text-sm">
                                <i class="fa fa-layer-group nav-icon text-info"></i>
                                <p>List Mitra</p>
                            </a>
                        </li>
                        <li class="nav-item ml-3">
                            <a href="<?=site_url(adminController('mitra/need-approve'))?>" class="nav-link text-sm">
                                <i class="fa fa-layer-group nav-icon text-success"></i>
                                <p>Verifikasi Mitra <span class="badge badge-warning ml-1"><?=number_format($jumlahMitraButuhVerifikasi)?></span></p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item <?=($currentController == 'transaksi')? 'menu-is-opening menu-open' : ''?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-shopping-cart"></i>
                        <p>
                            Transaksi
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item ml-3">
                            <a href="<?=site_url(adminController('transaksi/mitra'))?>/" class="nav-link text-sm">
                                <i class="fa fa-layer-group nav-icon text-info"></i>
                                <p>Transaksi Mitra</p>
                            </a>
                        </li>
                        <li class="nav-item ml-3">
                            <a href="<?=site_url(adminController('transaksi/pending'))?>" class="nav-link text-sm">
                                <i class="fa fa-layer-group nav-icon text-success"></i>
                                <p>Pembelian Paket <span class="badge badge-warning ml-1"><?=number_format($jumlahTransaksiButuhVerifikasi)?></span></p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item <?=($currentController == 'loker')? 'menu-is-opening menu-open' : ''?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                            Lowongan Pekerjaan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item ml-3">
                            <a href="<?=site_url(adminController('loker/mitra'))?>/" class="nav-link text-sm">
                                <i class="fa fa-layer-group nav-icon text-info"></i>
                                <p>Lowongan Kerja Mitra</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item <?=($currentController == 'website')? 'menu-is-opening menu-open' : ''?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-globe"></i>
                        <p>
                            Website
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item ml-3">
                            <a href="<?=site_url(adminController('website/landing-page/hero'))?>/" class="nav-link text-sm">
                                <i class="fa fa-layer-group nav-icon text-info"></i>
                                <p>Hero Slider</p>
                            </a>
                        </li>
                        <li class="nav-item ml-3">
                            <a href="<?=site_url(adminController('website/landing-page/about-us'))?>/" class="nav-link text-sm">
                                <i class="fa fa-layer-group nav-icon text-primary"></i>
                                <p>About Us</p>
                            </a>
                        </li>
                        <li class="nav-item ml-3">
                            <a href="<?=site_url(adminController('website/landing-page/features'))?>/" class="nav-link text-sm">
                                <i class="fa fa-layer-group nav-icon text-white"></i>
                                <p>Fitur</p>
                            </a>
                        </li>
                        <li class="nav-item ml-3">
                            <a href="<?=site_url(adminController('website/landing-page/whatsapp'))?>/" class="nav-link text-sm">
                                <i class="fab fa-whatsapp nav-icon text-success"></i>
                                <p>Whatsapp</p>
                            </a>
                        </li>
                        <li class="nav-item ml-3">
                            <a href="<?=site_url(adminController('website/landing-page/paket'))?>/" class="nav-link text-sm">
                                <i class="fa fa-layer-group nav-icon text-warning"></i>
                                <p>Paket</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="<?=site_url(adminController('log'))?>" class="nav-link">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Log Administrator</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>