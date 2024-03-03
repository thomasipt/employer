<!-- ======= Header ======= -->
<header id="header" class="header fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

        <a href="<?=site_url()?>" class="logo d-flex align-items-center">
            <img src="<?=base_url(assetsFolder('img/icon.png'))?>" alt="Employer" />
            <span>Employer</span>
        </a>

        <nav id="navbar" class="navbar">
            <ul>
                <li><a class="nav-link scrollto active" href='<?=site_url('#hero')?>'>Home</a></li>
                <li><a class="nav-link scrollto" href='<?=site_url('#about')?>'>About</a></li>
                <li><a class="nav-link scrollto" href='<?=site_url('#features')?>'>Features</a></li>
                <li><a class="nav-link scrollto" href='<?=site_url(websiteController('loker-free'))?>'>Loker Free</a></li>
                <li><a class="nav-link scrollto" href='<?=site_url(websiteController('loker-premium'))?>'>Loker Premium</a></li>
                <li><a class="nav-link scrollto" href='<?=site_url('#contact')?>'>Contact</a></li>
                <li>
                    <a href="<?=site_url(mitraController())?>" class='btn-registrasi'>
                        <button class='btn btn-primary'>Login</button>
                    </a>
                </li>
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
        <!-- .navbar -->

    </div>
</header>
<!-- End Header -->