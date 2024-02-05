<?php
    $id         =   $transaksi['id'];
    $buktiBayar =   $transaksi['buktiBayar'];
    $nomor      =   $transaksi['nomor'];
    $createdAt  =   $transaksi['createdAt'];
    
    $mitra      =   $transaksi['mitra'];
    $namaMitra      =   $mitra['nama'];
    $fotoMitra      =   $mitra['foto'];
    $alamatMitra    =   $mitra['alamat'];
?>
<div class="col-lg-3">
    <div class="card">
        <div class="card-body text-left">
            <?php if(!empty($buktiBayar)){ ?>
                <a href='<?=base_url(uploadGambarBuktiBayar($buktiBayar))?>' target='_blank'>
                    <img src='<?=base_url(uploadGambarBuktiBayar($buktiBayar))?>' class='w-100 d-block m-auto' alt='<?=$nomor?>'
                        title='Bukti Bayar' />
                </a>
                <br />
            <?php } ?>
            <div class="text-left mt-3">
                <div class='row'>
                    <img src='<?=base_url(uploadGambarMitra('compress'))?>/<?=$fotoMitra?>' class='img-50-50 img-circle' alt='<?=$namaMitra?>'
                        style='object-fit: cover;' />
                    <div class="col ml-2">
                        <h6 class='mb-1'><?=$namaMitra?></h6>
                        <p class="text-sm text-muted"><?=$alamatMitra?></p>
                    </div>
                </div>
            </div>
            <div class="text-left mt-3">
                <label for="nomorTransaksi" class='mb-1 text-sm'>Nomor Transaksi</label>
                <p id="nomorTransaksi" class='text-sm text-muted mb-1'><?=$nomor?></p>
            </div>
            <div class="text-left mt-3">
                <label for="tanggalPembelian" class='mb-1 text-sm'>Tanggal Pembelian</label>
                <p id="tanggalPembelian" class='text-sm text-muted mb-1'><?=formattedDateTime($createdAt)?></p>
            </div>
            <br />
            <?php if(!empty($buktiBayar)){ ?>
            <div class="text-center">
                <button class="btn btn-success"
                    data-id='<?=$id?>'
                    data-nomor='<?=$nomor?>'
                    data-url='<?=base_url(adminController('transaksi/approvement'))?>/<?=$id?>'
                    onClick='_onApprove(this, "<?=$transaksiModel->approvement_approved?>", refresh)'>Approve</button>
                <button class="btn btn-outline-danger" 
                    data-id='<?=$id?>'
                    data-nomor='<?=$nomor?>'
                    data-url='<?=base_url(adminController('transaksi/approvement'))?>/<?=$id?>'
                    onClick='_onApprove(this, "<?=$transaksiModel->approvement_rejected?>", refresh)'>Reject</button>
            </div>
            <?php }else{ ?>
                <h6 class='text-muted'>Belum upload bukti bayar</h6>
            <?php } ?>
        </div>
    </div>
</div>