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
    $contactUsElement   =   $data['contactUsElement'];

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
        </br></br>

        <!-- ======= Hero Section ======= -->
        <section id="hero" class="hero d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 d-flex flex-column justify-content-center">
                        <h1 data-aos="fade-up"><?=$heroElement['_title']?></h1>
                        <h4 data-aos="fade-up" data-aos-delay="400"><?=$heroElement['_description']?></h4>
                        <div data-aos="fade-up" data-aos-delay="600">
                          <div class="container">
                              <div class="row">
                                  <div class="col-md-5">
                                      <div class="text-center text-lg-start">
                                          <a href="<?=site_url(websiteController('registrasi'))?>" class="btn-get-started scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                                              <span>Registrasi</span>
                                              <i class="bi bi-arrow-right"></i>
                                          </a>
                                      </div>
                                  </div>
                                  <div class="col-md-7">
                                      <div class="text-center text-lg-start">
                                          <a href="https://play.google.com/store/apps/details?id=com.kubu.id&pli=1" class="btn-get-started scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                                              <span>KUBU ID Playstore</span>
                                              <i class="bi bi-arrow-right"></i>
                                          </a>
                                      </div>
                                  </div>
                              </div>
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

            <!-- ======= Daftar Loker ======= -->
            <section id="lokerPremium" class="loker-premium recent-blog-posts">
                <div class="container" data-aos="fade-up">
                    <header class="section-header">
                        <h2>Loker Premium</h2>
                        <p>Postingan Loker Premium Terbaru</p>
                      </br>
                      <div class="col text-center">
                          <a href="<?=site_url(websiteController('loker-premium'))?>">
                              <button class="btn btn-primary">Loker premium Anda juga tersedia di Aplikasi Kubu.id </button>
                          </a>
                      </div>
                    </header>
                        <div class="row">
                            <?php if(count($listLokerPremium) >= 1){ ?>
                                <?php foreach($listLokerPremium as $lokerPremium){ ?>
                                    <?php
                                        $idLoker                =   $lokerPremium['id'];
                                        $judulLoker             =   $lokerPremium['judul'];
                                        $deskripsiLoker         =   $lokerPremium['deskripsi'];
                                        $tanggalPostingLoker    =   $lokerPremium['createdAt'];

                                        $namaPerusahaan         =   $lokerPremium['namaPerusahaan'];
                                    ?>
                                    <div class="col-lg-4">
                                        <div class="post-box col">
                                            <h3 class="post-title"><?=$judulLoker?> <span class='badge bg-info' style='font-size: .873rem;'><?=$namaPerusahaan?></span></h3>
                                            <?php if(!empty($deskripsiLoker)){ ?>
                                                <p class="text-sm text-muted"><?=$deskripsiLoker?></p>
                                            <?php } ?>
                                            <br />
                                            Diposting pada <?=formattedDate($tanggalPostingLoker)?>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                        <?php if(count($listLokerPremium) >= 1){ ?>
                            <div class="row">
                                <div class="col text-center mt-1">
                                    <a href="<?=site_url(websiteController('loker-premium'))?>">
                                        <button class="btn btn-primary">Lihat Loker Premium Lainnya</button>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
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
                                <div class="col-lg-3">
                                    <div class="post-box col">
                                        <h3 class="post-title" style='font-size: 14px;'><?=$judulLoker?></h3>
                                        <?php if(!empty($deskripsiLoker)){ ?>
                                            <p class="text-sm text-muted"><?=$deskripsiLoker?></p>
                                        <?php } ?>
                                        <p class='mb-0' style='font-size:12px;'>Diposting pada <?=formattedDate($tanggalPostingLoker)?></p>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <?php if(count($listLokerFree) >= 1){ ?>
                        <div class="row">
                            <div class="col text-center mt-1">
                                <a href="<?=site_url(websiteController('loker-free'))?>">
                                    <button class="btn btn-primary">Lihat Loker Free Lainnya</button>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </section>
            <!-- End Daftar Loker -->

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





            <!-- ======= Contact Section ======= -->
            <section id="contact" class="contact">
                <div class="container" data-aos="fade-up">
                    <header class="section-header">
                        <h2><?=$contactUsElement['_title']?></h2>
                        <p><?=$contactUsElement['_subTitle']?></p>
                    </header>
                    <div class="row gy-4">
                        <div class="col-lg-6">
                            <div class="row gy-0">
                                <?php
                                    $contactUs      =   $contactUsElement['_contact'];
                                    $listContact    =   json_decode($contactUs, true);
                                ?>
                                <?php foreach($listContact as $index => $contact){ ?>
                                    <?php
                                        $delay                  =   $index * 100;
                                        $contactIcon            =   $contact['icon'];
                                        $contactTitle           =   $contact['title'];
                                        $contactDescription     =   $contact['description'];
                                    ?>
                                    <div class="col-md-6 pt-0" style='background-color: #fafbff;'>
                                        <div class="info-box">
                                            <i class="<?=$contactIcon?>"></i>
                                            <h3 class='pt-3'><?=$contactTitle?></h3>
                                            <p><?=$contactDescription?></p>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                        </div>
                        <div class="col-lg-6">
                            <form action="<?=site_url(websiteController('contact-us'))?>" method="post" class="php-email-form"
                                id='formContactUs'>
                                <div class="row gy-4">
                                    <div class="col-md-6">
                                        <input type="text" name="nama" class="form-control"
                                            placeholder="Nama Lengkap Anda" required>
                                    </div>
                                    <div class="col-md-6 ">
                                        <input type="email" class="form-control" name="email"
                                            placeholder="Email Anda" required>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="subject"
                                            placeholder="Subject" required>
                                    </div>
                                    <div class="col-md-12">
                                        <textarea class="form-control" name="message"
                                            placeholder="Pesan yang akan disampaikan" required></textarea>
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <button type="submit" id='btnSubmit'>Send Message</button>
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
<script src='<?= base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.js')) ?>'></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.css'))?>" />

<script src='<?=base_url(assetsFolder('custom/js/custom-alert.js'))?>'></script>
<script src='<?=base_url(assetsFolder('custom/js/form-submission.js'))?>'></script>
<script language='Javascript'>
    let _formContactUs  =   $('#formContactUs');

    _formContactUs.on('submit', async function(e){
        e.preventDefault();
        await submitForm(this, async (decodedRFS) => {
            let _status     =   decodedRFS.status;
            let _message    =   decodedRFS.message;

            let _title  =   'Contact Us';
            let _type   =   (_status)? 'success' : 'error';

            await notifikasi(_title, _message, _type);
            if(_status){
                location.reload();
            }
        })
    });
</script>
