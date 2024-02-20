<?php
    $paketModel             =   $models['paket'];
    $homepageElementModel   =   $models['homepageElement'];
    $pageTitle      =   (isset($pageTitle))? $pageTitle : null;

    $headData   =   [
        'pageTitle' =>  $pageTitle
    ];

    $view   =   (isset($view))? $view : null;
    
    $listPaket      =   $data['listPaket'];
    $heroElement    =   $data['heroElement'];
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
                        <img src="<?=$heroElement['_image']?>" alt='Hero Image' class="img-fluid" />
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
                                <h2>Expedita voluptas omnis cupiditate totam eveniet nobis sint iste. Dolores est repellat corrupti reprehenderit.</h2>
                                <p>
                                    Quisquam vel ut sint cum eos hic dolores aperiam. Sed deserunt et. Inventore et et dolor consequatur itaque ut voluptate sed et. Magnam nam ipsum tenetur suscipit voluptatum nam et est corrupti.
                                </p>
                                <div class="text-center text-lg-start">
                                    <a href="#" class="btn-read-more d-inline-flex align-items-center justify-content-center align-self-center">
                                        <span>Read More</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="200">
                            <img src="<?=base_url()?>assets/flexstart/assets/img/about.jpg" class="img-fluid" alt="">
                        </div>

                    </div>
                </div>

            </section><!-- End About Section -->

            <!-- ======= Features Section ======= -->
            <section id="features" class="features">

                <div class="container" data-aos="fade-up">
                    <!-- Feature Icons -->
                    <div class="row feature-icons" data-aos="fade-up">
                        <h3>Ratione mollitia eos ab laudantium rerum beatae quo</h3>

                        <div class="row">

                            <div class="col-xl-4 text-center" data-aos="fade-right" data-aos-delay="100">
                                <img src="<?=base_url()?>assets/flexstart/assets/img/features-3.png" class="img-fluid p-4" alt="">
                            </div>

                            <div class="col-xl-8 d-flex content">
                                <div class="row align-self-center gy-4">

                                    <div class="col-md-6 icon-box" data-aos="fade-up">
                                        <i class="ri-line-chart-line"></i>
                                        <div>
                                            <h4>Corporis voluptates sit</h4>
                                            <p>Consequuntur sunt aut quasi enim aliquam quae harum pariatur laboris nisi ut aliquip</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="100">
                                        <i class="ri-stack-line"></i>
                                        <div>
                                            <h4>Ullamco laboris nisi</h4>
                                            <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="200">
                                        <i class="ri-brush-4-line"></i>
                                        <div>
                                            <h4>Labore consequatur</h4>
                                            <p>Aut suscipit aut cum nemo deleniti aut omnis. Doloribus ut maiores omnis facere</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="300">
                                        <i class="ri-magic-line"></i>
                                        <div>
                                            <h4>Beatae veritatis</h4>
                                            <p>Expedita veritatis consequuntur nihil tempore laudantium vitae denat pacta</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="400">
                                        <i class="ri-command-line"></i>
                                        <div>
                                            <h4>Molestiae dolor</h4>
                                            <p>Et fuga et deserunt et enim. Dolorem architecto ratione tensa raptor marte</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="500">
                                        <i class="ri-radar-line"></i>
                                        <div>
                                            <h4>Explicabo consectetur</h4>
                                            <p>Est autem dicta beatae suscipit. Sint veritatis et sit quasi ab aut inventore</p>
                                        </div>
                                    </div>

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
                            <h2>Paket</h2>
                            <p>Cek Paket Kami</p>
                        </header>
                        <div class="row gy-4 d-flex justify-content-center" data-aos="fade-left">
                            <?php foreach($listPaket as $index => $paket){ ?>
                                <?php
                                    $code   =   $paket['code'];

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
                                        <img src="<?=base_url('assets/flexstart/assets/img/pricing-free.png')?>" 
                                            class="img-fluid" alt="<?=$namaPaket?>" />
                                        <?=$keteranganPaket?>
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

            <!-- ======= Recent Blog Posts Section ======= -->
            <section id="lokerPremium" class="loker-premium recent-blog-posts">

                <div class="container" data-aos="fade-up">

                    <header class="section-header">
                        <h2>Loker Premium</h2>
                        <p>Recent posts form our Loker Premium</p>
                    </header>

                    <div class="row">

                        <div class="col-lg-4">
                            <div class="post-box">
                                <div class="post-img"><img src="<?=base_url()?>assets/flexstart/assets/img/blog/blog-1.jpg" class="img-fluid" alt=""></div>
                                <span class="post-date">Tue, September 15</span>
                                <h3 class="post-title">Eum ad dolor et. Autem aut fugiat debitis voluptatem consequuntur sit</h3>
                                <a href="blog-single.html" class="readmore stretched-link mt-auto"><span>Read More</span><i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="post-box">
                                <div class="post-img"><img src="<?=base_url()?>assets/flexstart/assets/img/blog/blog-2.jpg" class="img-fluid" alt=""></div>
                                <span class="post-date">Fri, August 28</span>
                                <h3 class="post-title">Et repellendus molestiae qui est sed omnis voluptates magnam</h3>
                                <a href="blog-single.html" class="readmore stretched-link mt-auto"><span>Read More</span><i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="post-box">
                                <div class="post-img"><img src="<?=base_url()?>assets/flexstart/assets/img/blog/blog-3.jpg" class="img-fluid" alt=""></div>
                                <span class="post-date">Mon, July 11</span>
                                <h3 class="post-title">Quia assumenda est et veritatis aut quae</h3>
                                <a href="blog-single.html" class="readmore stretched-link mt-auto"><span>Read More</span><i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>

                    </div>

                </div>

            </section><!-- End Recent Blog Posts Section -->

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