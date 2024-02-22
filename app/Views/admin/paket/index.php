<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class='mb-0'>Paket</h5>
                        <span class="text-sm text-muted">List Paket</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id='tabelListPaket' class='table table-sm'>
                        <thead>
                            <tr>
                                <th class='text-center' width='75'>No.</th>
                                <th>Nama</th>
                                <th class='text-center' style='width: 100px;'>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src='<?= base_url(assetsFolder('plugins/datatables/jquery.dataTables.min.js')) ?>'></script>
<script src='<?= base_url(assetsFolder('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')) ?>'></script>

<script src='<?= base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.js')) ?>'></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.css'))?>" />

<script src='<?=base_url(assetsFolder('custom/js/custom-alert.js'))?>'></script>
<script src='<?=base_url(assetsFolder('custom/js/date-converter.js'))?>'></script>

<script src='<?=base_url(assetsFolder('plugins/numeral/numeral.js'))?>'></script>

<link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')) ?>" />
<script language='Javascript'>
    let _tabelListPaketEl  =   $('#tabelListPaket');
    let _getListPaket      =   `<?=site_url(adminController('paket/get-list-paket'))?>`;

    let _tableListBroadcastOptions = {
        processing: true,
        serverSide: true,
        ajax: {
            url     :   _getListPaket,
            dataSrc :   'listPaket'
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
                    let _code           =   data.code;
                    let _nama           =   data.nama;
                    let _durasi         =   data.durasi;
                    let _harga          =   data.harga;
                    let _keterangan     =   data.keterangan;

                    let _hargaHTML      =   `<span class='text-success'><b>Rp. ${numeral(_harga).format('0,0')}</b></span>`;

                    let _keteranganHTML =   `<i>Tanpa Keterangan</i>`;
                    if(_keterangan != null){
                        _keteranganHTML =   _keterangan;
                    }

                    return `<h6 class='mb-1'>${_nama}</h6>
                            <p class='text-sm mb-2'>${_hargaHTML} / <b class='text-primary'>${numeral(_durasi).format('0,0')} Hari</b></p>
                            <p class='text-sm text-muted mb-1'>${_keteranganHTML}</p>`;
                }
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    let _id =   data.id;

                    let _editHTML   =   `<a href='<?=site_url(adminController('paket/edit'))?>/${_id}'>
                                            <span class='fa fa-pen text-warning cp mr-1' title='Edit Data'></span>
                                        </a>`;

                    let _actionHTML =   `<div class='text-center'>
                                            ${_editHTML}
                                        </div>`;
                    return `${_actionHTML}`;
                }
            }
        ]
    };
    let _tabelListPaket = _tabelListPaketEl.DataTable(_tableListBroadcastOptions);
</script>