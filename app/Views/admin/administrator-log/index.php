<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class='mb-0'>List Log Administrator</h5>
                        <span class="text-sm text-muted">History Log</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id='tabelListLogAdministrator' class='table table-sm'>
                        <thead>
                            <tr>
                                <th class='text-center' width='75'>No.</th>
                                <th>Log</th>
                                <th>Keterangan</th>
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

<script src='<?=base_url(assetsFolder('custom/js/date-converter.js'))?>'></script>

<link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')) ?>" />
<script language='Javascript'>
    let _tabelListLogAdministratorEl    =   $('#tabelListLogAdministrator');
    let _getListLog                     =   `<?=site_url(adminController('log/get-list-log'))?>`;

    let _tableListBroadcastOptions = {
        processing: true,
        serverSide: true,
        ajax: {
            url     :   _getListLog,
            dataSrc :   'listLog'
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
                    let _judul      =   data.title;
                    let _module     =   data.module;
                    let _createdBy  =   data.createdBy;
                    let _createdAt  =   data.createdAt;   

                    let _administrator      =   data.administrator;
                    let _namaAdministrator  =   _administrator.nama;

                    return `<h6 class='mb-1'>${_judul}</h6>
                            <p class='text-sm text-muted mb-1'>Module <b>${_module}</b></p>
                            <span class='text-sm'>Oleh <b>${_namaAdministrator}</b> pada <b>${convertDateTime(_createdAt)}</b></span>`;
                }
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    let _keterangan =   data.keterangan;

                    let _keteranganHTML =   `<i class='text-muted'>Tanpa Keterangan</i>`;
                    if(_keterangan != null){
                        _keteranganHTML =   _keterangan;
                    }

                    return `${_keteranganHTML}`;
                }
            }
        ]
    };
    let _tabelListLogAdministrator = _tabelListLogAdministratorEl.DataTable(_tableListBroadcastOptions);
</script>