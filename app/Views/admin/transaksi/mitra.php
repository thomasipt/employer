<?php
    $listMitra      =   $data['listMitra'];
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class='mb-0'>History Transaksi</h5>
                        <span class="text-sm text-muted">Mitra Terpilih</span>
                    </div>
                    <div class="col text-right">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id='formListMitra'>
                    <div class="form-group">
                        <label for="mitra">Mitra</label>
                        <select name="mitra" id="mitra" class="form-control">
                            <option value="">-- Pilih Mitra --</option>
                            <?php foreach($listMitra as $mitraItem){ ?>
                                <?php
                                    $idMitra    =   $mitraItem['id'];
                                    $namaMitra  =   $mitraItem['nama'];
                                ?>
                                <option value="<?=$idMitra?>"><?=$namaMitra?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <hr />
                    <button class="btn btn-success" type='submit' id='btnSubmit'>Pilih</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src='<?= base_url(assetsFolder('plugins/datatables/jquery.dataTables.min.js')) ?>'></script>
<script src='<?= base_url(assetsFolder('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')) ?>'></script>
<link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')) ?>" />

<script src="<?=base_url(assetsFolder('plugins/select2/js/select2.min.js'))?>"></script>
<link rel='stylesheet' href="<?=base_url(assetsFolder('plugins/select2/css/select2.min.css'))?>" />
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css'))?>" />

<script language='Javascript'>
    let _formListMitra  =   $('#formListMitra');
    let _mitra          =   $('#mitra');

    _mitra.select2({
        theme: 'bootstrap4'
    });

    _formListMitra.on('submit', async (e) => {
        e.preventDefault();
        let _selectedMitra  =   _mitra.val();
        location.href       =   `<?=site_url(adminController('transaksi/mitra'))?>/${_selectedMitra}`;
    });
</script>
