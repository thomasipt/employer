<?php
    $paketModel             =   $models['paket'];
    $homepageElementModel   =   $models['homepageElement'];
    $pageTitle      =   (isset($pageTitle))? $pageTitle : null;

    $headData   =   [
        'pageTitle' =>  $pageTitle
    ];

    $view   =   (isset($view))? $view : null;
    
    #Element
    $heroElement        =   $data['heroElement'];
    $aboutUsElement     =   $data['aboutUsElement'];
    $featuresElement    =   $data['featuresElement'];
    $paketElement       =   $data['paketElement'];

    #Data
    $listPaket          =   $data['listPaket'];
    $listLokerPremium   =   $data['listLokerPremium'];
    $listLokerFree      =   $data['listLokerFree'];

    #Image Element
    $heroImagePath      =   $heroElement['_image'];
    $aboutUsImagePath   =   $aboutUsElement['_image'];
    $featuresImagePath  =   $featuresElement['_image'];
?>
<html lang="en">
    <?=view(websiteComponents('head'), $headData)?>
    <body>
        <?=view(websiteComponents('navbar'))?>

        <!-- ======= Hero Section ======= -->
        <section id="hero" class="hero d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 d-flex flex-column justify-content-center">
                        <h1 data-aos="fade-up"><?=$heroElement['_title']?></h1>
                        <h2 data-aos="fade-up" data-aos-delay="400"><?=$heroElement['_description']?></h2>
                        <div data-aos="fade-up" data-aos-delay="600">
                            <div class="text-center text-lg-start">
                                <a href="<?=site_url(websiteController('registrasi'))?>" class="btn-get-started scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                                    <span>Registrasi</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 hero-img" data-aos="zoom-out" data-aos-delay="200">
                        <img src="<?=base_url(uploadGambarWebsite('landing-page'))?>/<?=$heroImagePath?>" alt='Hero Image' class="img-fluid" />
                    </div>
                </div>
            </div>
        </section>
        <!-- End Hero -->

        <main id="main">
            <!-- ======= About Section ======= -->
            <section id="about" class="about">

                <div class="container" data-aos="fade-up">
                    <div class="row gx-0">

                        <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="200">
                            <div class="content">
                                <h3>Who We Are</h3>
                                <h2><?=$aboutUsElement['_title']?></h2>
                                <p><?=$aboutUsElement['_description']?></p>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="200">
                            <?php if(!empty($aboutUsImagePath)){ ?>
                                <img src="<?=base_url(uploadGambarWebsite('landing-page'))?>/<?=$aboutUsImagePath?>" class="img-fluid" alt="About Us" />
                            <?php } ?>
                        </div>

                    </div>
                </div>

            </section><!-- End About Section -->

            <!-- ======= Features Section ======= -->
            <section id="features" class="features">

                <div class="container" data-aos="fade-up">
                    <!-- Feature Icons -->
                    <div class="row feature-icons" data-aos="fade-up">
                        <h3><?=$featuresElement['_title']?></h3>

                        <div class="row">
                            <div class="col-xl-4 text-center" data-aos="fade-right" data-aos-delay="100">
                                <?php if(!empty($featuresImagePath)){ ?>
                                    <img src="<?=base_url(uploadGambarWebsite('landing-page'))?>/<?=$featuresImagePath?>" class="img-fluid p-4" alt="Features" />
                                <?php } ?>
                            </div>

                            <div class="col-xl-8 d-flex content">
                                <div class="row align-self-center gy-4">
                                    <?php 
                                        $features       =   $featuresElement['_feature'];
                                        $listFeatures   =   json_decode($features, true);
                                    ?>
                                    <?php foreach($listFeatures as $index => $feature){ ?>
                                        <?php
                                            $delay                  =   $index * 100;
                                            $featureIcon            =   $feature['icon'];
                                            $featureTitle           =   $feature['title'];
                                            $featureDescription     =   $feature['description'];
                                        ?>
                                        <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="<?=$delay?>">
                                            <i class="<?=$featureIcon?>"></i>
                                            <div>
                                                <h4><?=$featureTitle?></h4>
                                                <p><?=$featureDescription?></p>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                        </div>

                    </div><!-- End Feature Icons -->

                </div>

            </section><!-- End Features Section -->

            <?php if(count($listPaket) >= 1){ ?>
                <!-- ======= Pricing Section ======= -->
                <section id="pricing" class="pricing">
                    <div class="container" data-aos="fade-up">
                        <header class="section-header">
                            <h2><?=$paketElement['_title']?></h2>
                            <p><?=$paketElement['_description']?></p>
                        </header>
                        <div class="row gy-4 d-flex justify-content-center" data-aos="fade-left">
                            <?php foreach($listPaket as $index => $paket){ ?>
                                <?php
                                    $code   =   $paket['code'];

                                    $fotoPaket          =   $paket['foto'];
                                    $namaPaket          =   $paket['nama'];
                                    $hargaPaket         =   $paket['harga'];
                                    $durasiPaket        =   $paket['durasi'];
                                    $keteranganPaket    =   $paket['keterangan'];

                                    $colorCode  =   $paketModel->color[$code];

                                    $namaPaketColor =   '#65c600';
                                    if($index == 1){
                                        $namaPaketColor = '#ff901c';
                                    }
                                    if($index == 2){
                                        $namaPaketColor =   '#ff0071';
                                    }
                                ?>
                                <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
                                    <div class="box">
                                        <h3 style="color: <?=$namaPaketColor?>;"><?=$namaPaket?></h3>
                                        <div class="price" style='color: <?=$colorCode?>'>
                                            <sup>Rp.</sup> <?=number_format($hargaPaket)?><span></span>
                                        </div>
                                            <p class='text-center text-sm'><?=$durasiPaket?> Hari</p>
                                        <img src="<?=base_url(uploadGambarPaket($fotoPaket))?>" 
                                            class="img-fluid" alt="<?=$namaPaket?>" 
                                            onError='this.src="<?=base_url(assetsFolder('img/empty.png'))?>"' />
                                        <div class="d-block"><?=$keteranganPaket?></div>
                                        <br />
                                        <br />
                                        <a href="<?=site_url(mitraController('transaksi/checkout'))?>/<?=$code?>"
                                            class="btn-buy" target='_blank'>Buy Now</a>
                                    </div>
                                </div>                        
                            <?php } ?>
                        </div>
                    </div>
                </section>
                <!-- End Pricing Section -->
            <?php } ?>

            <section id="lokerPremium" class="loker-premium recent-blog-posts">

                <div class="container" data-aos="fade-up">

                    <header class="section-header">
                        <h2>Loker Premium</h2>
                        <p>Postingan Loker Premium Terbaru</p>
                    </header>
                        <div class="row">
                            <?php if(count($listLokerPremium) >= 1){ ?>
                                <?php foreach($listLokerPremium as $lokerPremium){ ?>
                                    <?php
                                        $idLoker                =   $lokerPremium['id'];
                                        $judulLoker             =   $lokerPremium['judul'];
                                        $deskripsiLoker         =   $lokerPremium['deskripsi'];
                                        $tanggalPostingLoker    =   $lokerPremium['createdAt'];
                                    ?>
                                    <div class="col-lg-4">
                                        <div class="post-box col">
                                            <h3 class="post-title"><?=$judulLoker?></h3>
                                            <?php if(!empty($deskripsiLoker)){ ?>
                                                <p class="text-sm text-muted"><?=$deskripsiLoker?></p>
                                            <?php } ?>
                                            <br />
                                            Diposting pada <?=formattedDate($tanggalPostingLoker)?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="col text-center mt-5">
                                    <a href="<?=site_url(websiteController('loker-premium'))?>">
                                        <button class="btn btn-primary">Lihat Loker Premium Lainnya</button>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                </div>

            </section>

            <section id="lokerFree" class="loker-premium recent-blog-posts">

                <div class="container" data-aos="fade-up">

                    <header class="section-header">
                        <h2>Loker Free</h2>
                        <p>Postingan Loker Free Terbaru</p>
                    </header>
                        <div class="row">
                            <?php if(count($listLokerFree) >= 1){ ?>
                                <?php foreach($listLokerFree as $lokerFree){ ?>
                                    <?php
                                        $idLoker                =   $lokerFree['id'];
                                        $judulLoker             =   $lokerFree['judul'];
                                        $deskripsiLoker         =   $lokerFree['deskripsi'];
                                        $tanggalPostingLoker    =   $lokerFree['createdAt'];
                                    ?>
                                    <div class="col-lg-4">
                                        <div class="post-box col">
                                            <h3 class="post-title"><?=$judulLoker?></h3>
                                            <?php if(!empty($deskripsiLoker)){ ?>
                                                <p class="text-sm text-muted"><?=$deskripsiLoker?></p>
                                            <?php } ?>
                                            <br />
                                            Diposting pada <?=formattedDate($tanggalPostingLoker)?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="col text-center mt-5">
                                    <a href="<?=site_url(websiteController('loker-free'))?>">
                                        <button class="btn btn-primary">Lihat Loker Free Lainnya</button>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                </div>

            </section>


            <!-- ======= Contact Section ======= -->
            <section id="contact" class="contact">

                <div class="container" data-aos="fade-up">

                    <header class="section-header">
                        <h2>Contact</h2>
                        <p>Contact Us</p>
                    </header>

                    <div class="row gy-4">

                        <div class="col-lg-6">

                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <i class="bi bi-geo-alt"></i>
                                        <h3>Address</h3>
                                        <p>A108 Adam Street,<br>New York, NY 535022</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <i class="bi bi-telephone"></i>
                                        <h3>Call Us</h3>
                                        <p>+1 5589 55488 55<br>+1 6678 254445 41</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <i class="bi bi-envelope"></i>
                                        <h3>Email Us</h3>
                                        <p>info@example.com<br>contact@example.com</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <i class="bi bi-clock"></i>
                                        <h3>Open Hours</h3>
                                        <p>Monday - Friday<br>9:00AM - 05:00PM</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-6">
                            <form action="forms/contact.php" method="post" class="php-email-form">
                                <div class="row gy-4">

                                    <div class="col-md-6">
                                        <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                                    </div>

                                    <div class="col-md-6 ">
                                        <input type="email" class="form-control" name="email" placeholder="Your Email" required>
                                    </div>

                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="subject" placeholder="Subject" required>
                                    </div>

                                    <div class="col-md-12">
                                        <textarea class="form-control" name="message" rows="6" placeholder="Message" required></textarea>
                                    </div>

                                    <div class="col-md-12 text-center">
                                        <div class="loading">Loading</div>
                                        <div class="error-message"></div>
                                        <div class="sent-message">Your message has been sent. Thank you!</div>

                                        <button type="submit">Send Message</button>
                                    </div>

                                </div>
                            </form>

                        </div>

                    </div>

                </div>

            </section><!-- End Contact Section -->

        </main>
        <!-- End #main -->

        <?=view(websiteComponents('footer'))?>
    </body>
</html>
<script src='<?=base_url(assetsFolder('plugins/jquery/jquery.min.js'))?>'></script>
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script language='Javascript'>
    let _qrCode     =   $('.qr-code');
    _qrCode.each((index, qrCodeElement) => {
        let _qrCodeItem     =   $(qrCodeElement);
        let _idLoker        =   _qrCodeItem.data('qrData');
        
        new QRCode(document.getElementById(`qrCode-${_idLoker}`), {
            text: `${_idLoker}`,
            width: 50.0,
            height: 50.0
        });
    });
</script>