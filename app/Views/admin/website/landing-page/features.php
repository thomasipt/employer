<?php
    $featuresElement    =   $data['featuresElement'];
    $listIcons          =   $data['listIcons'];

    $imagePath          =   $featuresElement['_image'];
    $isImageEmpty       =   empty($imagePath);
?>
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5>Feature Image</h5>
            </div>
            <div class="card-body">
                <form id='formFeaturesImage' action="<?=base_url(adminController('website/landing-page-image/features'))?>"
                    method='post' enctype='multipart/form-data'>
                    <label for='uploadButton' class='w-100'>
                        <input type="file" name="_image" id='uploadButton' style='display:none;'
                            onChange='_uploadFile(this)'
                            data-preview='#uploadIcon' />
                        <img src='<?=($isImageEmpty)? base_url(assetsFolder('img/upload-icon.png')) : base_url(uploadGambarWebsite('landing-page'))?>/<?=$imagePath?>'
                            alt='Upload Icon' class='w-100 d-block m-auto'
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
                <h5 class='mb-0'>Feature Division</h5>
            </div>
            <div class="card-body">
                <form id="formFeatures" action='<?=base_url(adminController('website/landing-page/features'))?>' method='post'
                    enctype='multipart/form-data'>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="_title">Judul</label>
                                    <input type='text' class='form-control' placeholder='Judul Besar Feature'
                                        name='_title' id='_title' value='<?=(!empty($featuresElement))? $featuresElement['_title'] : '' ?>' required />
                                </div>
                                <div class="form-group">
                                    <label for="features">Features <span class="fa fa-plus-circle cp text-success ml-1" onClick='_addFeature(this)'></span></label>
                                    <div id="features">
                                        <p class="text-sm text-muted">Klik + untuk menambahkan konten Info</p>
                                        <?php
                                            $features       =   $featuresElement['_feature'];
                                            $listFeatures   =   json_decode($features, true);
                                            foreach($listFeatures as $feature){
                                                $icon           =   $feature['icon'];
                                                $title          =   $feature['title'];
                                                $description    =   $feature['description'];
                                                ?>
                                                    <div class="feature mb-4 pl-3">
                                                        <div class="row">
                                                            <span class="<?=$icon?> feature-icon"></span>
                                                            <div class="col ml-3">
                                                                <div class='mb-3 row'>
                                                                    <div class="col-4">
                                                                        <select name="icon[]" class='form-control pilihan-icon' required
                                                                            onChange='_iconChanged(this)'>
                                                                            <option value="">-- Pilih Icon --</option>
                                                                            <?php foreach($listIcons as $iconItem){ ?>
                                                                                <?php
                                                                                    $isSelected =   $iconItem == $icon;
                                                                                    $selected   =   ($isSelected)? 'selected' : '';
                                                                                ?>
                                                                                <option value="<?=$iconItem?>" <?=$selected?>><?=$iconItem?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-8">
                                                                        <input type="text" class="form-control" placeholder='Nama Feature' 
                                                                            name='title[]' required 
                                                                            value='<?=$title?>' />
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <textarea class="form-control" placeholder='Deskripsi Feature' 
                                                                            name='description[]' required><?=$description?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <span class="fa fa-trash text-danger cp ml-3"
                                                                onClick='_delete(this)'></span>
                                                        </div>
                                                    </div>
                                                <?php
                                            }
                                        ?>
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

<script src='<?=base_url(assetsFolder('plugins/select2/js/select2.min.js'))?>'></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/select2/css/select2.min.css'))?>" />
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css'))?>" />

<link href="<?= base_url(flexStartAssets('vendor/remixicon/remixicon.css')) ?>" rel="stylesheet">

<?php
    $listIconOptions    =   '';
    foreach($listIcons as $iconItem){
        $listIconOptions    .=  '<option value="'.$iconItem.'">'.$iconItem.'</option>';
    }
?>
<script language='Javascript'>
    let _formFeatures       =   $('#formFeatures');
    let _formFeaturesImage  =   $('#formFeaturesImage');
    let _features           =   $('#features');

    let _listIconsHTML      =   `<?=$listIconOptions?>`;

    let _featureHTML        =   `<div class="feature mb-4 pl-3">
                                    <div class="row">
                                        <span class="feature-icon"></span>
                                        <div class="col ml-3">
                                            <div class='mb-3 row'>
                                                <div class="col-4">
                                                    <select name="icon[]" class='form-control pilihan-icon' required
                                                        onChange='_iconChanged(this)'>
                                                        <option value="">-- Pilih Icon --</option>
                                                        ${_listIconsHTML}
                                                    </select>
                                                </div>
                                                <div class="col-8">
                                                    <input type="text" class="form-control" placeholder='Nama Feature' 
                                                        name='title[]' required />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <textarea class="form-control" placeholder='Deskripsi Feature' 
                                                        name='description[]' required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="fa fa-trash text-danger cp ml-3"
                                            onClick='_delete(this)'></span>
                                    </div>
                                </div>`;

    _formFeatures.on('submit', async function(e){
        e.preventDefault();

        await submitForm(this, async (decodedRFS) => {
            let _status     =   decodedRFS.status;
            let _message    =   decodedRFS.message;

            let _title  =   'Feature';
            let _type   =   (_status)? 'success' : 'error';

            await notifikasi(_title, _message, _type);
            if(_status){
                location.href   =   `<?=site_url(adminController('website/landing-page/features'))?>`;
            }
        })
    });

    _formFeaturesImage.on('submit', async function(e){
        e.preventDefault();

        await submitForm(this, async (decodedRFS) => {
            let _status     =   decodedRFS.status;
            let _message    =   decodedRFS.message;

            let _title  =   'Feature';
            let _type   =   (_status)? 'success' : 'error';

            await notifikasi(_title, _message, _type);
            if(_status){
                location.href   =   `<?=site_url(adminController('website/landing-page/features'))?>`;
            }
        });
    });
    
    async function _uploadFile(thisContext){
        await fileHandler(thisContext);
    }
    
    async function _addFeature(thisContext){
        let _el     =   $(thisContext);
        
        let _localFeatureHTML   =   _featureHTML;
        _features.append(_localFeatureHTML);
    }
    
    $('.pilihan-icon').select2({
        theme: 'bootstrap4'
    });

    async function _iconChanged(thisContext){
        let _el         =   $(thisContext);
        let _parent     =   _el.parents('.feature');
        let _icon       =   _parent.find('.feature-icon');

        let _selectedIcon   =   _el.val();

        let _iconClass  =   `${_selectedIcon} feature-icon`;
        _icon.attr('class', _iconClass);
    }
    async function _delete(thisContext){
        let _el         =   $(thisContext);
        let _parent     =   _el.parents('.feature');

        _parent.remove();
    }
</script>
<style type='text/css'>
    .feature-icon{
        font-size: 44px;
        line-height: 44px;
        color: #0245bc;
        margin-right: 15px;
    }
</style>