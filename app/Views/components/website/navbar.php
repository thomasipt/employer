<!-- ======= Header ======= -->
<header id="header" class="header fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

        <a href="<?=site_url()?>" class="logo d-flex align-items-center">
            <img src="<?=base_url('assets/flexstart/assets/img/logo.png')?>" alt="Employer" />
            <span>Employer</span>
        </a>

        <nav id="navbar" class="navbar">
            <ul>
                <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
                <li><a class="nav-link scrollto" href="#about">About</a></li>
                <li><a class="nav-link scrollto" href="#features">Features</a></li>
                <li><a class="nav-link scrollto" href='#lokerPremium'>Loker Premium</a></li>
                <li><a class="nav-link scrollto" href="#contact">Contact</a></li>
                <li><a class="nav-link scrollto" href="<?=site_url(websiteController('syarat-dan-ketentuan'))?>">Syarat & Ketentuan</a></li>
                <li><a class="nav-link scrollto" href="<?=site_url(websiteController('kebijakan-privasi'))?>">Kebijakan Privasi</a></li>
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