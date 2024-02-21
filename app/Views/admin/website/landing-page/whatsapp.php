<?php
    $whatsappElement    =   $data['whatsappElement'];
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class='mb-0'>Whatsapp Division</h5>
            </div>
            <div class="card-body">
                <form id="formWhatsapp" action='<?=base_url(adminController('website/landing-page/whatsapp'))?>' method='post'
                    enctype='multipart/form-data'>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="_number">Nomor Whatsapp</label>
                                    <input type='text' class='form-control' placeholder='Whatsapp Number'
                                        name='_number' id='_number' value='<?=(!empty($whatsappElement))? $whatsappElement['_number'] : '' ?>' required />
                                </div>
                                <div class="form-group">
                                    <label for="_template">Template</label>
                                    <input type='text' class='form-control' placeholder='Template Percakapan'
                                        name='_template' id='_template' value='<?=(!empty($whatsappElement))? $whatsappElement['_template'] : '' ?>' required />
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
    let _formWhatsapp       =   $('#formWhatsapp');

    _formWhatsapp.on('submit', async function(e){
        e.preventDefault();

        await submitForm(this, async (decodedRFS) => {
            let _status     =   decodedRFS.status;
            let _message    =   decodedRFS.message;

            let _title  =   'Whatsapp';
            let _type   =   (_status)? 'success' : 'error';

            await notifikasi(_title, _message, _type);
            if(_status){
                location.href   =   `<?=site_url(adminController('website/landing-page/whatsapp'))?>`;
            }
        })
    });
</script>