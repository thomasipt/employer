<?php
    $request    =   request();
    $search     =   $request->getGet('search');

    $listLokerPremium   =   $data['listLokerPremium'];
?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <form>
                <input type="text" class="form-control form-control-lg" placeholder='Cari loker di sini dengan Job Title, dan Deskripsi'
                    name='search' value='<?=(!empty($search))? $search : ''?>' />
            </form>
        </div>
    </div>
    <br />
    <div class="row">
        <?php if(!empty($search)){ ?>
            <h6 class='mb-4'>Hasil Pencarian <b>"<?=$search?>"</b></h6>
        <?php } ?>
        <?php if(count($listLokerPremium) >= 1){ ?>
            <?php foreach($listLokerPremium as $lokerPremium){ ?>
                <?php
                    $idLoker                =   $lokerPremium['id'];
                    $judulLoker             =   $lokerPremium['judul'];
                    $gajiMinimumLoker       =   $lokerPremium['gajiMinimum'];
                    $gajiMaximumLoker       =   $lokerPremium['gajiMaximum'];
                    $tanggalPostingLoker    =   $lokerPremium['createdAt'];

                    $detailPerusahaan   =   $lokerPremium['mitra'];
                    $namaPerusahaan     =   $detailPerusahaan['nama'];
                    $fotoPerusahaan     =   $detailPerusahaan['foto'];

                    $namaKota       =   '-';
                    $namaProvinsi   =   '-';

                    $detailLokasi       =   $lokerPremium['lokasi'];
                    if(!empty($detailLokasi)){
                        $namaKota       =   $detailLokasi['namaKota'];
                        $namaProvinsi   =   $detailLokasi['namaProvinsi'];   
                    }

                    $detailJenisPekerjaan   =   $lokerPremium['jenis'];
                    $namaJenisPekerjaan     =   !empty($detailJenisPekerjaan)? $detailJenisPekerjaan['nama'] : '-';
                ?>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 mb-3">
                        <a href="<?=site_url(websiteController('loker-premium'))?>/<?=base64_encode($idLoker)?>" target='_blank'>
                            <div class="post-box col">
                                <div class="row">
                                    <img src="<?=base_url(uploadGambarMitra('compress'))?>/<?=$fotoPerusahaan?>" alt="<?=$namaPerusahaan?>"
                                        class='img-circle img-perusahaan p-0' />
                                    <div class="col ml-5">
                                        <h4 class="post-title mb-0 text-black"><?=$judulLoker?></h4>
                                        <span class='text-sm text-muted'><?=$namaPerusahaan?></span>
                                    </div>
                                </div>
                                <br />
                                <label class='text-black' for="lokasi"><b>Lokasi</b></label>
                                <p class='text-sm text-muted mb-2' id="lokasi"><?=$namaKota?>, <?=$namaProvinsi?></p>
                                
                                <label class='text-black' for="gaji"><b>Gaji</b></label>
                                <p class='text-sm text-muted mb-2' id="gaji">Rp. <?=number_format($gajiMinimumLoker)?> s/d Rp. <?=number_format($gajiMaximumLoker)?></p>
                                
                                <label class='text-black' for="jenis"><b>Jenis</b></label>
                                <p class='text-sm text-muted mb-2' id="jenis"><?=$namaJenisPekerjaan?></p>

                                <p class="text-sm mt-3 text-black" style='text-align: right;'>Diposting pada <?=formattedDate($tanggalPostingLoker)?></p>
                            </div>
                        </a>
                    </div>
            <?php } ?>
        <?php }else{ ?>
            <p class="text-sm text-muted text-center">
                <img src="<?=base_url(assetsFolder('img/empty.png'))?>" class='d-block m-auto mb-4' alt="Empty"
                    style='width: 125px; height: 125px;' />
                <i><?=(!empty($search))? 'Hasil pencarian tidak ada' : 'Belum ada Lowongan Pekerjaan Premium'?></i>
            </p>
        <?php } ?>
    </div>
</div>
<script src="<?= base_url(assetsFolder('plugins/jquery/jquery.min.js')) ?>/"></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/ionicons/ionicons.min.css'))?>" />

<script src='<?= base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.js')) ?>'></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.css'))?>" />

<script language='Javascript'>

</script>

<style tyle='text/css'>
    .post-box{
        box-shadow: 0px 0 30px rgba(1, 41, 112, 0.08);
        transition: 0.3s;
        height: 100%;
        overflow: hidden;
        padding: 30px;
        border-radius: 8px;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    .post-box:hover{
        transform: scale(1.1);
        box-shadow: 0px 0 30px rgba(1, 41, 112, 0.1);
    }
    .img-perusahaan{
        width: 85px;
        height: 85px;
        object-fit: cover; 
        border-radius: 50%;
    }
</style>