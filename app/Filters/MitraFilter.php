<?php
    namespace App\Filters;

    use CodeIgniter\Filters\FilterInterface;
    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;

    #Model
    use App\Models\Mitra;

    #Library
    use App\Libraries\MitraJWT;

    class MitraFilter implements FilterInterface{
        public function before(RequestInterface $request, $arguments = null){
            $session        =   session();
            $mitraSession   =   $session->get('mitra');
            
            $mitra          =   new Mitra();
            $mitraJWT       =   new MitraJWT();

            $authorization          =   $request->getServer('HTTP_AUTHORIZATION');
            if(!empty($authorization)){
                $encodedJWT             =   null;  
                if (preg_match('/Bearer\s(\S+)/', $authorization, $matches)) {
                    $encodedJWT =   $matches[1];
                }        

                if(!empty($encodedJWT)){
                    $decodedAuthorization   =   $mitraJWT->decode($encodedJWT);
                    $detailMitra            =   (property_exists($decodedAuthorization, 'mitra'))? $decodedAuthorization->mitra : null;
                    if(!empty($detailMitra)){
                        $idMitra            =   $detailMitra->id;
                        $detailMitra        =   $mitra->getMitra($idMitra);

                        $request->mitra     =   $detailMitra;
                        return $request;
                    }
                }
            }

            if(empty($mitraSession)){
                $request->mitra     =   null;
                return redirect()->to(mitraController('login'));
            }

            $id             =   $mitraSession['id'];
            $detailMitra    =   $mitra->getMitra($id);

            $request->mitra =   $detailMitra;

            return $request;
        }
        public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){

        }
    }
?>