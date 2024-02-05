<?php
    $transaksi  =   $model['transaksi'];

    $detailTransaksi    =   $data['detailTransaksi'];
    $detailMitra        =   $data['detailMitra'];
    $detailPaket        =   $data['detailPaket'];

    $nomor              =   $detailTransaksi['nomor'];
    $tanggalPembelian   =   $detailTransaksi['createdAt'];
    $harga              =   $detailTransaksi['harga'];
    $ppn                =   $detailTransaksi['ppn'];
    $berlakuMulai       =   $detailTransaksi['berlakuMulai'];
    $berlakuSampai      =   $detailTransaksi['berlakuSampai'];
    $approvement        =   $detailTransaksi['approvement'];
    $approvementReason  =   $detailTransaksi['approvementReason'];
    $approvementAt      =   $detailTransaksi['approvementAt'];

    $namaMitra      =   $detailMitra['nama'];
    $alamatMitra    =   $detailMitra['alamat'];

    $namaPaket          =   $detailPaket['nama'];
    $hargaPaket         =   $detailPaket['harga'];
    $durasiPaket        =   $detailPaket['durasi'];
    $keteranganPaket    =   $detailPaket['keterangan'];

    $grandTotal         =   $harga + $ppn;
    $statusPembelian    =   'Belum diproses Admin';
    $isApprovementApproved  =   $approvement == $transaksi->approvement_approved;
    $isApprovementRejected  =   $approvement == $transaksi->approvement_rejected;

    $approvementAtColumn    =   'Approvement Pada';
    if($isApprovementApproved){
        $statusPembelian        =   'Approved';
        $approvementAtColumn    =   'Diapprove Pada';
    }
    if($isApprovementRejected){
        $statusPembelian        =   'Rejected';
        $approvementAtColumn    =   'Ditolak Pada';
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Invoice <?=$nomor?></title>
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
            .row-footer{
                width:100%;
                margin-top: 30px;
            }
            .row::after{
                clear: both;
            }
            .header { 
                text-align: right; 
                width: 100%;
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
                font-size: .875rem!important;
            }
        </style>
    </head>
    <body>
        <table border='0' style='width:100%;'>
            <tr>
                <td>
                    <?=$nomor?>
                    <p class='text-sm text-muted' style='margin-top: 5px;'>Nomor Transaksi</p>
                </td>
                <td rowspan='2' class='text-right'>
                    <img src="<?=base_url(assetsFolder('img/icon.png'))?>" alt="<?=$nomor?>"
                        style='width:100px; height:100px;'>
                </td>
            </tr>
        </table>

        <table width="100%" class='minimalize-td'>
            <tr>
                <td class='vat' style='padding-left:0; width:200px'>Nama Mitra</td>
                <td class='vat' style='width:10px'>:</td>
                <td class='vat'><?=$namaMitra?></td>
            </tr>
            <tr>
                <td class='vat' style='padding-left:0; width:200px'>Alamat Mitra</td>
                <td class='vat' style='width:10px'>:</td>
                <td class='vat'><?=$alamatMitra?></td>
            </tr>
            <tr>
                <td class='vat' style='padding-left:0; width:200px'>Masa Berlaku</td>
                <td class='vat' style='width:10px'>:</td>
                <td class='vat'><?=formattedDate($berlakuMulai)?> s/d <?=formattedDate($berlakuSampai)?></td>
            </tr>
            <tr>
                <td class='vat' style='padding-left:0; width:200px'>Tanggal Pembelian</td>
                <td class='vat' style='width:10px'>:</td>
                <td class='vat'><?=formattedDateTime($tanggalPembelian)?></td>
            </tr>
            <tr>
                <td class='vat' style='padding-left:0; width:200px'>Status Pembelian</td>
                <td class='vat' style='width:10px'>:</td>
                <td class='vat'>
                    <?=$statusPembelian?>
                    <?php if(!empty($approvementReason)){ ?>
                        <p class='text-muted text-sm' style='margin-top: 5px;'><?=$approvementReason?></p>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td class='vat' style='padding-left:0; width:200px'><?=$approvementAtColumn?></td>
                <td class='vat' style='width:10px'>:</td>
                <td class='vat'><?=formattedDateTime($approvementAt)?></td>
            </tr>
        </table>
        <br />
        <table width="100%" border='1' class='minimalize-td' style='border-collapse:collapse; border-color: #115abe;'>
            <thead>
                <tr>
                    <td style='background-color: #115abe; color: #fff; padding: 10px 10px;'>Nama Paket</td>
                    <td style='background-color: #115abe; color: #fff; padding: 10px 10px;'>Durasi (hari)</td>
                    <td style='background-color: #115abe; color: #fff; padding: 10px 10px;' class='text-right' width='150px;'>Harga</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style='padding: 10px 10px;'>
                        <?=$namaPaket?>
                        <?php if(!empty($keteranganPaket)){ ?>
                            <p class='text-sm text-muted' style='margin-top: 5px;'><?=$keteranganPaket?></p>
                        <?php } ?>
                    </td>
                    <td style='padding: 10px 10px;'><?=number_format($durasiPaket)?> Hari</td>
                    <td style='padding: 10px 10px; color: #115abe;' class='text-right'>
                        Rp. <?=number_format($hargaPaket)?>
                    </td>
                </tr>
            </tbody>
        </table>
        </br />
        <div class="row" style='padding-top:0; margin-top:0;'>
            <div class="col-6">
            </div>
            <div class="col-6">
                <table style='width:100%;' class='minimalize-td'>
                    <tr> 
                        <td class='vat' class='vat text-left'>Total</td>     
                        <td class='vat' class='text-right'>Rp. <?=number_format($harga)?></td>                          
                    </tr>
                    <tr> 
                        <td class='vat' class='vat text-left'>PPN</td>     
                        <td class='vat' class='text-right'>Rp. <?=number_format($ppn)?></td>                          
                    </tr>
                    <tr> 
                        <td class='vat' class='vat text-left'>Grand Total</td>     
                        <td class='vat' class='text-right'
                            style='color: #115abe;'>Rp. <?=number_format($grandTotal)?></td>                          
                    </tr>
                </table>
            </div>   
        </div>
    </body>
</html>