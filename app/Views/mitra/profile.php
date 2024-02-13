<?php
    $detailMitra    =   $data['detailMitra'];

    $idMitra                =   $detailMitra['id'];
    $fotoMitra              =   $detailMitra['foto'];
    $namaMitra              =   $detailMitra['nama'];
    $alamatMitra            =   $detailMitra['alamat'];
    $teleponMitra           =   $detailMitra['telepon'];
    $emailMitra             =   $detailMitra['email'];
    $tanggalDaftarMitra     =   $detailMitra['createdAt'];

    $headData   =   [
        'pageTitle' =>  'Profile',
        'pageDesc'  =>  $namaMitra
    ];
?>
<!DOCTYPE html>
<html lang="en">
    <?=view(mitraComponents('head'), $headData)?>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper"> 
            <?=view(mitraComponents('navbar'))?>
            <?=view(mitraComponents('sidebar'))?>

            <div class="content-wrapper">
                <?=view(mitraComponents('page-header'), $headData)?>
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-header">
                                        Profil Singkat
                                    </div>
                                    <div class="card-body">
                                        <img src="<?=base_url(uploadGambarMitra($fotoMitra))?>" alt="<?=$namaMitra?>"
                                            class='m-auto d-block img-circle img-150-150' style='object-fit: cover;' />
                                        <br />
                                        <h5 class='text-center'><b><?=$namaMitra?></b></h5>
                                        <p class="text-sm text-muted text-center"><?=$alamatMitra?></p>
                                        <br />
                                        <h6><span class='mr-2 text-success fa fa-phone'></span> <b>Telepon</b></h6>
                                        <p class="text-muted text-sm"><?=$teleponMitra?></p>
                                        <h6><span class='mr-2 text-primary fa fa-at'></span> <b>Email</b></h6>
                                        <p class="text-muted text-sm"><?=$emailMitra?></p>
                                        <h6><span class='mr-2 text-yellow fa fa-map-marker'></span> <b>Tanggal Terdaftar</b></h6>
                                        <p class="text-muted text-sm"><?=formattedDateTime($tanggalDaftarMitra)?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="card">
                                    <div class="card-header">
                                        Form Update Profile
                                    </div>
                                    <div class="card-body">
                                        <form action="<?=site_url(mitraController('update-profile'))?>" id="formMitra">
                                            <div class="form-group">
                                                <label for="nama">Nama</label>
                                                <input type="text" class="form-control" id="nama" placeholder='Nama Lengkap Mitra'
                                                    value='<?=$namaMitra?>' name='nama' />
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="email">Email</label>
                                                        <input type="email" class="form-control" id="email" placeholder='Email Mitra'
                                                            value='<?=$emailMitra?>' name='email' />
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="telepon">Nomor Telepon</label>
                                                        <input type="number" class="form-control" id="telepon" placeholder='Telepon Mitra'
                                                            value='<?=$teleponMitra?>' name='telepon' />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="alamat">Alamat</label>
                                                <textarea class="form-control" id="alamat"
                                                    placeholder='Alamat Lengkap Mitra' name='alamat'><?=$alamatMitra?></textarea>
                                            </div>
                                            <hr />
                                            <button class="btn btn-success" id='btnSubmit' type='submit'>Update Profile</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?=view(mitraComponents('footer'))?>
        </div>
    </body>
</html>
<link rel="stylesheet" href='https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css' />

<script src="<?=base_url(assetsFolder('custom/js/form-submission.js'))?>"></script>
<script src="<?=base_url(assetsFolder('custom/js/custom-alert.js'))?>"></script>

<script language='Javascript'>
    let _formMitra  =   $('#formMitra');

    _formMitra.on('submit', async function(e) {
        e.preventDefault();
        await submitForm(this, async (responseFromServer) => {
            let _status     =   responseFromServer.status;
            let _message    =   responseFromServer.message;

            let _swalTitle      =   `Update Profile`;
            let _swalMessage    =   (_message == null)? (_status)? 'Berhasil!' : 'Gagal!' : _message;
            let _swalType       =   (_status)? 'success' : 'error';

            await notifikasi(_swalTitle, _swalMessage, _swalType);
            if(_status){
                location.reload();
            }
        });
    });
</script>