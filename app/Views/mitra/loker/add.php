<?php
    $detailLoker    =   $data['detailLoker'];    
    $detailMitra    =   $data['detailMitra'];
    $listKategori   =   $data['listKategori'];
    $listProvinsi   =   $data['listProvinsi'];
    $listJenis      =   $data['listJenis'];

    $dL             =   $detailLoker;
    $dM             =   $detailMitra;

    $namaMitra      =   $dM['nama'];
    $fotoMitra      =   $dM['foto'];
    $teleponMitra   =   $dM['telepon'];
    $alamatMitra    =   $dM['alamat'];

    $doesUpdate     =   !empty($dL);

    $actionURL  =   site_url(mitraController('loker/save'));
    if($doesUpdate){
        $actionURL  =   site_url(mitraController('loker/save/'.$dL['id']));
    }
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class='mb-0'>Lowongan Pekerjaan</h5>
                        <span class="text-sm text-muted"><?=(!empty($pageDesc))? $pageDesc : 'Lowongan Pekerjaan Baru' ?></span>
                    </div>
                    <a href="<?=site_url(mitraController('loker'))?>">
                        <button class="btn btn-primary">List Loker</button>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <h4>Perusahaan</h4>
                        <br />
                        <img src='<?=base_url(uploadGambarMitra($fotoMitra))?>' class='img-circle d-block m-auto' alt='<?=$namaMitra?>'
                            style='width:150px; height:150px; object-fit:cover;' />
                        <br />
                        <h5 class='mb-3 text-center'><b><?=$namaMitra?></b></h5>
                        <p class="text-sm mb-1 text-center">
                            <?=$alamatMitra?>
                        </p>
                        <p class="text-sm mb-1 text-center">
                            <?=$teleponMitra?>
                        </p>
                    </div>
                    <div class="col-lg-8">
                        <form id="formLoker" action='<?=$actionURL?>' method='post'
                            data-id-loker='<?=(!empty($dL))? $dL['id'] : '' ?>'>
                            <div class="col col-sm-12 col-xs-12">
                                <h4>Form Loker</h4>
                                <br />
                                <div class="form-group">
                                    <label for="judul">Judul</label>
                                    <input type='text' class='form-control' placeholder='Misal : Sekretaris Rektor Universitas Indonesia'
                                        name='judul' id='judul' value='<?=(!empty($dL))? $dL['judul'] : '' ?>' required />
                                </div>
                                <div class="form-group">
                                    <label for="kategori">Kategori</label>
                                    <select class='form-control' name='kategori' id='kategori' required>
                                        <option value=''>-- Pilih Kategori --</option>
                                        <?php 
                                            $kategoriLoker  =   ($doesUpdate)? $dL['kategori'] : null;
                                            foreach($listKategori as $kategori){
                                                $idKategori     =   $kategori['id'];
                                                $namaKategori   =   $kategori['nama'];

                                                $isSelected =   $kategoriLoker == $idKategori;
                                                $selected   =   ($isSelected)? 'selected' : '';
                                                ?>
                                                    <option value="<?=$idKategori?>" <?=$selected?>><?=$namaKategori?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="namaPIC">Nama PIC</label>
                                    <input type='text' class='form-control' placeholder='Nama Lengkap Penanggung Jawab'
                                        name='namaPIC' id='namaPIC' value='<?=(!empty($dL))? $dL['namaPIC'] : '' ?>' required />
                                </div>
                                <div class="form-group">
                                    <label for="emailPIC">Email PIC</label>
                                    <input type='text' class='form-control' placeholder='Email Penanggung Jawab'
                                        name='emailPIC' id='emailPIC' value='<?=(!empty($dL))? $dL['emailPIC'] : '' ?>' required />
                                </div>
                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi Pekerjaan</label>
                                    <textarea class='form-control' placeholder='Deskripsi pekerjaan secara detail dan lengkap'
                                        name='deskripsi' id='deskripsi'><?=(!empty($dL))? $dL['deskripsi'] : '' ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="kualifikasi">Kualifikasi Pekerjaan</label>
                                    <textarea class='form-control' placeholder='Kualifikasi pekerjaan secara detail dan lengkap'
                                        name='kualifikasi' id='kualifikasi'><?=(!empty($dL))? $dL['kualifikasi'] : '' ?></textarea>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="provinsi">Provinsi</label>
                                            <select class='form-control hirarki' name='provinsi' id='provinsi'
                                                data-tingkat='1'
                                                data-url='<?=base_url('ajax/get-provinsi')?>'
                                                data-data-src='listProvinsi'
                                                required>
                                                <option value=''>-- Pilih Provinsi --</option>
                                                <?php
                                                    $provinsiLoker  =   ($doesUpdate)? $dL['provinsi'] : null;
                                                    foreach($listProvinsi as $provinsi){
                                                        $idProvinsi     =   $provinsi['id'];
                                                        $namaProvinsi   =   $provinsi['nama'];

                                                        $isSelected =   $provinsiLoker == $idProvinsi;
                                                        $selected   =   ($isSelected)? 'selected' : '';
                                                        ?>
                                                            <option value="<?=$idProvinsi?>" <?=$selected?>><?=$namaProvinsi?></option>
                                                        <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="kota">Kota/Kabupaten</label>
                                            <select class='form-control hirarki' name='kota' id='kota'
                                                data-tingkat='2'
                                                data-url='<?=base_url('ajax/get-kota-per-provinsi')?>'
                                                data-data-src='listKota'
                                                data-default-text='Pilih Kota/Kabupaten'
                                                data-option-value-src='id'
                                                data-option-text-src='nama'
                                                required>
                                                <option value=''>-- Pilih Kota/Kabupaten --</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="rentangGaji">Gaji</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder='Gaji Minimum'
                                            data-type='currency' pattern="^\d{1,3}(,\d{3})*(\.\d+)?$"
                                            id='gajiMinimum' name='gajiMinimum'
                                            value='<?=($doesUpdate)? $dL['gajiMinimum'] : 0?>'
                                            onKeyup='formatCurrency(this)'
                                            required />
                                        <input type="text" class="form-control" placeholder='Gaji Maximum'
                                            data-type='currency' pattern="^\d{1,3}(,\d{3})*(\.\d+)?$"
                                            id='gajiMaximum' name='gajiMaximum'
                                            value='<?=($doesUpdate)? $dL['gajiMaximum'] : 0?>'
                                            onKeyup='formatCurrency(this)'
                                            required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="batasWaktu">Batas Waktu Melamar</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" placeholder='Batas Awal'
                                            id='batasAwalPendaftaran' name='batasAwalPendaftaran'
                                            value='<?=($doesUpdate)? date('Y-m-d', strtotime($dL['batasAwalPendaftaran'])) : ""?>' required />
                                        <input type="date" class="form-control" placeholder='Batas Akhir'
                                            id='batasAkhirPendaftaran' name='batasAkhirPendaftaran'
                                            value='<?=($doesUpdate)? date('Y-m-d', strtotime($dL['batasAkhirPendaftaran'])) : ""?>' required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="jenis">Jenis Pekerjaan</label>
                                    <select class='form-control' name='jenis' id='jenis' required>
                                        <option value=''>-- Pilih Jenis Pekerjaan --</option>
                                        <?php
                                            $jenisLoker     =   ($doesUpdate)? $dL['jenis'] : null;
                                            foreach($listJenis as $jenis){
                                                $idJenis    =   $jenis['id'];
                                                $namaJenis  =   $jenis['nama'];

                                                $isSelected =   $jenisLoker == $idJenis;
                                                $selected   =   ($isSelected)? 'selected' : '';
                                                ?>
                                                    <option value="<?=$idJenis?>" <?=$selected?>><?=$namaJenis?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="benefit">Benefit Pekerjaan</label>
                                    <textarea class='form-control' placeholder='Benefit pekerjaan secara detail dan lengkap'
                                        name='benefit' id='benefit'><?=($doesUpdate)? $dL['benefit'] : '' ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="benefit">Keterangan (Opsional)</label>
                                    <textarea class='form-control' placeholder='Opsional, informasi lebih lanjut tentang pekerjaan'
                                        name='keterangan' id='keterangan'><?=($doesUpdate)? $dL['keterangan'] : '' ?></textarea>
                                </div>
                                <hr class='mb-4' />
                                <button class="btn btn-success" type='submit' id='btnSubmit'>Simpan <?=($doesUpdate)? 'Perubahan' : ''?></button>
                                <a href="<?=site_url(mitraController('loker'))?>">
                                    <button class="btn btn-default" type='button'>Back to List Loker</button>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src='<?=base_url(assetsFolder('plugins/jquery/jquery.min.js'))?>'></script>
<script src='<?= base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.js')) ?>'></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.css'))?>" />

