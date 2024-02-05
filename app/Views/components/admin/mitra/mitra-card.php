<?php
    $id         =   $mitra['id'];
    $foto       =   $mitra['foto'];
    $nama       =   $mitra['nama'];
    $alamat     =   $mitra['alamat'];
    $email      =   $mitra['email'];
    $telepon    =   $mitra['telepon'];
    $createdAt  =   $mitra['createdAt'];
?>
<div class="col-lg-2">
    <div class="card">
        <div class="card-body text-center">
            <img src='<?=base_url(uploadGambarMitra('compress'))?>/<?=$foto?>' class='img-circle d-block m-auto' alt='<?=$nama?>'
                style='width:125px; height:125px; object-fit:cover;' />
            <br />
            <h4><?=$nama?></h4>
            <p class="text-sm text-muted"><?=$alamat?></p>
            <div class="text-left mt-3">
                <label for="tanggalDaftar" class='mb-1 text-sm'>Tanggal Daftar</label>
                <p id="tanggalDaftar" class='text-sm text-muted mb-1'><?=formattedDateTime($createdAt)?></p>
            </div>
            <div class="text-left">
                <label for="email" class='mb-1 text-sm'>Email</label>
                <p id="email" class='text-sm text-muted mb-1'><?=$email?></p>
            </div>
            <div class="text-left">
                <label for="telepon" class='mb-1 text-sm'>Telepon</label>
                <p id="telepon" class='text-sm text-muted mb-1'><?=$telepon?></p>
            </div>
            <br />
            <div class="text-center">
                <button class="btn btn-success"
                    data-id='<?=$id?>'
                    data-nama='<?=$nama?>'
                    data-url='<?=base_url(adminController('mitra/approvement'))?>/<?=$id?>'
                    onClick='_onApprove(this, "<?=$mitraModel->approvement_approved?>", refresh)'>Approve</button>
                <button class="btn btn-outline-danger" 
                    data-id='<?=$id?>'
                    data-nama='<?=$nama?>'
                    data-url='<?=base_url(adminController('mitra/approvement'))?>/<?=$id?>'
                    onClick='_onApprove(this, "<?=$mitraModel->approvement_rejected?>", refresh)'>Reject</button>
            </div>
        </div>
    </div>
</div>