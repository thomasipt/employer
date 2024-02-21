<?php
    namespace App\Controllers\admin;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\Administrator;
use App\Models\Kandidat;
use App\Models\Loker;
use App\Models\Mitra;
use Dompdf\Options;

    class Home extends BaseController{
        use ResponseTrait;
        public function index(){
            $mitra      =   new Mitra();
            $loker      =   new Loker();
            $kandidat   =   new Kandidat();

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

            $jumlahMitraButuhApprovementOptions     =   $options;
            $jumlahMitraButuhApprovementOptions['where']    =   [
                'approvement'   =>  null
            ];
            $getJumlahMitraButuhApprovement     =   $mitra->getMitra(null, $jumlahMitraButuhApprovementOptions);
            $jumlahMitraButuhApprovement        =   (!empty($getJumlahMitraButuhApprovement))? $getJumlahMitraButuhApprovement['jumlahData'] : 0;

            $lokerOptions       =   $options;
            $getJumlahLoker     =   $loker->getLoker(null, $lokerOptions);
            $jumlahLoker        =   !empty($getJumlahLoker)? $getJumlahLoker['jumlahData'] : 0;

            $kandidatOptions    =   $options;
            $getJumlahKandidat  =   $kandidat->getKandidat(null, $kandidatOptions);
            $jumlahKandidat     =   !empty($getJumlahKandidat)? $getJumlahKandidat['jumlahData'] : 0;

            $data   =   [
                'data'  =>  [
                    'jumlahMitra'       =>  $jumlahMitra,
                    'jumlahLoker'       =>  $jumlahLoker,
                    'jumlahKandidat'    =>  $jumlahKandidat,
                    'jumlahMitraButuhApprovement'   =>  $jumlahMitraButuhApprovement
                ]
            ];
           return view(adminView('home'), $data);
        }
    }
?>