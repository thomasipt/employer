<?php
    $listSektor =   $data['listSektor'];
?>
<div class="container">
    <div class="row">
        <div class="col-lg-4">
            <div class="p-3" style='background-color: #ecf3ff; border-radius: 10px;'>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Blanditiis facere debitis modi officia nobis dolorem. Excepturi aliquam iste qui labore numquam! Exercitationem ipsam odio rem recusandae laboriosam voluptas, sit veniam?
            </div>
        </div>
        <div class="col-lg-8" id='formContainer'>
            <h5><b>Form Registrasi</b></h5>
            <br />
            <form action="<?=site_url(mitraController('registrasi'))?>" id="formMitra">
                <div class="form-group mb-3">
                    <label for="nama" class='d-block mb-2'>Nama</label>
                    <input type="text" id="nama" class="form-control" name='nama'
                        placeholder='Nama Lengkap Perusahaan' required />
                </div>
                <div class="form-group mb-3">
                    <label for="alamat" class='d-block mb-2'>Alamat</label>
                    <textarea id="alamat" class="form-control" name='alamat'
                        placeholder='Alamat Lengkap Perusahaan' required></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="telepon" class='d-block mb-2'>Telepon</label>
                    <input type="number" id="telepon" class="form-control" name='telepon'
                        placeholder='Nomor Telepon' required />
                </div>
                <div class="form-group mb-3">
                    <label for="email" class='d-block mb-2'>Email</label>
                    <input type="email" id="email" class="form-control" name='email'
                        placeholder='Alamat Email Resmi' required />
                </div>
                <div class="form-group mb-3">
                    <label for="sektor" class='d-block mb-2'>Sektor</label>
                    <select name='sektor' class='form-control' id='sektor'>
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
                        placeholder='Nama Lengkap Penanggung Jawab' required />
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

<script src='<?=base_url(assetsFolder('custom/js/form-submission.js'))?>'></script>

<script src='<?=base_url(assetsFolder('plugins/select2/js/select2.min.js'))?>'></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/select2/css/select2.min.css'))?>" />
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css'))?>" />

<script language='Javascript'>
    let _formMitra  =   $('#formMitra');

    $('#sektor').select2({
        theme: 'bootstrap4'
    });

    _formMitra.on('submit', async function(e){
        e.preventDefault();
        await submitForm(this, async function(responseFromServer){
            console.log(responseFromServer);
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