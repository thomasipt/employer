<?php
    namespace App\Filters;

    use CodeIgniter\Filters\FilterInterface;
    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;

    use App\Models\Mitra;

    class MitraFilter implements FilterInterface{
        public function before(RequestInterface $request, $arguments = null){
            $session        =   session();
            $mitraSession   =   $session->get('mitra');
            
            if(empty($mitraSession)){
                $request->mitra     =   null;
                return redirect()->to(mitraController('login'));
            }
            
            $mitra          =   new Mitra();

            $id             =   $mitraSession['id'];
            $detailMitra    =   $mitra->getMitra($id);

            $request->mitra =   $detailMitra;

            return $request;
        }
        public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){

        }
    }
?>