<script src='<?=base_url(assetsFolder('custom/js/custom-alert.js'))?>'></script>
<script src='<?=base_url(assetsFolder('custom/js/form-submission.js'))?>'></script>
<script src='<?=base_url(assetsFolder('custom/js/custom-select-box.js'))?>'></script>

<script src='<?=base_url(assetsFolder('plugins/select2/js/select2.min.js'))?>'></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/select2/css/select2.min.css'))?>" />
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css'))?>" />

<script src='<?=base_url(assetsFolder('plugins/ckeditor5-build-classic/ckeditor.js'))?>'></script>

<script language='Javascript'>
    let _formLoker  =   $('#formLoker');
    let _kota       =   $('#kota');

    ClassicEditor
		.create(document.querySelector('#deskripsi'), {})
		.then(editor => {
			window.editor = editor;
		});
    ClassicEditor
		.create(document.querySelector('#kualifikasi'), {});
    ClassicEditor
		.create(document.querySelector('#benefit'), {});
    ClassicEditor
		.create(document.querySelector('#keterangan'), {});

    $('#kategori, #jenis, #provinsi, #kota').select2({
        theme: 'bootstrap4'
    });

    _formLoker.on('submit', async function(e){
        e.preventDefault();

        await submitForm(this, async (decodedRFS) => {
            let _status     =   decodedRFS.status;
            let _message    =   decodedRFS.message;

            let _idloker    =   _formLoker.data('idLoker');   
            let _isUpdate   =   (_idloker != '');

            let _title  =   (_isUpdate)? 'Update Loker' : 'Loker Baru';
            let _type   =   (_status)? 'success' : 'error';

            await notifikasi(_title, _message, _type);
            if(_status){
                location.href   =   `<?=site_url(mitraController('loker'))?>`;
            }
        })
    });

    $('.hirarki').on('change', function(){
        hirarki(this, (dataFromCallback) => { 
            let _isNotEmpty    =   dataFromCallback.length >= 1;
            _kota.prop('disabled', !_isNotEmpty)
        });
    });
</script>
<?php if($doesUpdate){ ?>
    <script language='Javascript'>
        let _kotaLoker  =   `<?=$dL['kota']?>`;

        hirarki('#provinsi');
        let _listKota   =   _kota.children();
        if(!_listKota.empty){
            _kota.prop('disabled', false);

            _listKota.each((index, optionElement) => {
                let _optionValue    =   optionElement.value;
                if(_optionValue == _kotaLoker){
                    optionElement.setAttribute('selected', true);
                }
            });
        }else{
            _kota.prop('disabled', true);
        }
    </script>
<?php } ?>