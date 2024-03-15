<?php
    $jumlahLoker                =   $data['jumlahLoker'];
    $jumlahTransaksiBerhasil    =   $data['jumlahTransaksiBerhasil'];
    $jumlahTransaksiGagal       =   $data['jumlahTransaksiGagal'];
    $paketAktif                 =   $data['paketAktif'];
?>
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?=number_format($jumlahLoker)?></h3>
                <p>Jumlah Loker</p>
            </div>
            <div class="icon">
                <i class="ion ion-network"
                    title='Jumlah Fasilitas'></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?=(!empty($paketAktif))? $paketAktif : 'Belum Ada'?></h3>
                <p>Paket Aktif</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-cart"
                    title='Jumlah Administrator'></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?=($jumlahTransaksiBerhasil >= 1)? number_format($jumlahTransaksiBerhasil) : 'Tidak Ada'?></h3>
                <p>Transaksi Berhasil</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-checkmark"
                    title='Jumlah Transaksi Berhasil'></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?=($jumlahTransaksiGagal >= 1)? number_format($jumlahTransaksiGagal) : 'Tidak Ada'?></h3>
                <p>Transaksi Gagal</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-checkmark-empty"
                    title='Jumlah Transaksi Gagal'></i>
            </div>
        </div>
    </div>
</div>
<?=view(mitraView('loker/index'))?>
<?=view(mitraView('transaksi/index'))?>
<script src='<?= base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.js')) ?>'></script>
<script src='<?= base_url(assetsFolder('plugins/numeral/numeral.js')) ?>'></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.css'))?>" />

<script src='<?=base_url(assetsFolder('custom/js/custom-alert.js'))?>'></script>
<script src='<?=base_url(assetsFolder('custom/js/date-converter.js'))?>'></script>

<script src='<?= base_url(assetsFolder('plugins/datatables/jquery.dataTables.min.js')) ?>'></script>
<script src='<?= base_url(assetsFolder('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')) ?>'></script>
<link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')) ?>" />

<link rel="stylesheet" href='https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css' />
