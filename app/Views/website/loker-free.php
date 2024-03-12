<?php
$request    =   request();
$search     =   $request->getGet('search');

$listLokerFree      =   $data['listLoker'];
$jumlahLoker        =   $data['jumlahLoker'];
$totalPage          =   $data['totalPage'];
$page               =   $data['page'];
$next               =   $data['next'];
$previous           =   $data['previous'];

$searchQS   =   !empty($search) ? '&search=' . $search : '';
?>
<?php
    function showPagination($totalPages, $currentPage){
        echo '<nav  class="mt-3" aria-label="Page navigation example"><ul class="pagination justify-content-center">';

        if ($totalPages > 7) {
            if ($currentPage == 1) {
                echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
                echo '<li class="page-item active" aria-current="page"><span class="page-link">1</span></li>';
            } else {
                echo '<li class="page-item"><a class="page-link" href="' . getURL($currentPage - 1) . '">Previous</a></li>';
                echo '<li class="page-item"><a class="page-link" href="' . getURL(1) . '">1</a></li>';
            }

            if ($totalPages - $currentPage > 3) {
                if ($currentPage > 4) {
                    echo '<li class="page-item"><span class="page-link">...</span></li>';
                    echo '<li class="page-item"><a class="page-link" href="' . getURL($currentPage - 1) . '">' . ($currentPage - 1) . '</a></li>';
                    echo '<li class="page-item active" aria-current="page"><span class="page-link">' . ($currentPage) . '</span></li>';
                    echo '<li class="page-item"><a class="page-link" href="' . getURL($currentPage + 1) . '">' . ($currentPage + 1) . '</a></li>';
                } else {
                    for ($pageNo = 2; $pageNo <= 5; $pageNo++) {
                        if ($currentPage == $pageNo) {
                            echo '<li class="page-item active" aria-current="page"><span class="page-link">' . ($pageNo) . '</span></li>';
                        } else {
                            echo '<li class="page-item"><a class="page-link" href="' . getURL($pageNo) . '">' . ($pageNo) . '</a></li>';
                        }
                    }
                }
            }

            if ($totalPages - $currentPage < 4) {
                echo '<li class="page-item"><span class="page-link">...</span></li>';
                for ($pageNo = $totalPages - 4; $pageNo <= $totalPages - 1; $pageNo++) {
                    if ($currentPage == $pageNo) {
                        echo '<li class="page-item active" aria-current="page"><span class="page-link">' . ($pageNo) . '</span></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="' . getURL($pageNo) . '">' . ($pageNo) . '</a></li>';
                    }
                }
            } else {
                echo '<li class="page-item"><span class="page-link">...</span></li>';
            }

            if ($currentPage == $totalPages) {
                echo '<li class="page-item active" aria-current="page"><span class="page-link">' . ($totalPages) . '</span></li>';
                echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
            } else {
                echo '<li class="page-item"><a class="page-link" href="' . getURL($totalPages) . '">' . ($totalPages) . '</a></li>';
                echo '<li class="page-item"><a class="page-link" href="' . getURL($currentPage + 1) . '">Next</a></li>';
            }
        } else {
            if ($currentPage == 1) {
                echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
            } else {
                echo '<li class="page-item"><a class="page-link" href="' . getURL($currentPage - 1) . '">Previous</a></li>';
            }
            for ($pageNo = 1; $pageNo <= $totalPages; $pageNo++) {
                if ($currentPage == $pageNo) {
                    echo '<li class="page-item active" aria-current="page"><span class="page-link">' . ($pageNo) . '</span></li>';
                } else {
                    echo '<li class="page-item"><a class="page-link" href="' . getURL($pageNo) . '">' . ($pageNo) . '</a></li>';
                }
            }
            if ($currentPage == $totalPages) {
                echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
            } else {
                echo '<li class="page-item"><a class="page-link" href="' . getURL($currentPage + 1) . '">Next</a></li>';
            }
        }

        echo '</ul></nav>';
    }
    function getURL($pageNo) {
        $actuallink =   "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $parsed_url =   parse_url($actuallink);

        if(isset($parsed_url["query"])) parse_str($parsed_url["query"], $query); else $query = array();

        if($pageNo == 1) {
            unset($query["page"]);
        } else {
            $query["page"]  =    $pageNo;
        }

        $query  =    htmlentities(http_build_query($query));

        return ($query) ? $parsed_url["path"] . "?" . $query : $parsed_url["path"];

      }
