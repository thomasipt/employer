<?php
    $pageTitle  =   (isset($pageTitle))? $pageTitle : null;

    $headData   =   [
        'pageTitle' =>  'Home',
        'pageDesc'  =>  'Halaman Awal Mitra'
    ];

    $jumlahLoker            =   $data['jumlahLoker'];
    $paketAktif             =   $data['paketAktif'];
    $jumlahHistoryTransaksi =   $data['jumlahHistoryTransaksi'];
    $jumlahTransaksiPending =   $data['jumlahTransaksiPending'];
    $jumlahKandidat         =   $data['jumlahKandidat'];
?>
<!DOCTYPE html>
<html lang="en">
    <?=view(mitraComponents('head'), $headData)?>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?=view(mitraComponents('navbar'))?>
            <?=view(mitraComponents('sidebar'))?>

            <div class="content-wrapper">
                <?=view(mitraComponents('page-header'), $headData)?>
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-2 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3><?=number_format($jumlahLoker)?></h3>
                                        <p>Jumlah Loker</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-clipboard"
                                            title='Jumlah Fasilitas'></i>
                                    </div>
                                    <a href="<?=site_url(mitraController('loker'))?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-2 col-6">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3><?=(!empty($paketAktif))? $paketAktif : 'Belum Ada'?></h3>
                                        <p>Paket Aktif</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-android-calendar"
                                            title='Jumlah Administrator'></i>
                                    </div>
                                    <a href="#" class='small-box-footer' style='background-color: transparent;'>&nbsp;</a>
                                </div>
                            </div>
                            <div class="col-lg-2 col-6">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3><?=number_format($jumlahHistoryTransaksi)?></h3>
                                        <p>Transaksi Berhasil</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-android-checkbox"
                                            title='Jumlah Penyuluh Terdaftar'></i>
                                    </div>
                                    <a href="#" class='small-box-footer' style='background-color: transparent;'>&nbsp;</a>
                                </div>
                            </div>
                            <div class="col-lg-2 col-6">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3><?=($jumlahTransaksiPending >= 1)? number_format($jumlahTransaksiPending) : 'Tidak Ada'?></h3>
                                        <p>Transaksi Pending</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-android-alert"
                                            title='Jumlah Dinas Terdaftar'></i>
                                    </div>
                                    <a href="<?=site_url(mitraController('transaksi/pending'))?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-2 col-6">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <h3><?=number_format($jumlahKandidat)?></h3>
                                        <p>Jumlah Kandidat</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-person-stalker"
                                            title='Jumlah Kandiat'></i>
                                    </div>
                                    <a href="#" class='small-box-footer' style='background-color: transparent;'>&nbsp;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <?=view(mitraComponents('footer'))?>
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
