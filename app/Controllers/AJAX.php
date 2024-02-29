<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

#Library
use App\Libraries\APIRespondFormat;

#Models
use App\Models\Provinsi;
use App\Models\Kota;

use Exception;

class AJAX extends BaseController{
    use ResponseTrait;

    private function provinsiChecking($idProvinsi){
        $provinsi       =   new Provinsi();

        $detailProvinsi =   $provinsi->getProvinsi($idProvinsi);
        if(empty($detailProvinsi)){
            throw new Exception('Tidak ada provinsi dengan pengenal '.$idProvinsi.'!');
        }

        return $detailProvinsi;
    }
    public function getProvinsi(){
        $provinsi       =   new Provinsi();

        $options        =   [
            'select'    =>  'id, nama',
            'order_by'  =>  [
                'column'        =>  'nama',
                'orientation'   =>  'asc'
            ]
        ];
        $listProvinsi   =   $provinsi->getProvinsi(null, $options);

        $arf        =   new APIRespondFormat(true, null, ['listProvinsi' => $listProvinsi]);
        $respond    =   $arf->getRespond();

        return $this->respond($respond);
    }
    public function getKotaPerProvinsi($idProvinsi){
        $status     =   false;
        $message    =   'Tidak dapat meresponse data kota per provinsi dengan baik! Ulangi lagi!';
        $data       =   null;

        try{
            $detailProvinsi =   $this->provinsiChecking($idProvinsi);
            $kodeProvinsi   =   $detailProvinsi['kode'];
            
            $kota       =   new Kota();

            $kotaOptions    =   [
                'select'    =>  'kode as id, nama',
                'order_by'  =>  [
                    'column'        =>  'nama',
                    'orientation'   =>  'asc'
                ]
            ];
            $listKota       =   $kota->getKotaPerProvinsi($kodeProvinsi, $kotaOptions);
            
            $status     =   true;
            $message    =   null;
            $data       =   [
                'listKota'  =>  $listKota
            ];
        }catch(Exception $e){
            $status     =   false;
            $message    =   $e->getMessage();
            $data       =   null;
        }

        $arf        =   new APIRespondFormat($status, $message, $data);
        $respond    =   $arf->getRespond();

        return $this->respond($respond);
    }
}
