<?php
    $pageTitle  =   (isset($pageTitle))? $pageTitle : null;

    $headData   =   [
        'pageTitle' =>  'Home',
        'pageDesc'  =>  'Halaman Awal Administrator'
    ];

    $jumlahMitra                    =   $data['jumlahMitra'];
    $jumlahLoker                    =   $data['jumlahLoker'];
    $jumlahKandidat                 =   $data['jumlahKandidat'];
    $jumlahPembelianPaket           =   $data['jumlahPembelianPaket'];
?>
<!DOCTYPE html>
<html lang="en">
    <?=view(adminComponents('head'), $headData)?>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?=view(adminComponents('navbar'))?>
            <?=view(adminComponents('sidebar'))?>

            <div class="content-wrapper">
                <?=view(adminComponents('page-header'), $headData)?>
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3><?=number_format($jumlahMitra)?></h3>
                                        <p>Jumlah Mitra</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-android-playstore"
                                            title='Jumlah Mitra'></i>
                                    </div>
                                    <a href="<?=site_url(adminController('mitra'))?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3><?=number_format($jumlahLoker)?></h3>
                                        <p>Lowongan Pekerjaan Premium</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-android-person"
                                            title='Jumlah Loker Premium'></i>
                                    </div>
                                    <a href="#" class='small-box-footer' style='background-color: transparent;'>&nbsp;</a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3><?=number_format($jumlahKandidat)?></h3>
                                        <p>Jumlah Kandidat</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-person-add"
                                            title='Jumlah Penyuluh Terdaftar'></i>
                                    </div>
                                    <a href="#" class='small-box-footer' style='background-color: transparent;'>&nbsp;</a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3><?=number_format($jumlahPembelianPaket)?></h3>
                                        <p>Jumlah Pembelian Paket</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-android-people"
                                            title='Jumlah Dinas Terdaftar'></i>
                                    </div>
                                    <a href="<?=site_url(adminController('transaksi/pending'))?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <?=view(adminComponents('footer'))?>
        </div>
    </body>
</html>
<link rel="stylesheet" href='https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css' />
<script language='Javascript'>
    let _logoURL   =   `<?=base_url('assets/img/icon.png')?>`;
    function _handleErrorImage(imageElement){
        let _el =   $(imageElement);
        _el.attr('src', _logoURL);
    }
</script>
