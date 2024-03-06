<?php
    $request                =   request();
    $loggedInDetailMitra    =   (property_exists($request, 'mitra'))? $request->mitra : null;
    $loggedInIDMitra        =   (!empty($loggedInDetailMitra))? $loggedInDetailMitra['id'] : null;

    $transaksiModel =   model('Transaksi');

    $mitraToken     =   null;
    if(isset($data)){
        if(array_key_exists('mitraToken', $data)){
            $mitraToken =   $data['mitraToken'];
        }
    }
?>
<?php if(!empty($loggedInIDMitra)){ ?>
<div class="modal fade" id="modalUploadBuktiBayar" tabindex="-1" role="dialog" aria-labelledby="modalUploadBuktiBayarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUploadBuktiBayarLabel">Upload Bukti Bayar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pb-4">
                <form id='formUploadBuktiBayar' type='post' action='<?=site_url(mitraController('transaksi/upload-bukti-bayar'))?>'
                    enctype="multipart/form-data">
                    <h6>Nomor Transaksi <b id='nomorTransaksi'></b></h6>
                    <label for='uploadButton' class='w-100'>
                        <input type="text" name='nomorTransaksi' style='display:none;' class='nomorTransaksi' />
                        <input type="file" name="buktiBayar" id='uploadButton' style='display:none;'
                            onChange='_uploadFile(this)'
                            data-preview='#uploadIcon' />
                        <img src='<?=base_url(assetsFolder('img/upload-icon.png'))?>' alt='Upload Icon' class='img-150-150 d-block m-auto'
                            id='uploadIcon' />
                    </label>
                    <p class="text-sm text-muted text-center">Klik icon upload untuk memilih gambar</p>
                    <hr />
                    <button type="submit" id='btnSubmit' class='btn btn-primary btn-block'>
                        Upload Bukti Bayar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class='mb-0'>History Transaksi</h5>
                        <span class="text-sm text-muted">History Transaksi Anda</span>
                    </div>
                    <div class="col text-right">

                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id='tabelHistoryTransaksi' class='table table-sm'>
                        <thead>
                            <tr>
                                <th class='text-center' width='7%'>No.</th>
                                <th class='text-center' width='20%'>Nomor Transaksi</th>
                                <th>Paket</th>
                                <th class='text-center' width='10%'>Status</th>
                                <th class='text-center' width='10%'>Harga</th>
                                <th class='text-center' width='10%'>Total</th>
                                <th class='text-center' width='7%'>Act</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src='<?= base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.js')) ?>'></script>
<script src="<?=base_url(assetsFolder('plugins/numeral/numeral.js'))?>"></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.css'))?>" />

<script src='<?=base_url(assetsFolder('custom/js/custom-alert.js'))?>'></script>
<script src='<?=base_url(assetsFolder('custom/js/date-converter.js'))?>'></script>
<script src='<?=base_url(assetsFolder('custom/js/form-submission.js'))?>'></script>

