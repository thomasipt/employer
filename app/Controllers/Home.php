<?php

namespace App\Controllers;

use App\Models\Paket;

class Home extends BaseController{
    public function index(): string{
        $paket      =   new Paket();

        $options    =   [
            'order_by'  =>  [
                'column'        =>  'id',
                'orientation'   =>  'asc'
            ]
        ];
        $listPaket  =   $paket->getPaket(null, $options);

        $data   =   [
            'models'    =>  [
                'paket' =>  $paket
            ],
            'data'  =>  [
                'listPaket' =>  $listPaket
            ]
        ];
        return view('welcome', $data);
    }
}
