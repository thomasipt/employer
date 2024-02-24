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
?>
<!-- ======= Footer ======= -->
<footer id="footer" class="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-5 col-md-12 footer-info">
                    <a href="index.html" class="logo d-flex align-items-center">
                        <img src="<?=base_url(assetsFolder('img/icon.png'))?>" alt="Employer" />
                        <span>Employer</span>
                    </a>
                    <p>Cras fermentum odio eu feugiat lide par naso tierra. Justo eget nada terra videa magna derita valies darta donna mare fermentum iaculis eu non diam phasellus.</p>
                    <div class="social-links mt-3">
                        <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-6 footer-links">
                    <h4>Useful Links</h4>
                    <ul>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Home</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">About us</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Terms of service</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Kebijakan Privasi</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-6 footer-links">
                    <h4>Our Services</h4>
                    <ul>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Web Design</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Web Development</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Product Management</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Marketing</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Graphic Design</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
                    <h4>Contact Us</h4>
                    <p>
                        A108 Adam Street <br>
                        New York, NY 535022<br>
                        United States <br><br>
                        <strong>Phone:</strong> +1 5589 55488 55<br>
                        <strong>Email:</strong> info@example.com<br>
                    </p>

                </div>

            </div>
        </div>
    </div>

    <div class="container">
        <div class="copyright">
            &copy; Copyright <strong><span>FlexStart</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/flexstart-bootstrap-startup-template/ -->
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
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