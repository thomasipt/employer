<?php
    helper('cookie');

    $transaksiModel             =   model('Transaksi');
    $request                    =   request();

    $loggedInDetailMitra        =   $request->mitra;
    $loggedInIDMitra            =   $loggedInDetailMitra['id'];

    $jumlahTransaksiPending     =   $transaksiModel->getJumlahTransaksiPending($loggedInIDMitra);
    $showAlertTransaksiPending  =   get_cookie('showAlertTransaksiPending');
    $showAlert                  =   ($showAlertTransaksiPending != null)? $showAlertTransaksiPending == 'true': $jumlahTransaksiPending >= 1;

    if(!isset($pageDesc)){
        $pageDesc   =   null;
    }
?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <?php if($showAlert){ ?>
            <div class="alert alert-warning text-sm alert-dismissible" role='alert'>
                Anda memiliki <b><?=number_format($jumlahTransaksiPending)?> Transaksi Pending</b>, silahkan lakukan pembayaran agar paket anda <b>aktif</b>. 
                Lihat di <a href="<?=site_url(mitraController('transaksi/pending'))?>" target='blank' class='text-primary'>sini</a>
                <button class="close" type='button' data-dismiss='alert'aria-label='Close'
                    onClick='_close()'>
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?=$pageTitle?></h1>
                <?php if(!empty($pageDesc)){ ?>
                    <p class="text-sm text-muted mb-0"><?=$pageDesc?></p>
                <?php } ?>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<script language='Javascript'>
    function _close(){
        document.cookie =   'showAlertTransaksiPending=false';
    }
</script>