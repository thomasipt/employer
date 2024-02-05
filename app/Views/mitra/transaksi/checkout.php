<?php
    $paketModel     =   model('Paket');
    $transaksiModel =   model('Transaksi');

    $detailPaket        =   $data['detailPaket'];
    $transaksiAktif     =   $data['transaksiAktif'];

    $codePaket          =   $detailPaket['code'];
    $namaPaket          =   $detailPaket['nama'];
    $keteranganPaket    =   $detailPaket['keterangan'];
    $hargaPaket     =   $detailPaket['harga'];
    $ppnPaket       =   ($paketModel->persentasePPN / 100 * $hargaPaket);
    $durasiPaket    =   $detailPaket['durasi'];

    $total  =   $hargaPaket + $ppnPaket;
?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class='mb-0'>Detail Paket</h5>
                        <span class="text-sm text-muted"><?=$namaPaket?> (<?=$codePaket?>)</span>
                    </div>
                    <div class="col text-right">
                        
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if(!empty($transaksiAktif)){ ?>
                    <?php
                        $currentDate    =   currentDate();

                        $namaPaketAktif             =   $transaksiAktif['namaPaket'];
                        $durasiPaketAktif           =   $transaksiAktif['durasiPaket'];  
                        $berlakuMulaiPaketAktif     =   $transaksiAktif['berlakuMulai'];
                        $berlakuSampaiPaketAktif    =   $transaksiAktif['berlakuSampai'];

                        $selisih            =   date_diff(date_create($berlakuSampaiPaketAktif), date_create($currentDate));
                        $sisaHariMasaAktif  =   $selisih->days;
                    ?>
                    <div class="alert alert-info text-sm">
                        Anda memiliki paket aktif <b><?=$namaPaketAktif?> <?=number_format($durasiPaketAktif)?> Hari</b> dengan sisa masa aktif 
                        <b><?=$sisaHariMasaAktif?> hari lagi</b> (periode <?=formattedDate($berlakuMulaiPaketAktif)?> sd <?=formattedDate($berlakuSampaiPaketAktif)?>). 
                        <br />
                        Jika anda meneruskan paket ini untuk tetap membeli paket ini, paket lama anda akan hangus dan akan digantikan dengan paket yang baru.
                    </div>
                <?php } ?>
                <div class="form-group">
                    <label for="nama" class='mb-1'>Nama Paket</label>
                    <p class="text-sm" id='nama'><?=$namaPaket?></p>
                </div>
                <div class="form-group">
                    <label for="keterangan" class='mb-1'>Keterangan Paket</label>
                    <p class="text-sm" id='keterangan'><?=$keteranganPaket?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
    <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class='mb-0'>Sub Total</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <h6 class='mb-3'><b>Rincian Pembayaran</b></h6>
                    <table id='tabelSubTotal' class='table table-sm table-borderless'>
                        <tbody>
                            <tr>
                                <td><h6>Harga</h6></td>
                                <td class='text-right text-success'>Rp. <?=number_format($hargaPaket)?></td>
                            </tr>
                            <tr>
                                <td><h6>PPN</h6></td>
                                <td class='text-right text-info'>Rp. <?=number_format($ppnPaket)?></td>
                            </tr>
                            <tr>
                                <td><h6>Durasi</h6></td>
                                <td class='text-right'><?=$durasiPaket?> Hari</td>
                            </tr>
                        </tbody>
                    </table>
                    <hr />
                    <table id='tabelSubTotal' class='table table-sm table-borderless'>
                        <tbody>
                            <tr>
                                <td class='text-right'>
                                    <h5 class='text-success'><b>Rp. <?=number_format($total)?></b></h5>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="text-sm text-muted mb-4">
                        Selanjutnya, anda harus melakukan pembayaran sebesar <b>Rp. <?=number_format($total)?></b> ke rekening <?=$transaksiModel->pembayaran_bankRekening?> 
                        an <?=$transaksiModel->pembayaran_namaRekening?> nomor rekening <b><?=$transaksiModel->pembayaran_nomorRekening?></b>
                    </p>
                    <button class='btn btn-success btn-block' id='btnCheckout'>
                        Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src='<?= base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.js')) ?>'></script>
<script src="<?=base_url(assetsFolder('plugins/numeral/numeral.js'))?>"></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.css'))?>" />

<script src='<?=base_url(assetsFolder('custom/js/custom-alert.js'))?>'></script>
<script src='<?=base_url(assetsFolder('custom/js/date-converter.js'))?>'></script>

<script src='<?= base_url(assetsFolder('plugins/datatables/jquery.dataTables.min.js')) ?>'></script>
<script src='<?= base_url(assetsFolder('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')) ?>'></script>
<link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')) ?>" />
<script language='Javascript'>
    let _btnCheckout            =   $('#btnCheckout');
    let _btnCheckoutText        =   _btnCheckout.text();
    let _punyaTransaksiAktif    =   `<?=(!empty($transaksiAktif))? 'true' : 'false' ?>`;
    _punyaTransaksiAktif        =   _punyaTransaksiAktif == 'true';
    let _codePaket              =   `<?=$codePaket?>`;

    _btnCheckout.on('click', async function(){
        _btnCheckout.prop('disabled', true).text('Processing ..');

        if(_punyaTransaksiAktif){
            let _konfirmasi =   await konfirmasi('Checkout', 'Apakah anda yakin akan menganti paket aktif anda dengan paket yang baru?');
            _btnCheckout.prop('disabled', false).text(_btnCheckoutText);
            
            if(!_konfirmasi){
                
                return false;
            }
        }

        $.ajax({
            url     :   `<?=site_url(mitraController('transaksi/checkout'))?>/${_codePaket}`,
            type    :   'POST',
            success :   async (responseFromServer) => {
                let _status     =   responseFromServer.status;
                let _message    =   responseFromServer.message;

                let _swalMessage    =   (_message == null)? (_status)? 'Berhasil!' : 'Gagal, silahkan ulangi lagi!' : _message;
                let _swalIcon       =   (_status)? 'success' : 'error';

                await notifikasi('Checkout', _swalMessage, _swalIcon);
                if(_status){
                    location.href   =   `<?=site_url(mitraController('transaksi'))?>`;
                }
            }
        });

        _btnCheckout.prop('disabled', false).text(_btnCheckoutText);
    });
</script>