<script src='<?= base_url(assetsFolder('plugins/datatables/jquery.dataTables.min.js')) ?>'></script>
<script src='<?= base_url(assetsFolder('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')) ?>'></script>
<link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')) ?>" />
<script language='Javascript'>
    let _tabelHistoryTransaksiEl    =   $('#tabelHistoryTransaksi');
    let _modalUploadBuktiBayar      =   $('#modalUploadBuktiBayar');
    let _uploadIcon                 =   $('#uploadIcon');
    let _formUploadBuktiBayar       =   $('#formUploadBuktiBayar');
    let _getListTransaksi           =   `<?=site_url(mitraController('transaksi/get-list-transaksi'))?>`;

    let _imgPreview    =   null;
    let _imgData       =   null;

    let _approvementApproved    =   `<?=$transaksiModel->approvement_approved?>`;
    let _approvementRejected    =   `<?=$transaksiModel->approvement_rejected?>`;

    if(typeof _loggedInIDMitra === 'undefined'){
        var _loggedInIDMitra    =   `<?=(!empty($loggedInIDMitra))? $loggedInIDMitra : ''?>`;
    }
    _loggedInIDMitra        =   (_loggedInIDMitra != '')? _loggedInIDMitra : null;

    let _tabelTransaksiOptions = {
        processing: true,
        serverSide: true,
        ajax: {
            url     :   _getListTransaksi,
            dataSrc :   'listTransaksi',
            headers :   {
                "Authorization" : "Bearer <?=$mitraToken?>"
            }
        },
        columns: [
            {
                data: null,
                render: function(data, type, row, meta) {
                    return `<p class='mb-0 text-center'><b>${data.nomorUrut}.</b></p>`;
                }
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    let _id             =   data.id;
                    let _nomor          =   data.nomor;
                    let _stackedBy      =   data.stackedBy;
                    let _buktiBayar     =   data.buktiBayar;
                    let _berlakuMulai   =   data.berlakuMulai;
                    let _berlakuSampai  =   data.berlakuSampai;

                    let _periodeBerlakuHTML     =   ``;

                    if(_berlakuMulai != null && _berlakuSampai){
                        _periodeBerlakuHTML     =   `<p class='mb-1 text-sm'>
                                                        Periode berlaku <br />
                                                        <span class='text-muted'>
                                                            ${convertDate(_berlakuMulai)} sd ${convertDate(_berlakuSampai)}
                                                        </span>
                                                    </p>`;
                    }

                    let _buktiBayarHMTL         =   ``;
                    if(_buktiBayar == null){
                        _buktiBayarHMTL         =   `<span class='badge badge-info blink'>Belum upload bukti bayar</span>`;
                        if(_loggedInIDMitra != null){
                            _buktiBayarHMTL     =   `${_buktiBayarHMTL}
                                                        <span class='ml-2 fa fa-upload text-info cp'
                                                            data-id='${_id}'
                                                            data-nomor='${_nomor}'
                                                            onClick='_uploadBuktiBayar(this)'></span>`;
                        }
                    }

                    let _stackedByHTML  =   ``;
                    if(_stackedBy != null){
                        let _nomorTransaksiPenimpa  =   _stackedBy.nomor;
                        _stackedByHTML  =   `<div class='mb-1'>
                                                <span class='badge badge-warning mr-1'
                                                    title='Tetimpa oleh transaksi ${_nomorTransaksiPenimpa}'>Tertimpa</span>
                                            </div>`;
                    }

                    let _buktiBayarImgHTML =   '';
                    if(_buktiBayar != null){
                        _buktiBayarImgHTML  =   `<a href='<?=base_url(uploadGambarBuktiBayar())?>/${_buktiBayar}' target='_blank'>
                                                    <span class='text-sm mr-1'>Bukti bayar</span> <span class='fa fa-image'></span>
                                                </a>`;
                    }

                    return `<div class='text-left'>
                                ${_stackedByHTML}
                                <h6 class='mb-1'>${_nomor} <span class='fa fa-copy cp text-muted cp ml-1' style='font-size: 12px;'
                                        onClick='copy("${_nomor}")'></span></h6>
                                ${_periodeBerlakuHTML}
                                ${_buktiBayarHMTL}
                                ${_buktiBayarImgHTML}
                            </div>`;
                }
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    let _paket      =   data.paket;
                    let _createdAt  =   data.createdAt;

                    let _namaPaket          =   _paket.nama;
                    let _keteranganPaket    =   _paket.keterangan;

                    let _keteranganPaketHTML    =   '';
                    if(_keteranganPaket != null){
                        _keteranganPaketHTML    =   `<p class='text-sm text-muted mb-2' style='word-break: break-all !important;'>${_keteranganPaket}</p>`;
                    }

                    return `<h6 class='mb-1'>${_namaPaket}</h6>
                            ${_keteranganPaketHTML}
                            <p class='text-sm mb-1'>Checkout pada <b>${convertDateTime(_createdAt)}</b></p>`;
                }
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    let _status         =   data.approvement;

                    let _statusHTML     =   `<span class='badge badge-info'>Sedang diproses</span>`;
                    if(_status == _approvementApproved){
                        _statusHTML =   `<span class='badge badge-success'>Aktif</span>`;
                    }
                    if(_status == _approvementRejected){
                        let _approvementReason  =   data.approvementReason;
                        _statusHTML =   `<span class='badge badge-danger'>
                                            Tidak Aktif
                                        </span>
                                        <span class='fa fa-info-circle text-danger ml-1' title='${_approvementReason}'></span>`;
                    }

                    return `${_statusHTML}`;
                }
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    let _harga  =   data.harga;
                    let _ppn    =   data.ppn;

                    let _hargaHTML  =   `<div class='text-right'>
                                            <h6 class='text-info mb-0'><b>Rp. ${numeral(_harga).format('0,0')}</b></h6>
                                            <span class='text-sm text-muted'>
                                                Rp. ${numeral(_ppn).format('0,0')} <span class='fa fa-info-circle ml-1' title='PPN'></span>
                                            </span>
                                        </div>`;

                    return `${_hargaHTML}`;
                }
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    let _harga  =   data.harga;
                    let _ppn    =   data.ppn;
                    let _total  =   Number.parseInt(_harga) + Number.parseInt(_ppn);

                    let _totalHTML  =   `<div class='text-right'>
                                            <h6 class='text-success mb-0'><b>Rp. ${numeral(_total).format('0,0')}</b></h6>
                                        </div>`;

                    return `${_totalHTML}`;
                }
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    let _id             =   data.id;
                    let _nomor          =   data.nomor;
                    let _approvement    =   data.approvement;

                    let _invoiceHTML    =   ``;
                    if(_approvement != null){
                        _invoiceHTML    =   `<a href='<?=site_url(mitraController('transaksi/invoice'))?>/${_id}' target='_blank'>
                                                <span class='fa fa-print cp' title='Invoice Transaksi ${_nomor}'></span>
                                            </a>`;
                    }

                    return `<div class='text-center'>
                                ${_invoiceHTML}
                            </div>`;
                }
            }
        ]
    };
    let _tabelHistoryTransaksi = _tabelHistoryTransaksiEl.DataTable(_tabelTransaksiOptions);
