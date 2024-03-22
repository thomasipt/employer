<?php
    helper('CustomDate');

    $namaKandidat           =   $detailKandidat['nama'];
    
    $fotoKandidat           =   $detailKandidat['foto'];
    $fotoKandidat           =   base64_encode(file_get_contents($fotoKandidat));

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
        <link rel="stylesheet" href="<?=base_url(flexStartAssets('vendor/bootstrap/css/bootstrap.min.css'))?>" media='' />
        <style type='text/css' media="all">
            @font-face {
                font-family: Montserrat;
                font-style: normal;
                font-weight: 400;
                src: url(<?=base_url(assetsFolder('fonts/Montserrat/static/Montserrat-Regular.ttf'))?>);
            }
            @font-face {
                font-family: Montserrat;
                font-style: normal;
                font-weight: 700;
                src: url(<?=base_url(assetsFolder('fonts/Montserrat/static/Montserrat-Bold.ttf'))?>);
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
            .title-sm{
                font-size: 1rem;
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
            .mb-1{
                margin-bottom: .25rem!important;
            }
            .p-0{
                padding:0;
            }
            .foto-kandidat{
                width: 100%;

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
                color: #fff !important;
            }
            #rightSide{
                background-color: #fff;
            }
            .biodata, .pendidikan{
                margin: 10px 15px;
            }
            .biodata-icon{
                margin-right: 15px; 
                color: #fff;
                background-color:#fff;
                padding: 10px;
                width: 20px;
                height: 20px;
                border-radius: 20px;
            }
            .mt-15px{
                margin-top: 15px;
            }
            .bullet{
                width: 15px;
                height: 15px;
                border-radius: 15px;
            }
            .bullet-chain{
                width: 3.5px;
                height: 100%;
                position: absolute;
                margin-top: 5px;
                margin-left: 5px;
            }
            .bg-white{
                background-color: #fff;
            }
            .bg-blue{
                background-color: #0bb5f4;
            }
            .bullet-container{
                position: relative !important;
            }
        </style>
    </head>
    <body style='height:100vh; background-color:#0bb5f4;'>
        <table class="w-100" style='height:100vh;'>
            <tr>
                <td id='leftSide' class='vat p-0'>
                    <img src="data:image/png;base64,<?=$fotoKandidat?>" alt="<?=$namaKandidat?>"
                        class='foto-kandidat' onError='this.src="<?=base_url(assetsFolder('img/empty.png'))?>"' />
                    <div class="biodata">
                        <p class="title mb-0" style='font-weight: 700;'><?=$namaKandidat?></p>
                        <p style='font-size: .765rem; margin-top: 7.5px;'><?=$namaKotaKandidat?></p>
                        <div style='margin-top:0px; margin-bottom:0px;'></div>
                        <table>
                            <tr>
                                <td class='vat'>
                                    <img src="<?=base_url(assetsFolder('img/pdf-alamat.png'))?>" alt="Alamat" class='biodata-icon' />
                                </td>
                                <td class='text-left'>
                                    <p class='biodata-text title-sm' style='margin-bottom:10px;'>Alamat</p>
                                    <p class="text-sm biodata-text"><?=$alamatKandidat?></p>
                                </td>
                            </tr>
                        </table>
                        <table>
                            <tr>
                                <td class='vat'>
                                    <img src="<?=base_url(assetsFolder('img/pdf-email.png'))?>" alt="Email" class='biodata-icon' />
                                </td>
                                <td class='text-left'>
                                    <p class='biodata-text title-sm' style='margin-bottom:10px;'>Email</p>
                                    <p class="text-sm biodata-text"><?=$emailKandidat?></p>
                                </td>
                            </tr>
                        </table>
                        <table>
                            <tr>
                                <td class='vat'>
                                    <img src="<?=base_url(assetsFolder('img/pdf-telepon.png'))?>" alt="Telepon" class='biodata-icon' />
                                </td>
                                <td class='text-left'>
                                    <p class='biodata-text title-sm' style='margin-bottom:10px;'>Telepon</p>
                                    <p class="text-sm biodata-text"><?=$nomorTeleponKandidat?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class='divider' style='margin-top: 20px; margin-bottom: 20px; background-color:#fff;'></div>
                    <div class="pendidikan">
                    <p class='title mt-0'><b>Pendidikan</b></p>
                        <?php
                            $listEducationLength    =   count($listEducation);
                        ?>
                        <?php if($listEducationLength >= 1){ ?>
                            <table class="table-borderless w-100 m-0">
                                <?php foreach($listEducation as $index => $edukasi){ ?>
                                    <?php
                                        $namaLembagaPendidikan  =   $edukasi['school_name'];
                                        $namaJurusan            =   $edukasi['faculty'];
                                        $tahunTamat             =   $edukasi['end_date'];

                                        $circleImage    =   circleIcon($index);
                                    ?>
                                    <tr>
                                        <td class='vat bullet-container'>
                                            <div class="bullet bg-white">
                                                <?php if($index != $listEducationLength - 1){ ?>
                                                    <div style='display: flex; justify-content: center;'>
                                                        <div class="bullet-chain bg-white"></div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <td style='padding-left:10px;'>
                                            <p class='text-md mt-0 mb-0'>Tamat <?=formattedDate($tahunTamat)?></p>
                                            <p class='text-md'><?=$namaLembagaPendidikan?></p>
                                            <?php if(!empty($namaJurusan)){ ?>
                                                <p class="text-sm"><?=$namaJurusan?></p>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        <?php }else{ ?>
                            <p class="mb-1">Belum ada riwayat pendidikan</p>
                        <?php } ?>
                    </div>
                </td>
                <td id='rightSide' class='vat' style='padding-left:10px;'>
                    <div style='margin: 10px 15px;'>
                        <p class='title mt-0'>Pengalaman</p>
                        <?php
                            $listExperienceLength   =   count($listExperience);
                        ?>
                        <?php if($listEducationLength >= 1){ ?>
                            <table class="table-borderless w-100 m-0">
                                <?php foreach($listExperience as $indexExperience => $pengalaman){ ?>
                                    <?php
                                        $pengalamanNamaPerusahaan   =   $pengalaman['experience_company'];
                                        $pengalamanJobName          =   $pengalaman['experience_name'];
                                        $pengalamanJobDescription   =   $pengalaman['experience_description'];
                                        $pengalamanMulai            =   $pengalaman['experience_start_date'];
                                        $pengalamanSampai           =   $pengalaman['experience_end_date'];
                                    ?>
                                    <tr>
                                        <td class='vat bullet-container'>
                                            <div class="bullet bg-blue">
                                                <?php if($indexExperience != $listExperienceLength - 1){ ?>
                                                    <div style='display: flex; justify-content: center;'>
                                                        <div class="bullet-chain bg-blue"></div>
                                                    </div>
                                                <?php } ?>
                                            </div>
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
                        <br />
                        <p class='title'>Skill</p>
                        <?php 
                            $listSkillLength    =   count($listSkill);
                        ?>
                        <?php if($listSkillLength >= 1){ ?>
                            <table class="table-borderless w-100">
                                <?php foreach($listSkill as $indexSkill => $skill){ ?>
                                    <?php
                                        $skillName          =   $skill['skill_name'];
                                        $skillDescription   =   $skill['skill_description'];
                                    ?>
                                    <tr>
                                        <td class='vat bullet-container'>
                                            <div class="bullet bg-blue">
                                                <?php if($indexSkill != $listSkillLength - 1){ ?>
                                                    <div style='display: flex; justify-content: center;'>
                                                        <div class="bullet-chain bg-blue"></div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <td>
                                            <p class='text-md mt-0'><?=$skillName?></p>
                                            <p class="text-sm text-muted"><?=$skillDescription?></p>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        <?php }else{ ?>
                            <p class="text-sm text-muted">Belum ada riwayat skill</p>
                        <?php } ?>
                    </div>
                </td>
            </tr>
        </table>
    </body>
</html>