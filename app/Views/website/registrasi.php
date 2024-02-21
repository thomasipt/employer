<?php
    $listSektor     =   $data['listSektor'];
    $errorLib       =   $library['error'];
    $emailSender    =   $library['emailSender'];
?>
<div class="container">
    <div class="row">
        <div class="col-lg-5 d-flex align-items-center px-5">
            <img src="<?=base_url(assetsFolder('flexstart/assets/img/features-3.png'))?>" class='w-100 d-block m-auto' alt="Pendaftaran Mitra" />
        </div>
        <div class="col-lg-7" id='formContainer'>
            <h5><b>Form Registrasi</b></h5>
            <br />
            <form action="<?=site_url(websiteController('process-registrasi'))?>" id="formMitra">
                <div class="form-group mb-3">
                    <label for="nama" class='d-block mb-2'>Nama</label>
                    <input type="text" id="nama" class="form-control" name='nama'
                        placeholder='Nama Lengkap Perusahaan' required  />
                </div>
                <div class="form-group mb-3">
                    <label for="alamat" class='d-block mb-2'>Alamat</label>
                    <textarea id="alamat" class="form-control" name='alamat'
                        placeholder='Alamat Lengkap Perusahaan' required ></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="telepon" class='d-block mb-2'>Telepon</label>
                    <input type="number" id="telepon" class="form-control" name='telepon'
                        placeholder='Nomor Telepon' required  />
                </div>
                <div class="form-group mb-3">
                    <label for="email" class='d-block mb-2'>Email</label>
                    <input type="email" id="email" class="form-control" name='email'
                        placeholder='Alamat Email Resmi' required  />
                </div>
                <div class="form-group mb-3">
                    <label for="sektor" class='d-block mb-2'>Sektor</label>
                    <select name='sektor' class='form-control' id='sektor' required >
                        <option value="">-- Pilih Sektor --</option>
                        <?php foreach($listSektor as $sektor){ ?>
                            <?php
                                $idSektor       =   $sektor['id'];
                                $namaSektor     =   $sektor['nama'];
                            ?>
                            <option value="<?=$idSektor?>"><?=$namaSektor?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="pic" class='d-block mb-2'>Nama Penanggung Jawab</label>
                    <input type="text" id="pic" class="form-control" name='pic'
                        placeholder='Nama Lengkap Penanggung Jawab' required  />
                </div>
                <hr class='mb-4' />
                <button class="btn btn-success" type='submit' id='btnSubmit'>
                    Daftar sebagai Mitra
                </button>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url(assetsFolder('plugins/jquery/jquery.min.js')) ?>/"></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/ionicons/ionicons.min.css'))?>" />

<script src='<?= base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.js')) ?>'></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.css'))?>" />

<script src='<?=base_url(assetsFolder('custom/js/form-submission.js'))?>'></script>
<script src='<?=base_url(assetsFolder('custom/js/custom-alert.js'))?>'></script>

<script src='<?=base_url(assetsFolder('plugins/select2/js/select2.min.js'))?>'></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/select2/css/select2.min.css'))?>" />
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css'))?>" />

<script language='Javascript'>
    let _formMitra      =   $('#formMitra');

    $('#sektor').select2({
        theme: 'bootstrap4'
    });

    _formMitra.on('submit', async function(e){
        e.preventDefault();
        await submitForm(this, async function(responseFromServer){
            let _formValidationErrorCode    =   `<?=$errorLib->formValidationError?>`;

            let _status     =   responseFromServer.status;
            let _code       =   responseFromServer.code;
            let _message    =   responseFromServer.message;
            let _data       =   responseFromServer.data;
            
            let _swalTitle      =   'Pendaftaran Mitra';
            let _swalMessage    =   (_message == null)? (_status)? 'Berhasil!' : 'Gagal!' : _message;
            let _swalType       =   (_status)? 'success' : 'error';
            
            await notifikasi(_swalTitle, _swalMessage, _swalType);

            if(_status){
                location.href   =   `<?=site_url()?>`;
            }else{
                if(_code == _formValidationErrorCode){
                    //Reset Form Validation
                    let _formControlElement     =   _formMitra.find('.form-control');
                    _formControlElement.removeClass('border-danger');
                    _formControlElement.next().remove();

                    //Memunculkan error
                    $.each(_data, function(formName, formError){
                        let _formElement    =   `[name=${formName}]`;
                        
                        // $(_formElement).removeClass('border-danger');
                        $(_formElement).addClass('border-danger');

                        let _nextElement    =   $(_formElement).next();
                        if(_nextElement.length >= 1){
                            _nextElement.remove();
                        }
                        $(_formElement).after(`<p class='text-sm mt-2 text-danger'>${formError}</p>`);
                    });
                }
            }
        });
    });
</script>

<style type='text/css'>
    @media screen and (max-width: 576px) {
        #formContainer{
            margin-top: 45px;
        }
    }
</style>