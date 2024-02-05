<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;

    #Libraries
    use App\Libraries\Tabel;

    class Paket extends BaseModel{
        public $code_silver     =   'silver';
        public $code_gold       =   'gold';
        public $code_premium    =   'premium';
        public $code;
        public $color;

        public $persentasePPN     =   10;

        public function __construct(){
            parent::__construct();
            $silverColor    =   '#C0C0C0';
            $goldColor      =   '#FFD700';
            $premiumColor   =   '#07d5c0';

            $color  =   [
                $this->code_silver  =>  $silverColor,
                $this->code_gold    =>  $goldColor,
                $this->code_premium =>  $premiumColor    
            ];

            $code   =   [
                $this->code_silver,
                $this->code_gold,
                $this->code_premium
            ];

            $this->color    =   $color;
            $this->code     =   $code;
        }
        public function getPaket($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $paket                      =   $this->getData($t->paket, $id, $options);

            return $paket;
        }
        public function savePaket($id = null, $dataPaket = null){
            $database   =   new Database();
            $tabel      =   new Tabel();
            $db         =   $database->connect($database->default);
            
            $paketBuilder   =   $db->table($tabel->paket);
            if(!empty($id)){
                $paketBuilder->where('id', $id);
                $savePaket  =   $paketBuilder->update($dataPaket);
            }else{                
                $savePaket  =   $paketBuilder->insert($dataPaket);
                $id         =   $db->insertID();
            }

            return ($savePaket)? $id : false;
        }
    }
?>