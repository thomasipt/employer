<?php
    $appConfig      =   config('Config\App');
    $emailSender    =   $library['emailSender'];

    $appName    =   $appConfig->appName;

    $username   =   $emailSender->username;
    $password   =   $emailSender->password;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrator Login Page</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/fontawesome-free/css/all.min.css')) ?>" />
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url(assetsFolder('dist/css/adminlte.min.css')) ?>">

    <link rel="shortcut icon" href="<?= base_url(assetsFolder('img/icon.png')) ?>" />
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href='#'><b>Administrator</b><br /><?= $appName ?></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body py-4">
                <img src="<?= base_url(assetsFolder('img/icon.png')) ?>" alt="<?= $appName ?>" class='m-auto d-block' style='width:125px;' />
                <p class="login-box-msg mt-3">Lupa Password</p>

                <form id='formLupaPassword' method='post' action='<?= site_url(adminController('lupa-password')) ?>' onSubmit='_formLupaPasswordSubmit(this, event)'>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Email or Username" name='username' />
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <?php if (!empty($username) && !empty($password)) { ?>
                                <button type="submit" class="btn btn-primary btn-block" id='btnSignIn'>Proses</button>
                            <?php } ?>
                            <?php if (empty($username) || empty($password)) { ?>
                                <div class="alert alert-warning">
                                    <p class="text-center mb-0 text-sm">Harap lakukan konfigurasi email (username, dan password)</p>
                                </div>
                            <?php } ?>
                            <p class="text-center mb-0 mt-3">
                                <a href="<?= site_url(adminController('login')) ?>">Login</a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <script src="<?= base_url(assetsFolder('plugins/jquery/jquery.min.js')) ?>"></script>
    <script src="<?= base_url(assetsFolder('plugins/bootstrap/js/bootstrap.bundle.min.js')) ?>"></script>
    <script src="<?= base_url(assetsFolder('dist/js/adminlte.min.js')) ?>"></script>
</body>

</html>
<script src='<?= base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.js')) ?>'></script>
<link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.css')) ?>" />
<script src='<?= base_url(assetsFolder('custom/js/custom-alert.js')) ?>'></script>
<script src='<?= base_url(assetsFolder('custom/js/form-submission.js')) ?>'></script>
<script language='Javascript'>
    let _formLupaPassword = $('#formLupaPassword');

    async function _formLupaPasswordSubmit(thisContext, e) {
        e.preventDefault();

        let _formData = _formLupaPassword.serialize();
        await submitForm(thisContext, async function(responseFromServer) {
            console.log(responseFromServer);
        });
    }
</script>
<style type='text/css'>
    .w-150 {
        width: 150px;
        height: 150px;
    }

    .w-250 {
        width: 250px;
        height: 250px;
    }
</style>