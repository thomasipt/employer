<?php
    $appConfig      =   config('Config\App');
    $appName        =   $appConfig->appName;
    $appDescription =   $appConfig->appDescription;
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome | <?= $appName ?></title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" />
    <link rel="shortcut icon" href="<?= base_url(assetsFolder('img/icon.png')) ?>" />

    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="<?= base_url(flexStartAssets('img/favicon.png')) ?>" rel="icon">
    <link href="<?= base_url(flexStartAssets('img/apple-touch-icon.png')) ?>" rel="apple-touch-icon">

    <!-- Vendor CSS Files -->
    <link href="<?= base_url(flexStartAssets('vendor/aos/aos.css')) ?>" rel="stylesheet">
    <link href="<?= base_url(flexStartAssets('vendor/bootstrap/css/bootstrap.min.css')) ?>" rel="stylesheet">
    <link href="<?= base_url(flexStartAssets('vendor/bootstrap-icons/bootstrap-icons.css')) ?>" rel="stylesheet">
    <link href="<?= base_url(flexStartAssets('vendor/glightbox/css/glightbox.min.css')) ?>" rel="stylesheet">
    <link href="<?= base_url(flexStartAssets('vendor/remixicon/remixicon.css')) ?>" rel="stylesheet">
    <link href="<?= base_url(flexStartAssets('vendor/swiper/swiper-bundle.min.css')) ?>" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="<?= base_url('assets/flexstart/assets/css/style.css') ?>" rel="stylesheet" />

    <!-- Template CSS Loker Free -->
    <style>
      #lokerFree {
        font-size: 12px;
      }
    </style>
</head>
