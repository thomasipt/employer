<?php
    $paketModel =   $data['paketModel'];
    $listPaket  =   $data['listPaket'];
?>
<div class="row">
    <?php foreach($listPaket as $paketItem){ ?>
        <?php
            $codePaket          =   $paketItem['code'];
            $fotoPaket          =   $paketItem['foto'];
            $namaPaket          =   $paketItem['nama'];
            $durasiPaket        =   $paketItem['durasi'];
            $hargaPaket         =   $paketItem['harga'];
            $keteranganPaket    =   $paketItem['keterangan'];

            $color  =   $paketModel->color[$codePaket];
        ?>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class='mb-3'><span style='color: <?=$color?>'><b><?=$namaPaket?></b></span> <span class="badge badge-info"><?=$codePaket?></span></h5>
                    <h4><b style='color: <?=$color?>'>Rp. <?=number_format($hargaPaket)?></b> </h4>
                    <h5 class="text-muted"><?=$durasiPaket?> Hari</h5>
                    <img src="<?=base_url(uploadGambarPaket($fotoPaket))?>" 
                        class="img-fluid my-3" alt="<?=$namaPaket?>" 
                        onError='this.src="<?=base_url(assetsFolder('img/empty.png'))?>"' />
                    <?php if(!empty($keteranganPaket)){ ?>
                        <p class="text-sm text-muted mb-0 mt-3"><?=$keteranganPaket?></p>
                    <?php } ?>
                    <hr />
                    <a href='<?=site_url(mitraController('transaksi/checkout'))?>/<?=$codePaket?>' target='_blank'>
                        <button class="btn btn-success btn-block">
                            <span class="fa fa-dollar-sign mr-1"></span> Beli
                        </button>
                    </a>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<script src='<?= base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.js')) ?>'></script>
<script src="<?=base_url(assetsFolder('plugins/numeral/numeral.js'))?>"></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.css'))?>" />

<script src='<?=base_url(assetsFolder('custom/js/date-converter.js'))?>'></script>

<script src='<?= base_url(assetsFolder('plugins/datatables/jquery.dataTables.min.js')) ?>'></script>
<script src='<?= base_url(assetsFolder('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')) ?>'></script>
<link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')) ?>" />