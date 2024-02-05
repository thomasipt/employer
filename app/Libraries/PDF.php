<?php
    namespace App\Libraries;

    use Dompdf\Dompdf;

    class PDF extends Dompdf{
        public $fileName;
        private $ci;

        public function __construct($codeIgniter){
            parent::__construct();
            $this->fileName     =   'Invoice Belanja.pdf';
            $this->ci           =   $codeIgniter;
        }
        public function loadView($viewPath, $viewData = []){
            $ci     =   $this->ci;
            $html   =   view($viewPath, $viewData);

            $this->loadHtml($html);
            $this->render();
            $this->stream($this->fileName, ['Attachment' => false]);
        }
    }
?>