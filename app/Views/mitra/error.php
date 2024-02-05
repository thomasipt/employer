<!DOCTYPE html>
<html lang="en">
    <?=view(mitraComponents('head'))?>
    <body>
        <div class="error-wrapper d-flex justify-content-center align-items-center" style='height:100vh;'>
            <div class="content text-center">
                <img src="<?= base_url(assetsFolder('img/icon.png')) ?>" alt="AdminLTELogo" height="125" width="125">
                <h5 class='pt-4 text-danger'><?=$judul?></h5>
                <p class="text-sm text-muted"><?=$deskripsi?></p>
                <a href="<?=site_url(mitraController())?>">
                    <button class="btn btn-primary">Back to Home</button>
                </a>
            </div>
        </div>
    </body>
</html>