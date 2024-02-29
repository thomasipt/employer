<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;

    #Libraries
    use App\Libraries\Tabel;

    class Kota extends BaseModel{
        public function getKota($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $kota                       =   $this->getData($t->kota, $id, $options, 'kode');

            return $kota;
        }
        public function getKotaPerProvinsi($kodeProvinsi, $kotaOptions = null){
            $options            =   [
                'where' =>  [
                    'province'    =>  $kodeProvinsi
                ]
            ];

            if(!empty($kotaOptions)){
                if(is_array($kotaOptions)){
                    $options    =   array_merge($options, $kotaOptions);
                }
            }

            $kotaPerProvinsi    =   $this->getKota(null, $options);
            return $kotaPerProvinsi;
        }
    }
?>