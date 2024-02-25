<?php
    $rekeningPerusahaanElement    =   $data['rekeningPerusahaanElement'];
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class='mb-0'>Rekening Perusahaan</h5>
            </div>
            <div class="card-body">
                <form id="formRekeningPerusahaan" action='<?=base_url(adminController('website/landing-page/rekening-perusahaan'))?>' method='post'
                    enctype='multipart/form-data'>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="_pemilik">Pemilik</label>
                                    <input type='text' class='form-control' placeholder='Nama Pemilik Rekening'
                                        name='_pemilik' id='_pemilik' value='<?=(!empty($rekeningPerusahaanElement))? $rekeningPerusahaanElement['_pemilik'] : '' ?>' required />
                                </div>
                                <div class="form-group">
                                    <label for="_nomor">Nomor Rekening</label>
                                    <input type='text' class='form-control' placeholder='Nomor Rekening'
                                        name='_nomor' id='_nomor' value='<?=(!empty($rekeningPerusahaanElement))? $rekeningPerusahaanElement['_nomor'] : '' ?>' required />
                                </div>
                                <div class="form-group">
                                    <label for="_bank">Nama Bank</label>
                                    <input type='text' class='form-control' placeholder='Nama Bank Rekening'
                                        name='_bank' id='_bank' value='<?=(!empty($rekeningPerusahaanElement))? $rekeningPerusahaanElement['_bank'] : '' ?>' required />
                                </div>
                                <hr class='mb-4' />
                                <button class="btn btn-success" type='submit' id='btnSubmit'>Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src='<?= base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.js')) ?>'></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.css'))?>" />

<script src='<?=base_url(assetsFolder('custom/js/custom-alert.js'))?>'></script>
<script src='<?=base_url(assetsFolder('custom/js/form-submission.js'))?>'></script>

<script language='Javascript'>
    let _formRekeningPerusahaan       =   $('#formRekeningPerusahaan');

    _formRekeningPerusahaan.on('submit', async function(e){
        e.preventDefault();

        await submitForm(this, async (decodedRFS) => {
            let _status     =   decodedRFS.status;
            let _message    =   decodedRFS.message;

            let _title  =   'Rekening Perusahaan';
            let _type   =   (_status)? 'success' : 'error';

            await notifikasi(_title, _message, _type);
            if(_status){
                location.href   =   `<?=site_url(adminController('website/landing-page/rekening-perusahaan'))?>`;
            }
        })
    });
</script>