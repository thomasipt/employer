<?php
    $emailPerusahaanElement    =   $data['emailPerusahaanElement'];
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class='mb-0'>Email Perusahaan</h5>
            </div>
            <div class="card-body">
                <form id="formEmail" action='<?=base_url(adminController('website/landing-page/email-perusahaan'))?>' method='post'
                    enctype='multipart/form-data'>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="_email">Email</label>
                                    <input type='email' class='form-control' placeholder='Email'
                                        name='_email' id='_email' value='<?=(!empty($emailPerusahaanElement))? $emailPerusahaanElement['_email'] : '' ?>'
                                            required />
                                </div>
                                <div class="form-group">
                                    <label for="_password">Password</label>
                                    <div class="input-group" id='passwordEmailInputGroup'>
                                        <input type='password' class='form-control password' placeholder='Password'
                                            name='_password' id='_password' value='<?=(!empty($emailPerusahaanElement))? $emailPerusahaanElement['_password'] : '' ?>'
                                                required />
                                        <div class="input-group-append cp">
                                            <span class="input-group-text">
                                                <span class="fa fa-eye password-icon" onClick='togglePassword(this, "#passwordEmailInputGroup")'></span>
                                            </span>
                                        </div>
                                    </div>
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
    let _formEmail       =   $('#formEmail');

    _formEmail.on('submit', async function(e){
        e.preventDefault();

        await submitForm(this, async (decodedRFS) => {
            let _status     =   decodedRFS.status;
            let _message    =   decodedRFS.message;

            let _title  =   'Email Perusahaan';
            let _type   =   (_status)? 'success' : 'error';

            await notifikasi(_title, _message, _type);
            if(_status){
                location.href   =   `<?=site_url(adminController('website/landing-page/email-perusahaan'))?>`;
            }
        })
    });
</script>