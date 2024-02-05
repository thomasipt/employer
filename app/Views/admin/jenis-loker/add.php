<?php
    $detailJenisLoker   =   $data['detailJenisLoker'];

    $dJL                =   $detailJenisLoker;
    $doesUpdate         =   !empty($dJL);

    $actionURL  =   site_url(adminController('jenis-loker/save'));

    if($doesUpdate){
        $actionURL  =   site_url(adminController('jenis-loker/save/'.$dJL['id']));
    }
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class='mb-0'>Jenis Lowongan Pekerjaan</h5>
                        <span class="text-sm text-muted"><?=(!empty($pageDesc))? $pageDesc : 'Jenis Lowongan Pekerjaan Baru' ?></span>
                    </div>
                    <a href="<?=site_url(adminController('jenis-loker'))?>">
                        <button class="btn btn-primary">List Jenis Loker</button>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form id="formJenisLoker" action='<?=$actionURL?>' method='post'
                            data-id-jenis-loker='<?=($doesUpdate)? $dJL['id'] : '' ?>'>
                            <div class="col col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type='text' class='form-control' placeholder='Misal: Kontrak, Purna Waktu, dsb'
                                        name='nama' id='nama' value='<?=(!empty($dJL))? $dJL['nama'] : '' ?>' required />
                                </div>
                                <div class="form-group">
                                    <label for="benefit">Keterangan (Opsional)</label>
                                    <textarea class='form-control' placeholder='Opsional, informasi lebih lanjut tentang jenis pekerjaan'
                                        name='keterangan' id='keterangan'><?=(!empty($dJL))? $dJL['keterangan'] : '' ?></textarea>
                                </div>
                                <hr class='mb-4' />
                                <button class="btn btn-success" type='submit' id='btnSubmit'>Simpan <?=($doesUpdate)? 'Perubahan' : ''?></button>
                                <a href="<?=site_url(adminController('jenis-loker'))?>">
                                    <button class="btn btn-default" type='button'>Back to List Jenis Loker</button>
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
    let _formJenisLoker  =   $('#formJenisLoker');

    _formJenisLoker.on('submit', async function(e){
        e.preventDefault();

        await submitForm(this, async (decodedRFS) => {
            let _status     =   decodedRFS.status;
            let _message    =   decodedRFS.message;

            let _idJenisloker   =   _formJenisLoker.data('idJenisLoker');   
            let _isUpdate       =   (_idJenisloker != '');

            let _title  =   (_isUpdate)? 'Update Jenis Loker' : 'Jenis Loker Baru';
            let _type   =   (_status)? 'success' : 'error';

            await notifikasi(_title, _message, _type);
            if(_status){
                location.href   =   `<?=site_url(adminController('jenis-loker'))?>`;
            }
        })
    });

    $('.hirarki').on('change', function(){
        hirarki(this);
    });
</script>