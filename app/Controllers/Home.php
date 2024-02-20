<?php

namespace App\Controllers;

use App\Models\HomepageElement;
use App\Models\Paket;

class Home extends BaseController{
    public function index(): string{
        $paket              =   new Paket();
        $homepageElement    =   new HomepageElement();

        $options    =   [
            'order_by'  =>  [
                'column'        =>  'id',
                'orientation'   =>  'asc'
            ]
        ];
        $listPaket  =   $paket->getPaket(null, $options);

        $options        =   [
            'where' =>  [
                'parent'    =>  1 #1 = id hero
            ]
        ];
        $heroElement    =   $homepageElement->getHomepageElement(null, $options);
        $heroElement    =   $homepageElement->convertListELementToKeyValueMap($heroElement);

        $data   =   [
            'models'    =>  [
                'paket'             =>  $paket,
                'homepageElement'   =>  $homepageElement
            ],
            'data'  =>  [
                'listPaket'     =>  $listPaket,
                'heroElement'   =>  $heroElement
            ]
        ];
        return view('welcome', $data);
    }
}
