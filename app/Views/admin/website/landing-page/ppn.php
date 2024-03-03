<?php
    $ppnElement     =   $data['ppnElement'];
    $ppnActive      =   $ppnElement['_active'];
    $persentasePPN  =   $ppnElement['_value'];  
    
    $isActive   =   $ppnActive == 'true';
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class='mb-0'>PPN Transaksi</h5>
            </div>
            <div class="card-body">
                <?php if(!$isActive){ ?>
                    <div class="alert alert-warning text-sm">PPN tidak aktif, sehingga transaksi yang dilakukan oleh mitra tidak akan dimintai PPN</div>
                <?php } ?>
                <form id="formPPN" action='<?=base_url(adminController('website/landing-page/ppn'))?>' method='post'
                    enctype='multipart/form-data'>
                    <div class="form-group">
                        <label for="_value">Persentase PPN</label>
                        <div class="input-group">
                            <select name="_active" id="ppnActive" class="form-control" required>
                                <option value="true" <?=($ppnActive == 'true')? 'selected' : ''?>>Aktif</option>
                                <option value="false" <?=($ppnActive == 'false')? 'selected' : ''?>>Non Aktif</option>
                            </select>
                            <input type='number' class='form-control' placeholder='Persentase PPN'
                                name='_value' id='_value' value='<?=$persentasePPN?>'
                                required />
                        </div>
                    </div>
                    <hr class='mb-4' />
                    <button class="btn btn-success" type='submit' id='btnSubmit'>Simpan</button>
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
    let _formPPN       =   $('#formPPN');

    _formPPN.on('submit', async function(e){
        e.preventDefault();

        await submitForm(this, async (decodedRFS) => {
            let _status     =   decodedRFS.status;
            let _message    =   decodedRFS.message;

            let _title  =   'PPN Transaksi';
            let _type   =   (_status)? 'success' : 'error';

            await notifikasi(_title, _message, _type);
            if(_status){
                location.href   =   `<?=site_url(adminController('website/landing-page/ppn'))?>`;
            }
        })
    });
</script>