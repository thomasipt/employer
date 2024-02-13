<?php 
    $request                =   request();
    $appConfig              =   config('Config\App');
    
    $detailMitra    =   $request->mitra;
    $foto           =   $detailMitra['foto'];
    $nama           =   $detailMitra['nama'];
    $username       =   $detailMitra['username'];
    $id             =   $detailMitra['id'];

    $uri                    =   $request->getUri();
    $currentController      =   $uri->getSegment(2);
    $currentControllerLC    =   strtolower($currentController);

    $appName    =   $appConfig->appName;
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
                <img src="<?= base_url(uploadGambarMitra('compress/'.$foto)) ?>" 
                    class="img-circle elevation-2" alt="User Image" 
                    style='width:2.85rem; height: 2.85rem; object-fit: cover;' />
            </div>
            <div class="info">
                <a href="#" class="d-block">
                    <h5 class='mb-0 text-white'><?=$nama?></h5>
                </a>
                <p class="text-sm mb-1" style='color:#c8c8c8'><?=$username?></p>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="<?=site_url(mitraController())?>" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Home</p>
                    </a>
                </li>
                <li class="nav-item <?=($currentController == 'loker')? 'menu-is-opening menu-open' : ''?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-bullhorn text-warning"></i>
                        <p>
                            Loker
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item ml-3">
                            <a href="<?=site_url(mitraController('loker'))?>" class="nav-link text-sm">
                                <i class="fa fa-layer-group nav-icon"></i>
                                <p>List Loker</p>
                            </a>
                        </li>
                        <li class="nav-item ml-3">
                            <a href="<?=site_url(mitraController('loker/add'))?>" class="nav-link text-sm">
                                <i class="fa fa-plus-circle nav-icon"></i>
                                <p>Add Loker</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item <?=($currentController == 'transaksi')? 'menu-is-opening menu-open' : ''?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-shopping-cart text-primary"></i>
                        <p>
                            Transaksi
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item ml-3">
                            <a href="<?=site_url(mitraController('transaksi'))?>" class="nav-link text-sm">
                                <i class="fa fa-layer-group nav-icon"></i>
                                <p>Histori Transaksi</p>
                            </a>
                        </li>
                        <li class="nav-item ml-3">
                            <a href="<?=site_url(mitraController('transaksi/pilihan-paket'))?>" class="nav-link text-sm">
                                <i class="fa fa-layer-group nav-icon"></i>
                                <p>Pilihan Paket</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="<?=site_url(mitraController('log'))?>" class="nav-link">
                        <i class="nav-icon fas fa-history text-info"></i>
                        <p>Log Mitra</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>