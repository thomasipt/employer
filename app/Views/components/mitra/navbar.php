<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?=site_url(mitraController('profile'))?>">
                <i class="far fa-user"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link"
                href='<?=site_url(mitraController('logout'))?>'>
                <i class="fas text-danger fa-power-off"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->