<?php
    $contactUsElement       =   $data['contactUsElement'];
    $listIcons              =   $data['listIcons'];
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class='mb-0'>Contact Us</h5>
            </div>
            <div class="card-body">
                <form id="formContactUs" action='<?=base_url(adminController('website/landing-page/contact-us'))?>' method='post'
                    enctype='multipart/form-data'>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="_title">Judul</label>
                                    <input type='text' class='form-control' placeholder='Judul Besar Contact Us'
                                        name='_title' id='_title' value='<?=(!empty($contactUsElement))? $contactUsElement['_title'] : '' ?>' required />
                                </div>
                                <div class="form-group">
                                    <label for="_description">Deskripsi</label>
                                    <input type='text' class='form-control' placeholder='Deskripsi Contact Us'
                                        name='_description' id='_description' value='<?=(!empty($contactUsElement))? $contactUsElement['_description'] : '' ?>' required />
                                </div>
                                <div class="form-group">
                                    <label for="contact">Contact <span class="fa fa-plus-circle cp text-success ml-1" onClick='_addContact(this)'></span></label>
                                    <div id="contact">
                                        <p class="text-sm text-muted">Klik + untuk menambahkan konten contact</p>
                                        <?php
                                            $encodedContact        =   $contactUsElement['_contact'];
                                            $listContact    =   json_decode($encodedContact, true);
                                            foreach($listContact as $contact){
                                                $icon           =   $contact['icon'];
                                                $title          =   $contact['title'];
                                                $description    =   $contact['description'];
                                                ?>
                                                    <div class="contact mb-4 pl-3">
                                                        <div class="row">
                                                            <span class="<?=$icon?> contact-icon"></span>
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
                                                                        <input type="text" class="form-control" placeholder='Nama Contact' 
                                                                            name='title[]' required 
                                                                            value='<?=$title?>' />
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <textarea class="form-control" placeholder='Deskripsi Contact' 
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
    let _formContactUs      =   $('#formContactUs');
    let _contactUs          =   $('#contact');

    let _listIconsHTML      =   `<?=$listIconOptions?>`;

    let _contactHTML        =   `<div class="contact mb-4 pl-3">
                                    <div class="row">
                                        <span class="contact-icon"></span>
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
                                                    <input type="text" class="form-control" placeholder='Nama Contact' 
                                                        name='title[]' required />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <textarea class="form-control" placeholder='Deskripsi Contact' 
                                                        name='description[]' required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="fa fa-trash text-danger cp ml-3"
                                            onClick='_delete(this)'></span>
                                    </div>
                                </div>`;

    _formContactUs.on('submit', async function(e){
        e.preventDefault();

        await submitForm(this, async (decodedRFS) => {
            let _status     =   decodedRFS.status;
            let _message    =   decodedRFS.message;

            let _title  =   'Contact Us';
            let _type   =   (_status)? 'success' : 'error';

            await notifikasi(_title, _message, _type);
            if(_status){
                location.href   =   `<?=site_url(adminController('website/landing-page/contact-us'))?>`;
            }
        })
    });

    
    async function _addContact(thisContext){
        let _el     =   $(thisContext);
        
        let _localContactHTML   =   _contactHTML;
        _contactUs.append(_localContactHTML);
    }
    
    $('.pilihan-icon').select2({
        theme: 'bootstrap4'
    });

    async function _iconChanged(thisContext){
        let _el         =   $(thisContext);
        let _parent     =   _el.parents('.contact');
        let _icon       =   _parent.find('.contact-icon');

        let _selectedIcon   =   _el.val();

        let _iconClass  =   `${_selectedIcon} contact-icon`;
        _icon.attr('class', _iconClass);
    }
    async function _delete(thisContext){
        let _el         =   $(thisContext);
        let _parent     =   _el.parents('.contact');

        _parent.remove();
    }
</script>
<style type='text/css'>
    .contact-icon{
        font-size: 44px;
        line-height: 44px;
        color: #0245bc;
        margin-right: 15px;
    }
</style>