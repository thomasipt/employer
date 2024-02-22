<?php
    $paketElement    =   $data['paketElement'];
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class='mb-0'>Paket Division</h5>
            </div>
            <div class="card-body">
                <form id="formPaket" action='<?=base_url(adminController('website/landing-page/paket'))?>' method='post'
                    enctype='multipart/form-data'>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="_title">Judul</label>
                                    <input type='text' class='form-control' placeholder='Judul'
                                        name='_title' id='_title' value='<?=(!empty($paketElement))? $paketElement['_title'] : '' ?>' required />
                                </div>
                                <div class="form-group">
                                    <label for="_description">Deskripsi</label>
                                    <input type='text' class='form-control' placeholder='Deskripsi'
                                        name='_description' id='_description' value='<?=(!empty($paketElement))? $paketElement['_description'] : '' ?>' required />
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
    let _formPaket       =   $('#formPaket');

    _formPaket.on('submit', async function(e){
        e.preventDefault();

        await submitForm(this, async (decodedRFS) => {
            let _status     =   decodedRFS.status;
            let _message    =   decodedRFS.message;

            let _title  =   'Paket';
            let _type   =   (_status)? 'success' : 'error';

            await notifikasi(_title, _message, _type);
            if(_status){
                location.href   =   `<?=site_url(adminController('website/landing-page/paket'))?>`;
            }
        })
    });
</script>