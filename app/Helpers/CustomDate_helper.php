<?php 
	function rightNow(){
        date_default_timezone_set('Asia/Jakarta');
        return date('Y-m-d H:i:s');
	}
    function currentDate(){
        date_default_timezone_set('Asia/Jakarta');
        return date('Y-m-d');
    }
    function formattedDate($date = null){
        $date   =   (!is_null($date))? $date : rightNow();
        $formattedDate  =   date('d/m/Y', strtotime($date));

        return $formattedDate;
    }
    function formattedDateTime($dateTime = null){
        $dateTime   =   (!is_null($dateTime))? $dateTime : rightNow();
        $formattedDateTime  =   date('d/m/Y H:i:s', strtotime($dateTime));

        return $formattedDateTime;
    }
    function getMonthName($bulanInteger){
        switch ($bulanInteger){
            case 1:
                return "Januari";
                break;
            case 2:
                return "Februari";
                break;
            case 3:
                return "Maret";
                break;
            case 4:
                return "April";
                break;
            case 5:
                return "Mei";
                break;
            case 6:
                return "Juni";
                break;
            case 7:
                return "Juli";
                break;
            case 8:
                return "Agustus";
                break;
            case 9:
                return "September";
                break;
            case 10:
                return "Oktober";
                break;
            case 11:
                return "Nopember";
                break;
            case 12:
                return "Desember";
                break;
        }
    }
?>