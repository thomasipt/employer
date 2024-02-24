<?php
    $request                =   request();
    $loggedInDetailMitra    =   (property_exists($request, 'mitra'))? $request->mitra : null;
    $loggedInIDMitra        =   (!empty($loggedInDetailMitra))? $loggedInDetailMitra['id'] : null;

    $mitraToken     =   null;
    if(isset($data)){
        if(array_key_exists('mitraToken', $data)){
            $mitraToken =   $data['mitraToken'];
        } 
    }
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class='mb-0'>Lowongan Pekerjaan</h5>
                        <span class="text-sm text-muted">List Lowongan Pekerjaan</span>
                    </div>
                    <div class="col text-right">
                        <?php if(!empty($loggedInIDMitra)){ ?>
                            <a href="<?=site_url(mitraController('loker/add'))?>">
                                <button class="btn btn-success">
                                    Loker Baru
                                </button>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id='tabelListLoker' class='table table-sm'>
                        <thead>
                            <tr>
                                <th class='text-center' width='75'>No.</th>
                                <th style='max-width: 600px;'>Loker</th>
                                <th>PIC</th>
                                <th>Pelamar</th>
                                <th class='text-center'>Act</th>
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
<script src='<?= base_url(assetsFolder('plugins/numeral/numeral.js')) ?>'></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.css'))?>" />

<script src='<?=base_url(assetsFolder('custom/js/custom-alert.js'))?>'></script>
<script src='<?=base_url(assetsFolder('custom/js/date-converter.js'))?>'></script>

<script src='<?= base_url(assetsFolder('plugins/datatables/jquery.dataTables.min.js')) ?>'></script>
<script src='<?= base_url(assetsFolder('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')) ?>'></script>
<link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')) ?>" />
<script language='Javascript'>
    let _tabelListLokerEl   =   $('#tabelListLoker');
    let _getListLoker         =   `<?=site_url(mitraController('loker/get-list-loker'))?>`;
    
    let _loggedInIDMitra    =   `<?=(!empty($loggedInIDMitra))? $loggedInIDMitra : ''?>`;
    _loggedInIDMitra        =   (_loggedInIDMitra != '')? _loggedInIDMitra : null;

    let _mitraToken         =   `<?=$mitraToken?>`;

    let _tableLokerOptions = {
        processing: true,
        serverSide: true,
        ajax: {
            url     :   _getListLoker,
            dataSrc :   'listLoker',
            headers :   {
                "Authorization" : `Bearer ${_mitraToken}`
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
                    let _judul      =   data.judul;
                    let _createdAt  =   data.createdAt; 

                    let _batasAwalPendaftaran   =   data.batasAwalPendaftaran;
                    let _batasAkhirPendaftaran  =   data.batasAkhirPendaftaran;

                    let _mitra      =   data.mitra;
                    let _namaMitra  =   _mitra.nama;

                    return `<h6 class='mb-1'>${_judul}</h6>
                            <p class='text-sm mb-1'>Oleh <b>${_namaMitra}</b> pada <b>${convertDateTime(_createdAt)}</b></p>
                            <p class='text-sm mb-2'>
                                Periode <span class='badge badge-success'>${convertDateTime(_batasAwalPendaftaran)}</span> s/d 
                                <span class='badge badge-warning'>${convertDateTime(_batasAkhirPendaftaran)}</span>
                            </p>`;
                }
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    let _namaPIC    =   data.namaPIC;
                    let _emailPIC   =   data.emailPIC;

                    return `<h6 class='mb-1'>${_namaPIC}</h6>
                            <p class='text-sm text-muted mb-1'>
                                <a href='mailto:${_emailPIC}'>${_emailPIC}</a>
                            </p>`;
                }
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    let _id             =   data.id;
                    let _more           =   data.more;
                    let _jumlahPelamar  =   _more.jumlahPelamar;

                    let _url    =   `<?=site_url((!empty($mitraToken))? adminController('loker/applier') : mitraController('loker/applier'))?>/${_id}`;

                    return `<a href='${_url}'>
                                <div class='text-left cp'>
                                    <span class='far fa-user mr-1 text-muted'></span> 
                                    <b class='text-success'>${numeral(_jumlahPelamar).format('0,0')}</b>
                                </div>
                            </a>`;
                }
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    let _id =   data.id;

                    if(_loggedInIDMitra == null){
                        return  `<div class='text-center'>-</div>`;
                    }

                    return `<div class='text-center'>
                                <a href='<?=site_url(mitraController('loker/edit'))?>/${_id}'>
                                    <span class='fa fa-pen text-warning cp mr-1' title='Edit Loker'></span>
                                </a>
                                <span class='fa fa-trash text-danger cp ml-1' title='Hapus Loker'
                                    data-id='${_id}'
                                    onClick='_delete(this)'></span>
                            </div>`;
                }
            }
        ]
    };
    let _tabelListLoker = _tabelListLokerEl.DataTable(_tableLokerOptions);
    
    async function _delete(thisContext){
        let _el     =   $(thisContext);

        let _title  =   `Konfirmasi Hapus Loker`;
        let _desc   =   `Apakah yakin <b class='text-danger'>menghapus</b> lowongan pekerjaan ini?`;

        let _id         =   _el.data('id');
        let _konfirmasi =   await konfirmasi(_title, _desc);
        if(_konfirmasi){
            $.ajax({
                url     :   `<?=site_url(mitraController('loker/delete/'))?>${_id}`,
                type    :   `POST`,
                success :   async (decodedRFS) => {
                    let _status     =   decodedRFS.status;
                    let _message    =   decodedRFS.message;

                    let _swalTitle      =   `Hapus Loker`;
                    let _swalMessage    =   (_message == null)? (_status)? 'Berhasil hapus lowongan pekerjaan!' : 'Gagal hapus lowongan pekerjaan! Silahkan coba lagi!' : _message;
                    let _swalType       =   'error';

                    if(_status){
                        _swalType   =   'success';
                    }
                    
                    await notifikasi(_swalTitle, _swalMessage, _swalType);
                    if(_status){
                        _tabelListLoker.ajax.reload();
                    }
                }
            });
        }
    }
</script>