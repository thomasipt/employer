<?php
    $pageTitle  =   (isset($pageTitle))? $pageTitle : null;

    $headData   =   [
        'pageTitle' =>  $pageTitle
    ];
?>
<!-- ======= Breadcrumbs ======= -->
<section class="breadcrumbs">
    <div class="container">
        <ol>
            <li><a href="<?=site_url()?>">Home</a></li>
            <li><?=$pageTitle?></li>
        </ol>
        <h2><?=$pageTitle?></h2>
    </div>
</section>
<!-- End Breadcrumbs -->