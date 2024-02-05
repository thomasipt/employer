<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;

    #Libraries
    use App\Libraries\Tabel;

    class Transaksi extends BaseModel{
        public $pembayaran_bankRekening     =   'BRI';
        public $pembayaran_nomorRekening    =   '732301008529534';
        public $pembayaran_namaRekening     =   'Falentino Djoka';

        public $approvement_approved    =   'approved';
        public $approvement_rejected    =   'rejected';
        public $approvement;

        public function __construct(){
            parent::__construct();
            
            $approvement        =   [$this->approvement_approved, $this->approvement_rejected];
            $this->approvement  =   $approvement;
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