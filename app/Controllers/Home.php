<?php

namespace App\Controllers;

use App\Libraries\Tabel;

use App\Models\Homepage;
use App\Models\HomepageElement;
use App\Models\Loker;
use App\Models\LokerFree;
use App\Models\Paket;

class Home extends BaseController{
    public function index(): string{
        helper('CustomDate');

        $paket              =   new Paket();
        $homepage           =   new Homepage();
        $homepageElement    =   new HomepageElement();
        $loker              =   new Loker();
        $lokerFree          =   new LokerFree();
        $tabel              =   new Tabel();

        $options    =   [
            'order_by'  =>  [
                'column'        =>  'id',
                'orientation'   =>  'asc'
            ]
        ];
        $listPaket  =   $paket->getPaket(null, $options);

        $options        =   [
            'where' =>  [
                'parent'    =>  $homepage->heroId
            ]
        ];
        $heroElement    =   $homepageElement->getHomepageElement(null, $options);
        $heroElement    =   $homepageElement->convertListELementToKeyValueMap($heroElement);
        
        $options    =   [
            'where' =>  [
                'parent'    =>  $homepage->aboutUsId
            ]
        ];
        $aboutUsElement    =   $homepageElement->getHomepageElement(null, $options);
        $aboutUsElement    =   $homepageElement->convertListELementToKeyValueMap($aboutUsElement);
        
        $options    =   [
            'where' =>  [
                'parent'    =>  $homepage->featuresId
            ]
        ];
        $featuresElement    =   $homepageElement->getHomepageElement(null, $options);
        $featuresElement    =   $homepageElement->convertListELementToKeyValueMap($featuresElement);

        $options    =   [
            'where' =>  [
                'parent'    =>  $homepage->paketId
            ]
        ];
        $paketElement    =   $homepageElement->getHomepageElement(null, $options);
        $paketElement    =   $homepageElement->convertListELementToKeyValueMap($paketElement);
        
        $options    =   [
            'where' =>  [
                'parent'    =>  $homepage->contactUsId
            ]
        ];
        $contactUsElement    =   $homepageElement->getHomepageElement(null, $options);
        $contactUsElement    =   $homepageElement->convertListELementToKeyValueMap($contactUsElement);

        $options            =   [
            'limit'     =>  6,
            'select'    =>  'pT.*, mitra.nama as namaPerusahaan',
            'join'      =>  [
                ['table' => $tabel->mitra.' mitra', 'condition' => 'mitra.id=pT.createdBy']
            ]
        ];
        $listLokerPremium   =   $loker->getLoker(null, $options);

        $options            =   [
            'limit' =>  4
        ];
        $listLokerFree      =   $lokerFree->getLokerFree(null, $options);

        $data   =   [
            'models'    =>  [
                'paket'             =>  $paket,
                'homepageElement'   =>  $homepageElement
            ],
            'data'  =>  [
                'listLokerFree'     =>  $listLokerFree,
                'listLokerPremium'  =>  $listLokerPremium,
                'listPaket'         =>  $listPaket,
                'heroElement'       =>  $heroElement,
                'aboutUsElement'    =>  $aboutUsElement,
                'featuresElement'   =>  $featuresElement,
                'paketElement'      =>  $paketElement,
                'contactUsElement'  =>  $contactUsElement
            ]
        ];
        return view('welcome', $data);
    }
}
