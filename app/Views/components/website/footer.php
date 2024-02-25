<?php
    $homepageModel          =   model('Homepage');
    $homepageElementModel   =   model('HomepageElement');

    $options                =   [
        'where' =>  [
            'parent'    =>  $homepageModel->whatsappId
        ]
    ];
    $whatsappElement    =   $homepageElementModel->getHomepageElement(null, $options);
    $whatsappElement    =   $homepageElementModel->convertListELementToKeyValueMap($whatsappElement);

    $emailOptions       =   $options;
    $emailOptions['where']['parent']    =   $homepageModel->emailPerusahaanId;
    $emailPerusahaanElement    =   $homepageElementModel->getHomepageElement(null, $emailOptions);
    $emailPerusahaanElement    =   $homepageElementModel->convertListELementToKeyValueMap($emailPerusahaanElement);

    $contactUsOptions       =   $options;
    $contactUsOptions['where']['parent']    =   $homepageModel->contactUsId;
    $contactUsElement    =   $homepageElementModel->getHomepageElement(null, $contactUsOptions);
    $contactUsElement    =   $homepageElementModel->convertListELementToKeyValueMap($contactUsElement);
?>
<!-- ======= Footer ======= -->
<footer id="footer" class="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row gy-4 justify-content-between">
                <div class="col-lg-5 col-md-12 footer-info">
                    <a href="<?=site_url()?>" class="logo d-flex align-items-center">
                        <img src="<?=base_url(assetsFolder('img/icon.png'))?>" alt="Employer" />
                        <span>Employer</span>
                    </a>
                    <div class="social-links mt-3">
                        <a href="https://x.com/Kubu_ID?s=20" class="twitter" target="_blank"><i class="bi bi-twitter"></i></a>
                        <a href="https://facebook.com/kubuindonesia" class="facebook" target='_blank'><i class="bi bi-facebook"></i></a>
                        <a href="https://instagram.com/kubu.app" class="instagram" target="_blank"><i class="bi bi-instagram"></i></a>
                        <a href="https://youtube.com/@aplikasikubu/featured" class="youtube" target='_blank'><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
                    <h4>Contact Us</h4>
                    <?=$contactUsElement['_description']?>
                    <p class='mt-3'>
                        <?php
                            $contact        =   $contactUsElement['_contact'];
                            $listContact    =   json_decode($contact, true);
                        ?>
                        <?php foreach($listContact as $contact){ ?>
                            <?php
                                $contactTitle           =   $contact['title'];
                                $contactDescription     =   $contact['description'];
                            ?>
                            <strong><?=$contactTitle?>:</strong> <?=$contactDescription?><br>
                        <?php } ?>
                    </p>

                </div>

            </div>
        </div>
    </div>
</footer>
<!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
</a>
<a href='https://wa.me/<?=$whatsappElement['_number']?>?text=<?=$whatsappElement['_template']?>' target='_blank' class='chat-whatsapp d-flex align-items-center justify-content-center'>
    <i class="bi bi-whatsapp"></i>
</a>

<!-- Vendor JS Files -->
<script src="<?=base_url(flexStartAssets('vendor/purecounter/purecounter_vanilla.js'))?>"></script>
<script src="<?=base_url(flexStartAssets('vendor/aos/aos.js'))?>"></script>
<script src="<?=base_url(flexStartAssets('vendor/bootstrap/js/bootstrap.bundle.min.js'))?>"></script>
<script src="<?=base_url(flexStartAssets('vendor/glightbox/js/glightbox.min.js'))?>"></script>
<script src="<?=base_url(flexStartAssets('vendor/isotope-layout/isotope.pkgd.min.js'))?>"></script>
<script src="<?=base_url(flexStartAssets('vendor/swiper/swiper-bundle.min.js'))?>"></script>
<script src="<?=base_url(flexStartAssets('vendor/php-email-form/validate.js'))?>"></script>

<!-- Template Main JS File -->
<script src="<?=base_url(flexStartAssets('js/main.js'))?>"></script>
<style type='text/css'>
    .chat-whatsapp{
        position: fixed;
        right: 15px;
        bottom: 65px;
        z-index: 99999;
        background-color: #128C7E;
        color: #fff;
        width: 40px;
        height: 40px;
        border-radius: 4px;
        transition: all 0.4s;
    }
    .btn-login, .btn-registrasi{
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
</style>
