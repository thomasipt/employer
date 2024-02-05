<?php
    $transaksiModel     =   $data['transaksiModel'];
    $listPembelian      =   $data['listPembelian'];

    $exist  =   count($listPembelian) >= 1;
?>
<div class="row">
    <?php if($exist){ ?>
        <?php foreach($listPembelian as $index => $transaksiItem){ ?>
            <?php 
                $dataMitraCard   =   [
                    'transaksi'         =>  $transaksiItem,
                    'transaksiModel'    =>  $transaksiModel
                ];    
            ?>
            <?=view(adminComponents('transaksi/transaksi-card'), $dataMitraCard)?>
        <?php } ?>
    <?php }else{ ?>
        <div class="col">
            <div class="d-flex justify-content-center align-middle">
                <div class="text-center">
                    <img src="<?=base_url(assetsFolder('img/empty.png'))?>" alt="Empty" class='d-block m-auto' />
                    <br />
                    <h5>Pembelian Paket</h5>
                    <p class="text-sm text-muted">Belum ada pembelian paket (yang butuh approvement)</p>
                    <br />
                    <a href="<?=site_url(adminController())?>">
                        <button class="btn btn-default">Home</button>
                    </a>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<script src='<?= base_url(assetsFolder('plugins/datatables/jquery.dataTables.min.js')) ?>'></script>
<script src='<?= base_url(assetsFolder('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')) ?>'></script>

<script src='<?= base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.js')) ?>'></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.css'))?>" />

<script src='<?=base_url(assetsFolder('custom/js/custom-alert.js'))?>'></script>
<script src='<?=base_url(assetsFolder('custom/js/form-submission.js'))?>'></script>
<script src='<?=base_url(assetsFolder('custom/js/date-converter.js'))?>'></script>
<script src='<?=base_url(assetsFolder('custom/js/approvement-transaksi.js'))?>'></script>

<link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')) ?>" />
<script language='Javascript'>
    let _approvementApproved    =   `<?=$transaksiModel->approvement_approved?>`;
    let _approvementRejected    =   `<?=$transaksiModel->approvement_rejected?>`;

    function refresh(){
        location.reload();
    }
</script>