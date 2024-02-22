<?php
    #Assets folder
    function assetsFolder($path = null){
        $assetsFolderPath   =   'assets';
        if(!empty($path)){
            $assetsFolderPath   =   $assetsFolderPath.'/'.$path;
        }

        return $assetsFolderPath;
    }

    #Administrator
        #View
        function adminView($path = null){
            $adminViewsPath     =   'admin';
            if(!empty($path)){
                $adminViewsPath =   $adminViewsPath.'/'.$path;
            }

            return $adminViewsPath;
        }
        
        #Components
        function adminComponents($path = null){
            $adminComponentsPath   =   'components/admin';
            if(!empty($path)){
                $adminComponentsPath   =   $adminComponentsPath.'/'.$path;
            }

            return $adminComponentsPath;
        }

        #Controller
        function adminController($path = null){
            $adminControllerPath   =   'admin';
            if(!empty($path)){
                $adminControllerPath   =   $adminControllerPath.'/'.$path;
            }

            return $adminControllerPath;
        }

        #Folder Upload
        function uploadGambarAdmin($path = null){
            $uploadGambarAdminPath  =   'upload/gambar/admin';
            if(!empty($path)){
                $uploadGambarAdminPath   =   $uploadGambarAdminPath.'/'.$path;
            }

            return $uploadGambarAdminPath;
        }

    #Website
        #View
        function websiteView($path = null){
            $websiteView     =   'website';
            if(!empty($path)){
                $websiteView =   $websiteView.'/'.$path;
            }

            return $websiteView;
        }
        function websiteComponents($path = null){
            $websiteComponentsPath   =   'components/website';
            if(!empty($path)){
                $websiteComponentsPath   =   $websiteComponentsPath.'/'.$path;
            }

            return $websiteComponentsPath;
        }

        #Controller
        function websiteController($path = null){
            $websiteControllerPath   =   'website';
            if(!empty($path)){
                $websiteControllerPath   =   $websiteControllerPath.'/'.$path;
            }

            return $websiteControllerPath;
        }

    #Mitra
        #View
        function mitraView($path = null){
            $mitraView     =   'mitra';
            if(!empty($path)){
                $mitraView =   $mitraView.'/'.$path;
            }

            return $mitraView;
        }
        function mitraComponents($path = null){
            $mitraComponentsPath   =   'components/mitra';
            if(!empty($path)){
                $mitraComponentsPath   =   $mitraComponentsPath.'/'.$path;
            }

            return $mitraComponentsPath;
        }

        #Controller
        function mitraController($path = null){
            $mitraControllerPath   =   'mitra';
            if(!empty($path)){
                $mitraControllerPath   =   $mitraControllerPath.'/'.$path;
            }

            return $mitraControllerPath;
        }

        #Folder Upload
        function uploadGambarMitra($path = null){
            $uploadGambarMitraPath  =   'upload/gambar/mitra';
            if(!empty($path)){
                $uploadGambarMitraPath   =   $uploadGambarMitraPath.'/'.$path;
            }

            return $uploadGambarMitraPath;
        }
        function uploadGambarBuktiBayar($path = null){
            $uploadGambarBuktiBayar  =   'upload/gambar/bukti-bayar';
            if(!empty($path)){
                $uploadGambarBuktiBayar   =   $uploadGambarBuktiBayar.'/'.$path;
            }

            return $uploadGambarBuktiBayar;
        }
        function uploadGambarWebsite($path = null){
            $uploadGambarWebsite  =   'upload/gambar/website';
            if(!empty($path)){
                $uploadGambarWebsite   =   $uploadGambarWebsite.'/'.$path;
            }

            return $uploadGambarWebsite;
        }
        function uploadGambarPaket($path = null){
            $uploadGambarPaket  =   'upload/gambar/paket';
            if(!empty($path)){
                $uploadGambarPaket   =   $uploadGambarPaket.'/'.$path;
            }

            return $uploadGambarPaket;
        }

    #Flexstart
    function flexStart($path = null){
        $flexStartPath  =   'assets/flexstart';
        if(!empty($path)){
            $flexStartPath   =   $flexStartPath.'/'.$path;
        }

        return $flexStartPath;
    }
    function flexStartAssets($path = null){
        $flexStartAssetsPath  =   flexStart('assets');
        if(!empty($path)){
            $flexStartAssetsPath   =   $flexStartAssetsPath.'/'.$path;
        }

        return $flexStartAssetsPath;
    }
?>