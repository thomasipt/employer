<?php
    helper('CustomDate');

    $namaKandidat   =   $detailKandidat['nama'];
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
        </style>
    </head>
    <body>
        <table class="table-borderless">
            <tr>
                <td id='leftSide'>

                </td>
                <td id='rightSide'>
                    <p class='title'>Pengalaman</p>
                    <?php if(count($listExperience) >= 1){ ?>
                        <table class="table-borderless">
                            <?php foreach($listExperience as $index => $pengalaman){ ?>
                                <?php
                                    $pengalamanNamaPerusahaan   =   $pengalaman['experience_company'];
                                    $pengalamanJobName          =   $pengalaman['experience_name'];
                                    $pengalamanJobDescription   =   $pengalaman['experience_description'];
                                    $pengalamanMulai            =   $pengalaman['experience_start_date'];
                                    $pengalamanSampai           =   $pengalaman['experience_end_date'];

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
                                ?>
                                <tr>
                                    <td style='padding-right:10px;' class='vat'>
                                        <img src="<?=base_url(assetsFolder('img'))?>/<?=$circleImage?>" alt="<?=$pengalamanJobName?>"
                                            style='width: 25px; height: 25px; padding: 0; margin: 0;' />
                                    </td>
                                    <td style='padding-left:10px;'>
                                        <p class='text-md mt-0'><?=formattedDate($pengalamanMulai)?> - <?=formattedDate($pengalamanSampai)?></p>
                                        <p class='text-md'><?=$pengalamanJobName?><?=(!empty($pengalamanNamaPerusahaan))? 'di '.$pengalamanNamaPerusahaan : ''?></p>
                                        <p class="text-sm text-muted"><?=$pengalamanJobDescription?></p>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php }else{ ?>
                        <p class="text-sm text-muted">Belum ada pengalaman</p>
                    <?php } ?>
                    <hr style='border-collapse: collapse;' />
                    <p class='title'>Skill</p>
                    <?php if(count($listSkill) >= 1){ ?>
                        <table class="table-borderless">
                            <?php foreach($listSkill as $index => $skill){ ?>
                                <?php
                                    $skillName          =   $skill['skill_name'];
                                    $skillDescription   =   $skill['skill_description'];

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
                                ?>
                                <tr>
                                    <td style='padding-right:10px;' class='vat'>
                                        <img src="<?=base_url(assetsFolder('img'))?>/<?=$circleImage?>" alt="<?=$pengalamanJobName?>"
                                            style='width: 25px; height: 25px; padding: 0; margin: 0;' />
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
    </body>
</html>