?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <form>
                <input type="text" class="form-control form-control-lg" placeholder='Cari loker di sini dengan Job Title, dan Deskripsi' name='search' value='<?= (!empty($search)) ? $search : '' ?>' />
            </form>
        </div>
    </div>
    <br />
    <div class="row">
        <?php if (!empty($search)) { ?>
            <h6 class='mb-4'>Hasil Pencarian <b>"<?= $search ?>"</b></h6>
        <?php } ?>
        <?php if (count($listLokerFree) >= 1) { ?>
            <?php foreach ($listLokerFree as $lokerFree) { ?>
                <?php
                $idLoker                =   $lokerFree['id'];
                $judulLoker             =   $lokerFree['judul'];
                $gajiMinimumLoker       =   $lokerFree['gajiMinimum'];
                $gajiMaximumLoker       =   $lokerFree['gajiMaximum'];
                $tanggalPostingLoker    =   $lokerFree['createdAt'];

                $detailPerusahaan   =   $lokerFree['mitra'];
                $namaPerusahaan     =   $detailPerusahaan['nama'];
                $fotoPerusahaan     =   $detailPerusahaan['cover'];

                $namaKota       =   '-';
                $namaProvinsi   =   '-';

                $detailLokasi       =   $lokerFree['lokasi'];
                if (!empty($detailLokasi)) {
                    $namaKota       =   $detailLokasi['namaKota'];
                    $namaProvinsi   =   $detailLokasi['namaProvinsi'];
                }

                $detailJenisPekerjaan   =   $lokerFree['jenis'];
                $namaJenisPekerjaan     =   !empty($detailJenisPekerjaan) ? $detailJenisPekerjaan['nama'] : '-';
                ?>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 mb-3">
                    <a href="<?= site_url(websiteController('loker-free')) ?>/<?= base64_encode($idLoker) ?>" target='_blank'>
                        <div class="post-box col">
                            <div class="row">
                                <img src="<?= $fotoPerusahaan ?>" alt="<?= $namaPerusahaan ?>" class='img-circle img-perusahaan p-0' />
                                <div class="col">
                                    <h5 class="post-title mb-0 text-black"><?= $judulLoker ?></h5>
                                    <span class='text-sm text-muted'><?= $namaPerusahaan ?></span>
                                </div>
                            </div>
                            <br />
                            <label class='text-black' for="lokasi"><b>Lokasi</b></label>
                            <p class='text-sm text-muted mb-2' id="lokasi"><?= $namaKota ?>, <?= $namaProvinsi ?></p>

                            <label class='text-black' for="gaji"><b>Gaji</b></label>
                            <p class='text-sm text-muted mb-2' id="gaji">Rp. <?= number_format($gajiMinimumLoker) ?> s/d Rp. <?= number_format($gajiMaximumLoker) ?></p>

                            <label class='text-black' for="jenis"><b>Jenis</b></label>
                            <p class='text-sm text-muted mb-2' id="jenis"><?= $namaJenisPekerjaan ?></p>

                            <p class="text-sm mt-3 text-black" style='text-align: right;font-size:12px;'>Diposting pada <?= formattedDate($tanggalPostingLoker) ?></p>
                        </div>
                    </a>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p class="text-sm text-muted text-center">
                <img src="<?= base_url(assetsFolder('img/empty.png')) ?>" class='d-block m-auto mb-4' alt="Empty" style='width: 125px; height: 125px;' />
                <i><?= (!empty($search)) ? 'Hasil pencarian tidak ada' : 'Belum ada Lowongan Pekerjaan Free' ?></i>
            </p>
        <?php } ?>
        <?php if ($totalPage >= 1) { ?>
            <?php showPagination($totalPage, $page); ?>
        <?php } ?>
    </div>
</div>
<script src="<?= base_url(assetsFolder('plugins/jquery/jquery.min.js')) ?>/"></script>
<link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/ionicons/ionicons.min.css')) ?>" />

<script src='<?= base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.js')) ?>'></script>
<link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.css')) ?>" />

<script language='Javascript'>

</script>

<style tyle='text/css'>
    .post-box {
        box-shadow: 0px 0 30px rgba(1, 41, 112, 0.08);
        transition: 0.3s;
        height: 100%;
        overflow: hidden;
        padding: 30px;
        border-radius: 8px;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .post-box:hover {
        transform: scale(1.1);
        box-shadow: 0px 0 30px rgba(1, 41, 112, 0.1);
    }

    .img-perusahaan {
        width: 65px;
        height: 65px;
        object-fit: cover;
        border-radius: 50%;
    }
</style>
