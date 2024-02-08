<?php
    $detailKategoriLoker    =   $data['detailKategoriLoker'];

    $dKL                =   $detailKategoriLoker;
    $doesUpdate         =   !empty($dKL);

    $actionURL      =   site_url(adminController('kategori-loker/save'));
    if($doesUpdate){
        $actionURL  =   site_url(adminController('kategori-loker/save/'.$dKL['id']));
    }
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class='mb-0'>Kategori Lowongan Pekerjaan</h5>
                        <span class="text-sm text-muted"><?=(!empty($pageDesc))? $pageDesc : 'Kategori Lowongan Pekerjaan Baru' ?></span>
                    </div>
                    <a href="<?=site_url(adminController('kategori-loker'))?>">
                        <button class="btn btn-primary">List Kategori Loker</button>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form id="formKategoriLoker" action='<?=$actionURL?>' method='post'
                            data-id-kategori-loker='<?=($doesUpdate)? $dKL['id'] : '' ?>'>
                            <div class="col col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type='text' class='form-control' placeholder='Misal: Sekretaris, Rektor Universitas, Programmer, dsb'
                                        name='nama' id='nama' value='<?=(!empty($dKL))? $dKL['nama'] : '' ?>' required />
                                </div>
                                <hr class='mb-4' />
                                <button class="btn btn-success" type='submit' id='btnSubmit'>Simpan <?=($doesUpdate)? 'Perubahan' : ''?></button>
                                <a href="<?=site_url(adminController('kategori-loker'))?>">
                                    <button class="btn btn-default" type='button'>Back to List Kategori Loker</button>
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
    let _formKategoriLoker  =   $('#formKategoriLoker');

    _formKategoriLoker.on('submit', async function(e){
        e.preventDefault();

        await submitForm(this, async (decodedRFS) => {
            let _status     =   decodedRFS.status;
            let _message    =   decodedRFS.message;

            let _idKategoriloker    =   _formKategoriLoker.data('idKategoriLoker');   
            let _isUpdate           =   (_idKategoriloker != '');

            let _title  =   (_isUpdate)? 'Update Kategori Loker' : 'Kategori Loker Baru';
            let _type   =   (_status)? 'success' : 'error';

            await notifikasi(_title, _message, _type);
            if(_status){
                location.href   =   `<?=site_url(adminController('kategori-loker'))?>`;
            }
        })
    });

    $('.hirarki').on('change', function(){
        hirarki(this);
    });
</script>