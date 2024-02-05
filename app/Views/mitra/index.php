<?php
    $pageTitle  =   (isset($pageTitle))? $pageTitle : null;

    $headData   =   [
        'pageTitle' =>  $pageTitle
    ];

    $view   =   (isset($view))? $view : null;
?>
<!DOCTYPE html>
<html lang="en">
    <?=view(mitraComponents('head'), $headData)?>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?=view(mitraComponents('navbar'))?>
            <?=view(mitraComponents('sidebar'))?>

            <div class="content-wrapper">
                <?=view(mitraComponents('page-header'))?>
                <section class="content">
                    <div class="container-fluid">
                        <?php if(!empty($view)){ ?>
                            <?=view($view)?>
                        <?php } ?>
                    </div>
                </section>
            </div>

            <?=view(mitraComponents('footer'))?>
        </div>
    </body>
</html>
<script language='Javascript'>
    let _logoURL   =   `<?=base_url('assets/img/icon.png')?>`;
    function _handleErrorImage(imageElement){
        let _el =   $(imageElement);
        _el.attr('src', _logoURL);
    }
</script>