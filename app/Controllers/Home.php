<?php

namespace App\Controllers;

use App\Models\Homepage;
use App\Models\HomepageElement;
use App\Models\Loker;
use App\Models\Paket;

class Home extends BaseController{
    public function index(): string{
        helper('CustomDate');

        $paket              =   new Paket();
        $homepage           =   new Homepage();
        $homepageElement    =   new HomepageElement();
        $loker              =   new Loker();

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

        $options            =   [
            'limit' =>  3
        ];
        $listLokerPremium   =   $loker->getLoker(null, $options);

        $data   =   [
            'models'    =>  [
                'paket'             =>  $paket,
                'homepageElement'   =>  $homepageElement
            ],
            'data'  =>  [
                'listLokerPremium'  =>  $listLokerPremium,
                'listPaket'         =>  $listPaket,
                'heroElement'       =>  $heroElement,
                'aboutUsElement'    =>  $aboutUsElement,
                'featuresElement'   =>  $featuresElement
            ]
        ];
        return view('welcome', $data);
    }
}
