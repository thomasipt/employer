<?php
    namespace App\Controllers\mitra;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\Administrator;

    class Home extends BaseController{
        use ResponseTrait;
        public function index(){
           return view(mitraView('home'));
        }
    }
?>