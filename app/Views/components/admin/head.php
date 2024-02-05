<?php
    if(!isset($pageTitle) || empty($pageTitle)){
        $pageTitle  =   'Dashboard Administrator';
    }

    $appConfig  =   config('Config\App');
    $appName    =   $appConfig->appName;
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$pageTitle?> | <?=$appName?></title>

    <!-- jQuery -->
    <script src="<?= base_url(assetsFolder('plugins/jquery/jquery.min.js')) ?>/"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="<?= base_url(assetsFolder('plugins/jquery-ui/jquery-ui.min.js')) ?>"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url(assetsFolder('plugins/bootstrap/js/bootstrap.bundle.min.js')) ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url(assetsFolder('dist/js/adminlte.js')) ?>"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/fontawesome-free/css/all.min.css')) ?>" />
    <link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/ionicons/ionicons.min.css')) ?>" />
    <link rel="stylesheet" href="<?= base_url(assetsFolder('dist/css/adminlte.min.css')) ?>" />
    <link rel="shortcut icon" href="<?= base_url(assetsFolder('img/icon.png')) ?>" />

    <script src='<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.js'))?>'></script>
    <link rel="stylesheet" href='<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.css'))?>' />
    
    <style type='text/css'>
        .cp{
            cursor:pointer;
        }
        .vam{
            vertical-align: middle !important;
        }
        .img-50-50{
            width: 50px;
            height: 50px;
        }
        .img-100-100{
            width: 100px;
            height: 100px;
        }
        .img-150-150{
            width: 150px;
            height: 150px;
        }
        .of-cover{
            object-fit: cover;
        }
        .blink{
            animation: blink 1s linear infinite;
        }
        .border-image{
            border: 3.5px solid #0D9AD7;
            border-radius: 50%;
            padding: 3.75px;
        }
        @keyframes blink{
            0%{opacity: 0;}
            50%{opacity: .5;}
            100%{opacity: 1;}
        }
    </style>
</head>