<?php
    $detailLoker    =   $data['detailLoker'];
    $detailMitra    =   $data['detailMitra'];
    $listApplier    =   $data['listApplier'];

    $judulLoker     =   $detailLoker['judul'];

    $namaMitra      =   $detailMitra['nama'];

    $jumlahPelamar  =   count($listApplier);
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class='mb-0'><?=$judulLoker?></h5>
                        <span class="text-sm text-muted"><?=$namaMitra?></span>
                    </div>
                    <div class="col text-right">
                        <h5><?=$jumlahPelamar?> Pelamar</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id='tabelListLoker' class='table table-sm'>
                        <thead>
                            <tr>
                                <th class='text-center' width='75'>No.</th>
                                <th style='max-width: 600px;'>Pelamar</th>
                                <th>Kontak</th>
                                <th class='text-center'>Act</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($listApplier as $index => $applier){ ?>
                                <?php
                                    $nomorUrut      =   $index+1;

                                    $detailApplier  =   $applier['kandidat'];

                                    $idKandidat             =   $detailApplier['id'];
                                    $fotoKandidat           =   $detailApplier['foto'];
                                    $namaKandidat           =   $detailApplier['nama'];
                                    $alamatKandidat         =   $detailApplier['alamat'];
                                    $tanggalLahirKandidat   =   $detailApplier['tanggalLahir'];
                                    $emailKandidat          =   $detailApplier['email'];
                                    $teleponKandidat        =   $detailApplier['telepon'];
                                ?>
                                <tr>
                                    <td class='text-center'><b><?=$nomorUrut?>.</b></td>
                                    <td class='text-left'>
                                        <div class="row">
                                            <a href="<?=$fotoKandidat?>">
                                                <img src="<?=$fotoKandidat?>" class='img-50-50 img-circle' alt="<?=$namaKandidat?>"
                                                    onError='this.src="<?=base_url(assetsFolder('img/empty.png'))?>"'
                                                    style='object-fit: cover;' />
                                            </a>
                                            <div class="col ml-2">
                                                <h6 class='my-1'><?=$namaKandidat?></h6>
                                                <p class="text-sm text-muted mb-1"><?=$alamatKandidat?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-left">
                                        <div class="text-sm">
                                            <p class="text-sm text-muted mb-1">
                                                <span class='fa fa-envelope mr-2'></span>
                                                <a href="mailto:<?=$emailKandidat?>"><?=$emailKandidat?></a>
                                            </p>
                                            <p class="text-sm text-muted mb-1">
                                                <span class='fa fa-phone mr-2'></span>
                                                <?=$teleponKandidat?>
                                            </p>
                                        </div>
                                    </td>
                                    <td class='text-center'>
                                        <a href="<?=site_url(mitraController('kandidat/cv'))?>/<?=$idKandidat?>" target="_blank">
                                            <img src="<?=base_url(assetsFolder('icon/download.svg'))?>" alt="<?=$namaKandidat?>"
                                                title='Downlad CV <?=$namaKandidat?>' class='img-icon cp' />
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>