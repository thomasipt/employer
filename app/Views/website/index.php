<?php
    $pageTitle  =   (isset($pageTitle))? $pageTitle : null;

    $headData   =   [
        'pageTitle' =>  $pageTitle
    ];

    $view   =   (isset($view))? $view : null;   
?>
<html lang="en">
    <?=view(websiteComponents('head'), $headData)?>
    <body>
        <?=view(websiteComponents('navbar'))?>
        <main id="main">
            <?=view(websiteComponents('breadcrumbs'), $headData)?>
            <section class="inner-page">
                <div class="container">
                    <?php if(!empty($view)){ ?>
                        <?=view($view)?>
                    <?php } ?>
                </div>
            </section>
        </main>
        <?=view(websiteComponents('footer'))?>
    </body>
</html>