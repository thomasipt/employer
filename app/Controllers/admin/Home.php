<?php
    namespace App\Controllers\admin;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\Kandidat;
    use App\Models\Loker;
    use App\Models\Mitra;
    use App\Models\Transaksi;

    class Home extends BaseController{
        use ResponseTrait;
        public function index(){
            $mitra      =   new Mitra();
            $loker      =   new Loker();
            $kandidat   =   new Kandidat();
            $transaksi  =   new Transaksi();

            $options    =   [
                'singleRow' =>  true,
                'select'    =>  'count(id) as jumlahData'
            ];

            $jumlahMitraOptions     =   $options;
            $jumlahMitraOptions['where']    =   [
                'approvement'   =>  $mitra->approvement_approved
            ];
            $getJumlahMitra         =   $mitra->getMitra(null, $jumlahMitraOptions);
            $jumlahMitra            =   (!empty($getJumlahMitra))? $getJumlahMitra['jumlahData'] : 0;

            $jumlahPembelianPaketOptions     =   $options;
            $jumlahPembelianPaketOptions['where']    =   [
                'approvement'   =>  null
            ];
            $getJumlahPembelianPaket     =   $transaksi->getTransaksi(null, $jumlahPembelianPaketOptions);
            $jumlahPembelianPaket        =   (!empty($getJumlahPembelianPaket))? $getJumlahPembelianPaket['jumlahData'] : 0;

            $lokerOptions       =   $options;
            $getJumlahLoker     =   $loker->getLoker(null, $lokerOptions);
            $jumlahLoker        =   !empty($getJumlahLoker)? $getJumlahLoker['jumlahData'] : 0;

            $kandidatOptions    =   $options;
            $getJumlahKandidat  =   $kandidat->getKandidat(null, $kandidatOptions);
            $jumlahKandidat     =   !empty($getJumlahKandidat)? $getJumlahKandidat['jumlahData'] : 0;

            $data   =   [
                'data'  =>  [
                    'jumlahMitra'           =>  $jumlahMitra,
                    'jumlahLoker'           =>  $jumlahLoker,
                    'jumlahKandidat'        =>  $jumlahKandidat,
                    'jumlahPembelianPaket'  =>  $jumlahPembelianPaket
                ]
            ];
           return view(adminView('home'), $data);
        }
    }
?>