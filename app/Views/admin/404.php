<!DOCTYPE html>
<html lang="en">
    <?=view(adminComponents('head'))?>
    <body>
        <div class="error-wrapper d-flex justify-content-center align-items-center" style='height:100vh;'>
            <div class="content text-center">
                <img src="<?= base_url(assetsFolder('img/icon.png')) ?>" alt="AdminLTELogo" height="125" width="125">
                <h5 class='pt-4 text-danger'>404 Not Found</h5>
                <p class="text-sm text-muted">Sorry, the page you're looking for doesn't exist. Try to check your address.</p>
            </div>
        </div>
    </body>
</html>