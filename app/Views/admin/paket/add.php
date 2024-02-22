<?php
    $detailPaket        =   $data['detailPaket'];

    $dP                 =   $detailPaket;
    $doesUpdate         =   !empty($dP);

    $actionURL  =   site_url(adminController('paket/save'));

    if($doesUpdate){
        $actionURL  =   site_url(adminController('paket/save/'.$dP['id']));
    }
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class='mb-0'>Paket</h5>
                        <span class="text-sm text-muted"><?=(!empty($pageDesc))? $pageDesc : 'Paket Baru' ?></span>
                    </div>
                    <a href="<?=site_url(adminController('paket'))?>">
                        <button class="btn btn-primary">List Paket</button>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form id="formPaket" action='<?=$actionURL?>' method='post'
                            data-id-paket='<?=($doesUpdate)? $dP['id'] : '' ?>'>
                            <div class="col col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type='text' class='form-control' placeholder='Nama Paket'
                                        name='nama' id='nama' value='<?=(!empty($dP))? $dP['nama'] : '' ?>' required />
                                </div>
                                <div class="form-group">
                                    <label for="harga">Harga</label>
                                    <input type='number' class='form-control' placeholder='Harga Paket'
                                        name='harga' id='harga' value='<?=(!empty($dP))? $dP['harga'] : '' ?>' required />
                                </div>
                                <div class="form-group">
                                    <label for="durasi">Durasi (Hari)</label>
                                    <input type='number' class='form-control' placeholder='Durasi Paket'
                                        name='durasi' id='durasi' value='<?=(!empty($dP))? $dP['durasi'] : '' ?>' required />
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan (Opsional)</label>
                                    <textarea class='form-control' placeholder='Keterangan Paket'
                                        name='keterangan' id='keterangan' required><?=(!empty($dP))? $dP['keterangan'] : '' ?></textarea>
                                </div>
                                <hr class='mb-4' />
                                <button class="btn btn-success" type='submit' id='btnSubmit'>Simpan <?=($doesUpdate)? 'Perubahan' : ''?></button>
                                <a href="<?=site_url(adminController('paket'))?>">
                                    <button class="btn btn-default" type='button'>Back to List Paket</button>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src='<?= base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.js')) ?>'></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.css'))?>" />

<script src='<?=base_url(assetsFolder('custom/js/custom-alert.js'))?>'></script>
<script src='<?=base_url(assetsFolder('custom/js/form-submission.js'))?>'></script>

<script language='Javascript'>
    let _formPaket  =   $('#formPaket');

    _formPaket.on('submit', async function(e){
        e.preventDefault();

        await submitForm(this, async (decodedRFS) => {
            let _status     =   decodedRFS.status;
            let _message    =   decodedRFS.message;

            let _idPaket    =   _formPaket.data('idPaket');   
            let _isUpdate   =   (_idPaket != '');

            let _title  =   (_isUpdate)? 'Update Paket' : 'Paket Baru';
            let _type   =   (_status)? 'success' : 'error';

            await notifikasi(_title, _message, _type);
            if(_status){
                location.href   =   `<?=site_url(adminController('paket'))?>`;
            }
        })
    });
</script>