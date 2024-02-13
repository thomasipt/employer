<?php
    helper('CustomDate');

    $namaKandidat           =   $detailKandidat['nama'];
    $fotoKandidat           =   $detailKandidat['foto'];
    $alamatKandidat         =   $detailKandidat['alamat'];
    $nomorTeleponKandidat   =   $detailKandidat['telepon'];
    $emailKandidat          =   $detailKandidat['email'];
    $namaKotaKandidat       =   $detailKandidat['namaKota'];

    function circleIcon($index){
        $circleImage    =   'red-circle.png';
        if($index % 4 == 1){
            $circleImage    =   'green-circle.png';
        }
        if($index % 4 == 2){
            $circleImage    =   'blue-circle.png';
        }
        if($index % 4 == 3){
            $circleImage    =   'yellow-circle.png';
        }

        return $circleImage;
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>CV <?=$namaKandidat?></title>
        <style type='text/css'>
            @font-face {
                font-family: Montserrat;
                src: url(<?=base_url(assetsFolder('fonts/Montserrat/static/Montserrat-Regular.ttf'))?>);
            }
            .row, .table{ 
                width:100%;
                margin-top: 10px;
                margin-bottom: 10px;  
            }
            .row::after{
                clear: both;
            }
            .col{width:50%;float:left;}
            .col-4{width:33.333333334%;float:left;}
            .col-6{width:50%;float:left;}
            .col-12{width:100%;float:left;}

            table{
                margin: 10px;
            }
            td{
                padding:5px;
            }
            caption { 
            display: table-caption;
            text-align: left;
            }
            .full-right{
                float: right;
                text-align: right;
            }
            table.minimalize-td td{
                padding-bottom:3.5px;
                padding-top:2.5px;
            }
            .text-right{
                text-align:right;
            }
            .text-center{
                text-align:center;
            }
            .text-left{
                text-align:left;
            }
            table td, table th, table thead, table tbody{
                font-family:Montserrat !important;
            }
            .text-success{
                color:#28a744;
            }
            .vat{
                vertical-align:top !important;
            }
            td{
                font-size: 14px;
                font-family: Montserrat !important;
            }
            .text-muted{
                color: #6c757d!important;
            }
            .text-sm{
                font-size: .675rem!important;
            }
            .text-md{
                font-size: .87rem !important;
            }
            .table-borderless{
                border-collapse: collapse;
            }

            .title{
                font-size: 1.25rem;
            }
            .mt-0{
                margin-top: 0px;
            }
            .mb-0{
                margin-bottom: 0px;
            }
            .m-0{
                margin:0;
            }
            .p-0{
                padding:0;
            }
            .foto-kandidat{
                width: 250px;
                border-radius: 10px;
            }
            @page{
                margin: 0;
            }
            .img-20-20{
                width: 20px;
                height: 20px;
            }
            .img-15-15{
                width: 15px;
                height: 15px;
            }
            .divider{
                height: .5px;
                background-color: #6c757d;
            }
            .container{
                margin: 25px;
            }
            .w-100{
                width: 100%;
            }
            #leftSide{
                width: 33.3%;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <table class="table-borderless m-0 p-0 w-100" border='1'>
                <tr>
                    <td id='leftSide' class='vat' style='padding-right:10px;'>
                        <img src="<?=$fotoKandidat?>" alt="<?=$namaKandidat?>"
                            class='foto-kandidat' onError='this.src="<?=base_url(assetsFolder('img/empty.png'))?>"' />
                        <br />
                        <p class="title mb-0" style='margin-bottom: 5px;'><?=$namaKandidat?></p>
                        <p class='text-muted' style='font-size: .765rem;'><?=$namaKotaKandidat?></p>
                        <div style='margin-top:10px; margin-bottom:10px;'></div>
                        <p class="text-md">Alamat</p>
                        <p class="text-sm text-muted"><?=$alamatKandidat?></p>
                        <p class="text-md">Email</p>
                        <p class="text-sm text-muted"><?=$emailKandidat?></p>
                        <p class="text-md">Nomor Telepon</p>
                        <p class="text-sm text-muted"><?=$nomorTeleponKandidat?></p>
                        

                        <div class='divider' style='margin-top: 20px; margin-bottom: 20px;'></div>
                        <p class='title mt-0'>Pendidikan</p>
                        <?php if(count($listEducation) >= 1){ ?>
                            <table class="table-borderless w-100 m-0">
                                <?php foreach($listEducation as $index => $edukasi){ ?>
                                    <?php
                                        $namaLembagaPendidikan  =   $edukasi['school_name'];
                                        $namaJurusan            =   $edukasi['faculty'];
                                        $tahunTamat             =   $edukasi['end_date'];

                                        $circleImage    =   circleIcon($index);
                                    ?>
                                    <tr>
                                        <td style='padding-right:10px;' class='vat'>
                                            <img src="<?=base_url(assetsFolder('img'))?>/<?=$circleImage?>" alt="<?=$namaLembagaPendidikan?>"
                                                class='img-15-15' />
                                        </td>
                                        <td style='padding-left:10px;'>
                                            <p class='text-md mt-0 mb-0'><?=formattedDate($tahunTamat)?></p>
                                            <p class='text-md'><?=$namaLembagaPendidikan?></p>
                                            <?php if(!empty($namaJurusan)){ ?>
                                                <p class="text-sm text-muted"><?=$namaJurusan?></p>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        <?php }else{ ?>
                            <p class="text-sm text-muted">Belum ada pengalaman</p>
                        <?php } ?>
                    </td>
                    <td id='rightSide' class='vat' style='padding-left:10px;'>
                        <p class='title mt-0'>Pengalaman</p>
                        <?php if(count($listExperience) >= 1){ ?>
                            <table class="table-borderless w-100 m-0">
                                <?php foreach($listExperience as $index => $pengalaman){ ?>
                                    <?php
                                        $pengalamanNamaPerusahaan   =   $pengalaman['experience_company'];
                                        $pengalamanJobName          =   $pengalaman['experience_name'];
                                        $pengalamanJobDescription   =   $pengalaman['experience_description'];
                                        $pengalamanMulai            =   $pengalaman['experience_start_date'];
                                        $pengalamanSampai           =   $pengalaman['experience_end_date'];

                                        $circleImage    =   circleIcon($index);
                                    ?>
                                    <tr>
                                        <td style='padding-right:10px;' class='vat'>
                                            <img src="<?=base_url(assetsFolder('img'))?>/<?=$circleImage?>" alt="<?=$pengalamanJobName?>"
                                                class='img-20-20' />
                                        </td>
                                        <td style='padding-left:10px;'>
                                            <p class='text-md mt-0 mb-0'><?=formattedDate($pengalamanMulai)?> - <?=formattedDate($pengalamanSampai)?></p>
                                            <p class='text-md'><?=$pengalamanJobName?><?=(!empty($pengalamanNamaPerusahaan))? ' di '.$pengalamanNamaPerusahaan : ''?></p>
                                            <p class="text-sm text-muted"><?=$pengalamanJobDescription?></p>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        <?php }else{ ?>
                            <p class="text-sm text-muted">Belum ada pengalaman</p>
                        <?php } ?>
                        <div class='divider' style='margin-top: 10px; margin-bottom: 10px;'></div>
                        <p class='title'>Skill</p>
                        <?php if(count($listSkill) >= 1){ ?>
                            <table class="table-borderless w-100">
                                <?php foreach($listSkill as $index => $skill){ ?>
                                    <?php
                                        $skillName          =   $skill['skill_name'];
                                        $skillDescription   =   $skill['skill_description'];

                                        $circleImage    =   circleIcon($index);
                                    ?>
                                    <tr>
                                        <td style='padding-right:10px;' class='vat'>
                                            <img src="<?=base_url(assetsFolder('img'))?>/<?=$circleImage?>" alt="<?=$pengalamanJobName?>"
                                                class='img-20-20' />
                                        </td>
                                        <td style='padding-left:10px;'>
                                            <p class='text-md mt-0'><?=$skillName?></p>
                                            <p class="text-sm text-muted"><?=$skillDescription?></p>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        <?php }else{ ?>
                            <p class="text-sm text-muted">Belum ada pengalaman</p>
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>