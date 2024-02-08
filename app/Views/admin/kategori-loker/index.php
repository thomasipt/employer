<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class='mb-0'>Kategori Loker</h5>
                        <span class="text-sm text-muted">List Kategori Loker</span>
                    </div>
                    <div class="col text-right">
                        <a href="<?=site_url(adminController('kategori-loker/add'))?>">
                            <button class="btn btn-success">
                                Kategori Loker Baru
                            </button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id='tabelListKategoriLoker' class='table table-sm'>
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

<link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')) ?>" />
<script language='Javascript'>
    let _tabelListKategoriLokerEl  =   $('#tabelListKategoriLoker');
    let _getListKategoriLoker      =   `<?=site_url(adminController('kategori-loker/get-list-kategori-loker'))?>`;

    let _tableListBroadcastOptions = {
        processing: true,
        serverSide: true,
        ajax: {
            url     :   _getListKategoriLoker,
            dataSrc :   'listKategoriLoker'
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
                    let _judul      =   data.nama;

                    return `<h6 class='mb-1'>${_judul}</h6>`;
                }
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    let _id =   data.id;

                    let _editHTML   =   `<a href='<?=site_url(adminController('kategori-loker/edit'))?>/${_id}'>
                                            <span class='fa fa-pen text-warning cp mr-1' title='Edit Data'></span>
                                        </a>`;
                    let _deleteHTML =   `<span class='fa fa-trash text-danger cp ml-1'
                                            title='Hapus Data'
                                            data-id='${_id}'
                                            onClick='_delete(this)'></span>`;

                    let _actionHTML =   `<div class='text-center'>
                                            ${_editHTML}
                                            ${_deleteHTML}
                                        </div>`;
                    return `${_actionHTML}`;
                }
            }
        ]
    };
    let _tabelListKategoriLoker = _tabelListKategoriLokerEl.DataTable(_tableListBroadcastOptions);

    async function _delete(thisContext){
        let _el     =   $(thisContext);

        let _title  =   `Konfirmasi Hapus Kategori Loker`;
        let _desc   =   `Apakah yakin <b class='text-danger'>menghapus</b> Kategori Loker ini?`;

        let _id         =   _el.data('id');
        let _konfirmasi =   await konfirmasi(_title, _desc);
        if(_konfirmasi){
            $.ajax({
                url     :   `<?=site_url(adminController('kategori-loker/delete/'))?>${_id}`,
                type    :   `POST`,
                success :   async (decodedRFS) => {
                    let _status     =   decodedRFS.status;
                    let _message    =   decodedRFS.message;

                    let _swalTitle      =   `Hapus Kategori Loker`;
                    let _swalMessage    =   (_message == null)? (_status)? 'Berhasil hapus Kategori Loker!' : 'Gagal hapus Kategori Loker! Silahkan coba lagi!' : _message;
                    let _swalType       =   'error';

                    if(_status){
                        _swalType   =   'success';
                    }
                    
                    await notifikasi(_swalTitle, _swalMessage, _swalType);
                    if(_status){
                        _tabelListKategoriLoker.ajax.reload();
                    }
                }
            });
        }
    }
</script>