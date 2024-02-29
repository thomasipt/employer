<?php
    function convertToInt($formattedNumber){
        $replacement    =   preg_replace('/,/', '', $formattedNumber);
        return $replacement;
    }
    function convertToString($number){
        $replacement    =   number_format($number, 0, ',', ',');
        return $replacement;
    }
?>