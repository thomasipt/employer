<?php
    namespace App\Controllers\admin;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\Administrator;

    class Home extends BaseController{
        use ResponseTrait;
        public function index(){
           return view(adminView('home'));
        }
    }
?>