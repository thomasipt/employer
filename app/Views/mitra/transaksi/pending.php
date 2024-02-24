<?php
    $transaksiModel         =   model('Transaksi');

    $listTransaksiPending   =   $data['listTransaksiPending'];
?>
<div class="row">
    <?php if(count($listTransaksiPending) >= 1){ ?>
        <?php foreach($listTransaksiPending as $transaksiPending){ ?>
            <?php 
                $nomorTransaksi         =   $transaksiPending['nomor'];
                $tagihanTransaksi       =   $transaksiPending['harga'];
                $ppnTransaksi           =   $transaksiPending['ppn'];
                $buktiBayar             =   $transaksiPending['buktiBayar'];
                $approvementTransaksi   =   $transaksiPending['approvement'];

                $paketTransaksi     =   $transaksiPending['paket'];
                $namaPaket          =   $paketTransaksi['nama'];
                $durasiPaket        =   $paketTransaksi['durasi'];
                $keteranganPaket    =   $paketTransaksi['keterangan'];

                $totalTransaksi =   $tagihanTransaksi + $ppnTransaksi;
            ?>
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class='mb-3'><?=$nomorTransaksi?></h5>
                        <div class="row">
                            <div class="col mr-3">
                                <h6>Paket <?=$namaPaket?> <?=number_format($durasiPaket)?> Hari</h6>
                                <h6 class='text-success mb-1 mt-1'>Rp. <?=number_format($tagihanTransaksi)?></h6>
                                <p class="text-sm text-muted">PPN <?=number_format($ppnTransaksi)?></p>
                            </div>
                            <h5><b class='text-success'>Rp. <?=number_format($totalTransaksi)?> ,-</b></h5>
                        </div>
                        <?php if(empty($buktiBayar)){ ?>
                            <div class="border-warning bg-warning text-sm mb-3 px-3 py-2"
                                style='border: 1px solid; border-radius: 5px;'>
                                Belum upload bukti bayar. <span class="fa fa-info-circle ml-1" title='Segera lakukan pembayaran dan konfirmasi pembayaran dengan mengupload bukti bayar'></span>
                            </div>
                        <?php }else{ ?>
                            <img src="<?=base_url(uploadGambarBuktiBayar($buktiBayar))?>" alt="<?=$buktiBayar?>"
                                class='w-100 img-thumbnail' />
                        <?php } ?>
                        <?php if(!empty($keteranganPaket)){ ?>
                            <p class="text-sm text-muted mb-0 mt-3"><?=$keteranganPaket?></p>
                        <?php } ?>
                        <?php if(!empty($buktiBayar) && $approvementTransaksi == $transaksiModel->approvement_pending){ ?>
                            <div class="border-info bg-info text-sm mt-3 px-3 py-2"
                                style='border: 1px solid; border-radius: 5px;'>
                                Bukti bayar sudah diterima dan admin akan segera mengaktifkan paket anda</span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>
<script src='<?= base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.js')) ?>'></script>
<script src="<?=base_url(assetsFolder('plugins/numeral/numeral.js'))?>"></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.css'))?>" />

<script src='<?=base_url(assetsFolder('custom/js/custom-alert.js'))?>'></script>
<script src='<?=base_url(assetsFolder('custom/js/date-converter.js'))?>'></script>

<script src='<?= base_url(assetsFolder('plugins/datatables/jquery.dataTables.min.js')) ?>'></script>
<script src='<?= base_url(assetsFolder('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')) ?>'></script>
<link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')) ?>" />