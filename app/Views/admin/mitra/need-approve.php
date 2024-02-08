<?php
    $mitraModel             =   $data['mitraModel'];
    $listMitraNeedApprove   =   $data['listMitraNeedApprove'];
    $mitraSearch            =   $data['mitraSearch'];

    $exist  =   count($listMitraNeedApprove) >= 1;
?>
<div class="row">
    <div class="col">
        <form>
            <label for="mitra">Cari Mitra</label>
            <input type="text" class="text form-control" name='mitra' id='mitra'
                placeholder='Cari mitra dengan nama, alamat, nomor telepon atau email yang berkaitan dengan mitra tersebut' />
        </form>
    </div>
</div>
<hr />
<div class="row">
    <?php if(!empty($mitraSearch)){ ?>
        <?php if($exist){ ?>
            <?php foreach($listMitraNeedApprove as $index => $mitraItem){ ?>
                <?php 
                    $dataMitraCard   =   [
                        'mitra'         =>  $mitraItem,
                        'mitraModel'    =>  $mitraModel
                    ];    
                ?>
                <?=view(adminComponents('mitra/mitra-card'), $dataMitraCard)?>
            <?php } ?>
        <?php }else{ ?>
            <div class="col">
                <div class="d-flex justify-content-center align-middle">
                    <div class="text-center">
                        <img src="<?=base_url(assetsFolder('img/empty.png'))?>" alt="Empty" class='d-block m-auto' />
                        <br />
                        <h5>Approvement Mitra</h5>
                        <p class="text-sm text-muted">Belum ada mitra yang butuh approvement</p>
                        <br />
                        <a href="<?=site_url(adminController())?>">
                            <button class="btn btn-default">Home</button>
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php }else{ ?>
        <div class="col mt-5">
                <div class="d-flex justify-content-center align-center">
                    <div class="text-center">
                        <img src="<?=base_url(assetsFolder('img/search.png'))?>" alt="Search" class='d-block m-auto' />
                        <br />
                        <h5>Approvement Mitra</h5>
                        <p class="text-sm text-muted">Silahkan cari terlebih dahulu mitra yang akan diapprove</p>
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
<script src='<?=base_url(assetsFolder('custom/js/approvement-mitra.js'))?>'></script>

<link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')) ?>" />
<script language='Javascript'>
    let _approvementApproved    =   `<?=$mitraModel->approvement_approved?>`;
    let _approvementRejected    =   `<?=$mitraModel->approvement_rejected?>`;

    function refresh(){
        location.reload();
    }
</script>