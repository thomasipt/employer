<?php
    namespace App\Libraries;

    class FormValidation{
        public $rule_required   =   'required';
        public $rule_numeric    =   'numeric';
        public $rule_validEmail =   'valid_email';

        public function getRequiredMessage($elementName = null){
            $message    =   'Harap isi bidang ini!';
            if(!empty($elementName)){
                $message    =   'Harap isi bidang '.$elementName.' ini!';
            }

            return $message;
        }
        public function getNumericMessage($elementName = null){
            $message    =   'Bidang ini wajib diisikan dengan data Numeric (0 s/d 9)!';
            if(!empty($elementName)){
                $message    =   $elementName.' wajib diisikan dengan data Numeric (0 s/d 9)!';
            }
            
            return $message;
        }
        public function getValidDateMessage($elementName = null){
            $message    =   'Bidang ini wajib diisikan dengan data tanggal yang valid!';
            if(!empty($elementName)){
                $message    =   $elementName.' wajib diisikan dengan data tanggal!';
            }

            return $message;
        }
        public function getMaxSizeMessage($elementName = null){
            $message    =   'Maaf, ukuran file yang diuplad melebihi batas!';
            if(!empty($elementName)){
                $message    =   $elementName.' melebihi batas ukuran file yang diizinkan!';
            }

            return $message;
        }
        public function getIsImageMessage($elementName = null){
            $message    =   'Maaf, file yang anda upload bukan gambar!';
            if(!empty($elementName)){
                $message    =   $elementName.' bukan gambar!';
            }

            return $message;
        }
        public function getMaxDimensionMessage($elementName = null){
            $message    =   'Maaf, file yang anda upload melebihi batas dimensi (panjang dan lebar) yang diizinkan!';
            if(!empty($elementName)){
                $message    =   $elementName.' memiliki panjang dan lebar diluar batas ketentuan!';
            }

            return $message;
        }
        public function getMimeTypeMessage($elementName = null){
            $message    =   'Maaf, jenis file yang anda upload tidak diizikan!';
            if(!empty($elementName)){
                $message    =   $elementName.' memiliki jenis file yang tidak diizinkan!';
            }

            return $message;
        }
        public function getExtensionMessage($elementName = null){
            $message    =   'Maaf, format (ekstensi) file yang anda upload tidak diizikan!';
            if(!empty($elementName)){
                $message    =   $elementName.' memiliki format (ekstensi) file yang tidak diizinkan!';
            }

            return $message;
        }
        public function getValidEmailMessage($elementName = null){
            $message    =   'Maaf, email yang diinputkan tidak valid!';
            if(!empty($elementName)){
                $message    =   'Harap isi '.$elementName.' dengan email yang valid!';
            }

            return $message;
        }
        public function getMessage($rule, $formName = null){
            $message    =   null;
            if($rule == $this->rule_required){
                $message    =   $this->getRequiredMessage($formName);
            }
            if($rule == $this->rule_numeric){
                $message    =   $this->getNumericMessage($formName);
            }
            if($rule == $this->rule_validEmail){
                $message    =   $this->getValidEmailMessage($formName);
            }

            return $message;
        }
        public function generateCustomMessageForSingleRule($validationRules){
            $customMessages =   [];
            if(is_array($validationRules)){
                foreach($validationRules as $formName => $rulesString){
                    $rulesArray     =   explode('|', $rulesString);
                    $ruleMessages   =   [];
                    foreach($rulesArray as $rule){
                        $ruleMessages[$rule]    =   $this->getMessage($rule, $formName);
                    }
                    $customMessages[$formName]  =   $ruleMessages;
                }
            }

            return $customMessages;
        }
    }
?>