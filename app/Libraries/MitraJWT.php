<?php
    namespace App\Libraries;

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    use stdClass;

    class MitraJWT{
        private $key        =   'Empl0y3R';
        private $algorithm  =   'HS256';

        public function encode($payload){
            $encoded    =   JWT::encode($payload, $this->key, $this->algorithm);
            return $encoded;
        }
        public function decode($token){
            $headers    =   new stdClass();
            $decoded    =   JWT::decode($token, new Key($this->key, $this->algorithm), $headers);
            return $decoded;
        }
    }
?>