<?php
    namespace App\Filters;

    use CodeIgniter\Filters\FilterInterface;
    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;

    use App\Models\Administrator;

    class AuthFilter implements FilterInterface{
        public function before(RequestInterface $request, $arguments = null){
            $session                =   session();
            $administratorSession   =   $session->get('administrator');
            
            if(empty($administratorSession)){
                $request->detailAdministrator   =   null;
                return redirect()->to(adminController('login'));
            }
            
            $administrator          =   new Administrator();

            $id                     =   $administratorSession['id'];
            $detailAdministrator    =   $administrator->getAdministrator($id);

            $request->administrator =   $detailAdministrator;

            return $request;
        }
        public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){

        }
    }
?>