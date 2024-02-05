<?php
    $mitra  =   model('Mitra');
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class='mb-0'>List Mitra</h5>
                        <span class="text-sm text-muted">Daftar Mitra Terdaftar</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id='tabelListMitra' class='table table-sm'>
                        <thead>
                            <tr>
                                <th class='text-center' width='75'>No.</th>
                                <th>Mitra</th>
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
<script src='<?= base_url(assetsFolder('plugins/datatables/jquery.dataTables.min.js')) ?>'></script>
<script src='<?= base_url(assetsFolder('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')) ?>'></script>

<script src='<?= base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.js')) ?>'></script>
<link rel="stylesheet" href="<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.css'))?>" />

<script src='<?=base_url(assetsFolder('custom/js/custom-alert.js'))?>'></script>
<script src='<?=base_url(assetsFolder('custom/js/form-submission.js'))?>'></script>
<script src='<?=base_url(assetsFolder('custom/js/date-converter.js'))?>'></script>
<script src='<?=base_url(assetsFolder('custom/js/approvement-mitra.js'))?>'></script>

<link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')) ?>" />
<script language='Javascript'>
    let _tabelListMitraEl   =   $('#tabelListMitra');
    let _getListMitra       =   `<?=site_url(adminController('mitra/get-list-mitra'))?>`;

    let _approvementApproved    =   `<?=$mitra->approvement_approved?>`;
    let _approvementRejected    =   `<?=$mitra->approvement_rejected?>`;

    let _tableListBroadcastOptions = {
        processing: true,
        serverSide: true,
        ajax: {
            url     :   _getListMitra,
            dataSrc :   'listMitra'
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
                    let _nama           =   data.nama;
                    let _alamat         =   data.alamat;
                    let _foto           =   data.foto;
                    let _approvement    =   data.approvement;   
                    let _approvementAt  =   data.approvementAt;

                    let _approver           =   data.approver;
                    let _administrator      =   _approver.administrator;
                    let _namaAdministrator  =   _administrator.nama;

                    let _approvementHTML   =   `<span class='text-sm'><b class='text-info'>Perlu verifikasi persetujuan/penolakan</b></span>`;
                    if(_approvement == _approvementApproved){
                        _approvementHTML   =   `<span class='text-sm'>
                                                    <span class='badge badge-success mr-2'>Disetujui</span>
                                                    Oleh <b>${_namaAdministrator}</b> pada <b>${convertDateTime(_approvementAt)}</b>
                                                </span>`;
                    }
                    if(_approvement == _approvementRejected){
                        _approvementHTML   =   `<span class='text-sm'>
                                                    <span class='badge badge-danger mr-2'>Ditolak</span>
                                                    Oleh <b>${_namaAdministrator}</b> pada <b>${convertDateTime(_approvementAt)}</b>
                                                </span>`;
                    }

                    return `<div class='row'>
                                <a href='<?=base_url(uploadGambarMitra())?>/${_foto}' target='_blank'>
                                    <img src='<?=base_url(uploadGambarMitra('compress'))?>/${_foto}' alt='${_nama}' class='img-50-50 img-circle'
                                        style='object-fit: cover;' />
                                </a>
                                <div class='col ml-3'>
                                    <h6 class='mb-2'>${_nama}</h6>
                                    <p class='text-sm text-muted mb-0'>${_alamat}</p>
                                    ${_approvementHTML}
                                </div>
                            </div>`;
                }
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    let _id             =   data.id;
                    let _nama           =   data.nama;
                    let _approvement    =   data.approvement;  

                    let _detailHTML     =   `<div class='d-inline-block'>
                                                <a href='<?=site_url(adminController('mitra/detail'))?>/${_id}'>
                                                    <span class='fa fa-arrow-right text-primary cp ml-1' title='Detail Mitra'></span>
                                                </a>
                                            </div>`;
                    let _approveHTML    =   (_approvement == null)? `<div class='text-center d-inline-block'>
                                                <span class='fa fa-check text-success cp mr-1'
                                                    title='Approvement Mitra (Terima)'
                                                    data-id='${_id}'
                                                    data-nama='${_nama}'
                                                    data-url='<?=base_url(adminController('mitra/approvement'))?>/${_id}'
                                                    onClick='_onApprove(this, "${_approvementApproved}", loadTable)'>
                                                </span>
                                                <span class='fa fa-times text-danger cp ml-1'
                                                    title='Approvement Mitra (Tolak)'
                                                    data-id='${_id}'
                                                    data-nama='${_nama}'
                                                    data-url='<?=base_url(adminController('mitra/approvement'))?>/${_id}'
                                                    onClick='_onApprove(this, "${_approvementRejected}", loadTable)'>
                                                </span>
                                            </div>` : '';

                    let _actionHTML =   `<div class='text-center'>
                                            ${_approveHTML}
                                            ${_detailHTML}
                                        </div>`;
                    return `${_actionHTML}`;
                }
            }
        ]
    };
    let _tabelListMitra = _tabelListMitraEl.DataTable(_tableListBroadcastOptions);
    
    function loadTable(){
        _tabelListMitra.ajax.reload();
    }
</script>