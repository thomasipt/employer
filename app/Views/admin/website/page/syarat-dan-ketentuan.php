<?php
    $syaratDanKetentuanElement    =   $data['syaratDanKetentuanElement'];
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class='mb-0'>Syarat dan Ketentuan</h5>
            </div>
            <div class="card-body">
                <form id="formSyaratDanKetentuan" action='<?=base_url(adminController('website/page/syarat-dan-ketentuan'))?>' method='post'
                    enctype='multipart/form-data'>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="_content">Content</label>
                                    <textarea class='form-control' placeholder='Content' required
                                        name='_content' id='_content'><?=(!empty($syaratDanKetentuanElement))? $syaratDanKetentuanElement['_content'] : '' ?></textarea>
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

<script src='<?=base_url(assetsFolder('plugins/ckeditor5-build-classic/ckeditor.js'))?>'></script>

<script language='Javascript'>
    let _formSyaratDanKetentuan       =   $('#formSyaratDanKetentuan');
    
    ClassicEditor
		.create(document.querySelector('#_content'), {})
		.then(editor => {
			window.editor = editor;
		});

    _formSyaratDanKetentuan.on('submit', async function(e){
        e.preventDefault();

        await submitForm(this, async (decodedRFS) => {
            let _status     =   decodedRFS.status;
            let _message    =   decodedRFS.message;

            let _title  =   'Syarat dan Ketentuan';
            let _type   =   (_status)? 'success' : 'error';

            await notifikasi(_title, _message, _type);
            if(_status){
                location.href   =   `<?=site_url(adminController('website/page/syarat-dan-ketentuan'))?>`;
            }
        })
    });
</script>