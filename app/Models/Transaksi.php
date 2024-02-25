<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;
    use App\Models\Homepage;
    use App\Models\HomepageElement;

    #Libraries
    use App\Libraries\Tabel;

    class Transaksi extends BaseModel{
        public $pembayaran_bankRekening;
        public $pembayaran_nomorRekening;
        public $pembayaran_namaRekening ;

        public $approvement_pending     =   null;
        public $approvement_approved    =   'approved';
        public $approvement_rejected    =   'rejected';
        public $approvement;

        public function __construct(){
            parent::__construct();
            
            $approvement        =   [$this->approvement_pending, $this->approvement_approved, $this->approvement_rejected];
            $this->approvement  =   $approvement;

            $homepageModel          =   new Homepage();
            $homepageElementModel   =   new HomepageElement();
            
            $options    =   [
                'where' =>  ['parent' => $homepageModel->rekeningPerusahaanId]
            ];
            $rekeningPerusahaanElement  =   $homepageElementModel->getHomepageElement(null, $options);
            $rekeningPerusahaanElement  =   $homepageElementModel->convertListELementToKeyValueMap($rekeningPerusahaanElement);

            $this->pembayaran_bankRekening  =   $rekeningPerusahaanElement['_bank'];
            $this->pembayaran_namaRekening  =   $rekeningPerusahaanElement['_pemilik'];
            $this->pembayaran_nomorRekening =   $rekeningPerusahaanElement['_nomor'];
        }
        
        public function getTransaksi($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $transaksi                  =   $this->getData($t->transaksi, $id, $options);

            return $transaksi;
        }
        public function saveTransaksi($id = null, $dataTransaksi = null){
            $database   =   new Database();
            $tabel      =   new Tabel();
            $db         =   $database->connect($database->default);
            
            $transaksiBuilder   =   $db->table($tabel->transaksi);
            if(!empty($id)){
                $transaksiBuilder->where('id', $id);
                $saveTransaksi  =   $transaksiBuilder->update($dataTransaksi);
            }else{                
                $saveTransaksi  =   $transaksiBuilder->insert($dataTransaksi);
                $id         =   $db->insertID();
            }

            return ($saveTransaksi)? $id : false;
        }
        public function getJumlahTransaksiPending($idMitra = null){
            $options    =   [
                'singleRow' =>  true,
                'select'    =>  'count(id) as jumlahData',
                'where'     =>  [
                    'approvement'   =>  null
                ]
            ];

            if(!empty($idMitra)){
                $options['where']['mitra']  =   $idMitra;
            }

            $getTransaksiPending    =   $this->getTransaksi(null, $options);
            $jumlahTransaksiPending =   (empty($getTransaksiPending))? 0 : $getTransaksiPending['jumlahData'];
            return $jumlahTransaksiPending;
        }
        public function getTransaksiPending($additionOptions = null){
            $options    =   [
                'where'     =>  [
                    'approvement'   =>  null
                ]
            ];

            if(!empty($additionOptions)){
                if(is_array($additionOptions)){
                    $options    =   array_merge($options, $additionOptions);
                }
            }

            $transaksiPending   =   $this->getTransaksi(null, $options);
            return $transaksiPending;
        }
    }
?>