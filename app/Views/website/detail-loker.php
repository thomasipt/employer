<?php
    $detailLoker        =   $data['detailLoker'];
    $detailPerusahaan   =   $data['detailPerusahaan'];
    $sektorPerusahaan   =   $data['sektorPerusahaan'];

    $fotoPerusahaan     =   $detailPerusahaan['foto'];
    $namaPerusahaan     =   $detailPerusahaan['nama'];
    $alamatPerusahaan   =   $detailPerusahaan['alamat'];
    $jumlahKaryawanPerusahaan   =   $detailPerusahaan['total_employe'];

    $namaSektorPerusahaan       =   $sektorPerusahaan['nama'];

    $judulLoker =   $detailLoker['judul'];
    $kotaLoker  =   $detailLoker['namaKota'];
    $namaJenisLoker  =   $detailLoker['namaJenis'];
    $deskripsiLoker =   $detailLoker['deskripsi'];
    $tanggalPostingLoker    =   $detailLoker['createdAt'];
    $keteranganLoker        =   (array_key_exists('keterangan', $detailLoker))? $detailLoker['keterangan'] : null;
    $kualifikasiLoker        =   $detailLoker['kualifikasi'];
    $benefitLoker           =   $detailLoker['benefit'];
    $gajiMinimumLoker       =   $detailLoker['gajiMinimum'];
    $gajiMaximumLoker       =   $detailLoker['gajiMaximum'];
    $batasAwalPendaftaranLoker       =   $detailLoker['batasAwalPendaftaran'];
    $batasAkhirPendaftaranLoker       =   $detailLoker['batasAkhirPendaftaran'];
?>
<div class="container">
    <div class="row">
        <div class='col-8' id='detailLoker'>
            <div class="post-box mb-3">
                <h5 class='mb-3'><b><?=$judulLoker?></b></h5>
                <div class="text-sm">
                    <span style='margin-right: 20px;'><i class="ri-building-line mr-2"></i><?=$namaPerusahaan?></span>
                    <span style='margin-right: 20px;'><i class="ri-map-pin-5-line mr-2"></i><?=$kotaLoker?></span>
                    <span style='margin-right: 20px;'><i class="ri-stack-line mr-2"></i><?=$namaSektorPerusahaan?></span>
                </div>
                <hr class='my-4' />
                <div class="row">
                    <div class="col-4">
                        <label class='d-block mb-2' for="gaji"><b>Gaji</b></label>
                        <p class="text-xs">
                            <i class="ri-money-dollar-circle-fill mr-2"></i>Rp. <?=number_format($gajiMinimumLoker)?> s/d Rp. <?=number_format($gajiMaximumLoker)?>
                        </p>
                    </div>
                    <div class="col-4">
                        <label class='d-block mb-2' for="periode"><b>Periode Pendaftaran</b></label>
                        <p class="text-xs">
                            <i class="ri-calendar-line mr-2"></i><?=formattedDate($batasAwalPendaftaranLoker)?> s/d <?=formattedDate($batasAkhirPendaftaranLoker)?>
                        </p>
                    </div>
                    <div class="col-4">
                        <label class='d-block mb-2' for="tipe"><b>Tipe Pekerjaan</b></label>
                        <p class="text-xs">
                            <i class="ri-stack-fill mr-2"></i><?=$namaJenisLoker?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="post-box">
                <p class='text-sm text-muted'>
                    Diposting pada <b><?=formattedDate($tanggalPostingLoker)?></b>
                </p>
                <?php if(!empty($keteranganLoker)){ ?>
                    <?=$keteranganLoker?>
                    <br />
                <?php } ?>
                <h6><b>Deskripsi Pekerjaan</b></h6>
                <?=$deskripsiLoker?>
                <br />
                <h6><b>Kualifikasi Pekerjaan</b></h6>
                <?=$kualifikasiLoker?>
                <br />
                <h6><b>Benefit Pekerjaan</b></h6>
                <?=$benefitLoker?>
                <br />
            </div>
        </div>
        <div class="col-4" id='profilePerusahaan'>
            <div class="post-box">
                <h5 class='text-left'>Profile Perusahaan</h5>
                <div class="post-body">
                    <img src="<?=base_url(uploadGambarMitra($fotoPerusahaan))?>" alt="<?=$namaPerusahaan?>"
                        class='img-perusahaan img-100-100 d-block my-4 m-auto' />
                    <br />
                    <h5><b><?=$namaPerusahaan?></b></h5>
                    <p class="text-sm text-muted"><?=$alamatPerusahaan?></p>
                    <br />
                    <label for="sektor"><b>Sektor Perusahaan</b></label>
                    <p class="text-sm text-muted" id='sektor'><?=$namaSektorPerusahaan?> Orang</p>
                    <label for="jumlahKaryawan"><b>Jumlah Karyawan</b></label>
                    <p class="text-sm text-muted" id='jumlahKaryawan'><?=number_format($jumlahKaryawanPerusahaan)?> Orang</p>
                </div>
            </div>
        </div>
    </div>
</div>
<style tyle='text/css'>
    .post-box{
        box-shadow: 0px 0 30px rgba(1, 41, 112, 0.08);
        transition: 0.3s;
        overflow: hidden;
        padding: 30px;
        border-radius: 8px;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    .img-perusahaan{
        width: 125px;
        height: 125px;
        object-fit: cover; 
        border-radius: 50%;
    }
    .mr-2{
        margin-right: 10px;
    }
</style>