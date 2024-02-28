<?php
    $heroElement    =   $data['heroElement'];

    $imagePath      =   $heroElement['_image'];
    $isImageEmpty   =   empty($imagePath);
?>
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5>Home Image</h5>
            </div>
            <div class="card-body">
                <form id='formHeroImage' action="<?=base_url(adminController('website/landing-page-image/hero'))?>"
                    method='post' enctype='multipart/form-data'>
                    <label for='uploadButton' class='w-100'>
                        <input type="file" name="_image" id='uploadButton' style='display:none;'
                            onChange='_uploadFile(this)'
                            data-preview='#uploadIcon' />
                        <img src='<?=($isImageEmpty)? base_url(assetsFolder('img/upload-icon.png')) : base_url(uploadGambarWebsite('landing-page'))?>/<?=$imagePath?>' alt='Upload Icon' class='w-100 d-block m-auto'
                            id='uploadIcon' />
                    </label>
                    <p class="text-sm text-muted text-center">Klik gambar untuk memilih gambar</p>
                    <hr class='mb-4' />
                    <button class="btn btn-success" type='submit' id='btnSubmit'>Upload</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class='mb-0'>Home Division</h5>
            </div>
            <div class="card-body">
                <form id="formHero" action='<?=base_url(adminController('website/landing-page/hero'))?>' method='post'
                    enctype='multipart/form-data'>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="_title">Judul</label>
                                    <input type='text' class='form-control' placeholder='Judul Besar Hero'
                                        name='_title' id='_title' value='<?=(!empty($heroElement))? $heroElement['_title'] : '' ?>' required />
                                </div>
                                <div class="form-group">
                                    <label for="_description">Deskripsi</label>
                                    <input type='text' class='form-control' placeholder='Deskripsi Hero'
                                        name='_description' id='_description' value='<?=(!empty($heroElement))? $heroElement['_description'] : '' ?>' required />
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

<script src='<?=base_url(assetsFolder('plugins/select2/js/select2.min.js'))?>'></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/select2/css/select2.min.css'))?>" />
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css'))?>" />

<script language='Javascript'>
    let _formHero       =   $('#formHero');
    let _formHeroImage  =   $('#formHeroImage');

    _formHero.on('submit', async function(e){
        e.preventDefault();

        await submitForm(this, async (decodedRFS) => {
            let _status     =   decodedRFS.status;
            let _message    =   decodedRFS.message;

            let _title  =   'Hero';
            let _type   =   (_status)? 'success' : 'error';

            await notifikasi(_title, _message, _type);
            if(_status){
                location.href   =   `<?=site_url(adminController('website/landing-page/hero'))?>`;
            }
        })
    });

    _formHeroImage.on('submit', async function(e){
        e.preventDefault();

        await submitForm(this, async (decodedRFS) => {
            let _status     =   decodedRFS.status;
            let _message    =   decodedRFS.message;

            let _title  =   'Hero';
            let _type   =   (_status)? 'success' : 'error';

            await notifikasi(_title, _message, _type);
            if(_status){
                location.href   =   `<?=site_url(adminController('website/landing-page/hero'))?>`;
            }
        });
    });
    
    async function _uploadFile(thisContext){
        await fileHandler(thisContext);
    }
</script>