</script>
<?php if(!empty($loggedInIDMitra)){ ?>
    <script language='Javascript'>
        async function _uploadBuktiBayar(thisContext){
            let _el     =   $(thisContext);
            let _id     =   _el.data('id');
            let _nomor  =   _el.data('nomor');

            let _nomorTransaksiEl       =   _modalUploadBuktiBayar.find('#nomorTransaksi');
            let _nomorTransaksiFormEl   =   _modalUploadBuktiBayar.find('.nomorTransaksi');

            _nomorTransaksiEl.text(_nomor);
            _nomorTransaksiFormEl.val(_nomor);
            _modalUploadBuktiBayar.modal();
        }

        async function _uploadFile(thisContext){
            await fileHandler(thisContext);
        }

        _formUploadBuktiBayar.on('submit', async function(e){
            e.preventDefault();
            await submitForm(this, async function(responseFromServer){
                let _status     =   responseFromServer.status;
                let _message    =   responseFromServer.message;

                let _swalMessage    =   (_message == null)? (_status)? 'Berhasil!' : 'Gagal!' : _message;
                let _swalType       =   (_status)? 'success' : 'error';

                await notifikasi('Bukti Bayar', _swalMessage, _swalType);
                if(_status){
                    _tabelHistoryTransaksi.ajax.reload();
                    _modalUploadBuktiBayar.modal('hide');
                }
            });
        });
    </script>
<?php } ?>
