<?php
    $appConfig  =   config('Config\App');
    $appName    =   $appConfig->appName;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mitra Login Page</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/fontawesome-free/css/all.min.css'))?>" />
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=base_url(assetsFolder('dist/css/adminlte.min.css'))?>">

    <link rel="shortcut icon" href="<?= base_url(assetsFolder('img/icon.png')) ?>" />
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href='#'><b>Mitra</b><br /><?=$appName?></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body py-4">
                <img src="<?=base_url(assetsFolder('img/icon.png'))?>" alt="<?=$appName?>"
                    class='m-auto d-block' style='width:125px;' />
                <p class="login-box-msg mt-3">Dashboard Mitra</p>

                <form id='formLogin'>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="fas fa-at"></span>
                            </div>
                        </div>
                        <input type="text" class="form-control" placeholder="Email or Username" name='username' />
                    </div>
                    <div class="input-group mb-3" id='passwordInputGroup'>
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <input type="password" class="form-control password" placeholder="Password" name='password' />
                        <div class="input-group-append">
                            <div class="input-group-text cp" onClick='togglePassword(this, "#passwordInputGroup")'>
                                <span class="fas fa-eye password-icon"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-primary btn-block"
                                id='btnSignIn'>Sign In</button>
                            <p class="text-center mb-0 mt-3">
                                Belum punya akun <a href="<?=site_url(websiteController('registrasi'))?>">Daftar di sini</a>
                                <br />
                                <a href="<?=site_url(mitraController('lupa-password'))?>">Lupa Password</a>
                            </p>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <script src="<?=base_url(assetsFolder('plugins/jquery/jquery.min.js'))?>"></script>
    <script src="<?=base_url(assetsFolder('plugins/bootstrap/js/bootstrap.bundle.min.js'))?>"></script>
    <script src="<?=base_url(assetsFolder('dist/js/adminlte.min.js'))?>"></script>
</body>

</html>
<script src='<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.js'))?>'></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.css'))?>" /> 

<script src='<?=base_url(assetsFolder('custom/js/custom-alert.js'))?>'></script>
<script src='<?=base_url(assetsFolder('custom/js/toggle-password.js'))?>'></script>

<script language='Javascript'>
    let _formLogin      =   $('#formLogin');
    let _buttonSignIn   =   $('#btnSignIn');
    _formLogin.on('submit', function(e){
        e.preventDefault();
        let _formData   =  _formLogin.serialize(); 

        let _btnText    =   _buttonSignIn.text();
        _buttonSignIn.prop('disabled', true).text('Processing ..');
        
        $.ajax({
            url     :   `<?=site_url(mitraController('auth-process'))?>`,
            type    :   'POST',
            data    :   _formData,
            success :   async (decodedRFS) => {
                _buttonSignIn.prop('disabled', false).text(_btnText);

                let _status     =   decodedRFS.status;
                if(_status){
                    location.href   =   `<?=site_url(mitraController())?>`;
                }else{
                    let _message    =   decodedRFS.message;

                    let _messageString    =   `Login gagal!`;
                    if(_message != null){
                        _messageString  =   _message;
                    }

                    let _swalTitle  =   `Login`;
                    let _swalMessage, _swalType;
                    _swalMessage    =   `<span class='text-danger'>${_messageString}</span>`;
                    _swalType       =   'error';
                    await notifikasi(_swalTitle, _swalMessage, _swalType);
                }
            }
        })
    });
</script>
<style type='text/css'>
   .w-150{
        width: 150px;
        height: 150px;
    }
    .w-250{
        width: 250px;
        height: 250px;
    }
    .cp{
        cursor: pointer;
    }
</style>