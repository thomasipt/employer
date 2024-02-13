<?php
    namespace App\Controllers\mitra;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\Kandidat as KandidatModel;
    
    #Library
    use App\Libraries\PDF;
    use App\Libraries\Tabel;

    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;
    use Dompdf\Options;
    use Psr\Log\LoggerInterface;

    use Exception;

    class Kandidat extends BaseController{
        use ResponseTrait;

        private $loggedInDetailMitra;
        private $loggedInIDMitra;
        public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger){
            parent::initController($request, $response, $logger);
            
            if(property_exists($request, 'mitra')){
                $detailMitraFromFilter  =   $request->mitra;
                $idMitra                =   $detailMitraFromFilter['id'];

                $this->loggedInDetailMitra  =   $detailMitraFromFilter;
                $this->loggedInIDMitra      =   $idMitra;
            }
        }

        private function kandidatChecking($idKandidat, $options = null){
            $kandidat       =   new KandidatModel();

            $detailKandidat =   $kandidat->getKandidat($idKandidat, $options);
            if(empty($detailKandidat)){
                throw new Exception('Kandidat dengan pengenal '.$idKandidat.' tidak ditemukan!');
            }

            return $detailKandidat;
        }

        public function cv($idKandidat){
            try{
                $tabel          =   new Tabel();

                $options        =   [
                    'select'    =>  'pT.*, kota.nama as namaKota',
                    'join'      =>  [
                        ['table' => $tabel->kota.' kota', 'condition' => 'kota.kode=pT.kota']
                    ]
                ];
                $detailKandidat =   $this->kandidatChecking($idKandidat, $options);
                $idKandidat     =   $detailKandidat['id'];
                $namaKandidat   =   $detailKandidat['nama'];
                
                $pdf            =   new PDF($this);
                $options        =   new Options();
                $options->setIsRemoteEnabled(true);
                $options->setIsJavascriptEnabled(true);
                
                $kandidat       =   new KandidatModel();

                $listSkill      =   $kandidat->getSkill($idKandidat);
                $listEducation  =   $kandidat->getEducation($idKandidat);
                $listExperience =   $kandidat->getExperience($idKandidat);

                $data   =   [
                    'detailKandidat'    =>  $detailKandidat,
                    'listSkill'         =>  $listSkill,
                    'listEducation'     =>  $listEducation,
                    'listExperience'    =>  $listExperience  
                ];

                $pdf->setPaper('A4');
                $pdf->setOptions($options);
                $pdf->fileName  =   'CV '.$namaKandidat.'.pdf';
                $pdf->loadView(mitraView('kandidat/cv'), $data);
                
                // echo view(mitraView('kandidat/cv'), $data);
            }catch(Exception $e){
                $data   =   [
                    'judul'     =>  'Terjadi Kesalahan',
                    'deskripsi' =>  $e->getMessage()
                ];
                echo view(mitraView('error'), $data);
            }
        }
    }
?>