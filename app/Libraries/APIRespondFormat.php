<?php
    namespace App\Libraries;

    class APIRespondFormat{
        protected $respond;

        public function __construct($status, $message, $data){
            $returnedData   =   [
                'status'    =>  $status,
                'message'   =>  $message,
                'data'      =>  $data
            ];
            
            $this->respond  =   $returnedData;
        }
        public function getRespond(){
            $respond    =   $this->respond;
            return $respond;
        }
    }
?>