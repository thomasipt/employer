<?php
    namespace App\Libraries;

    class FileUpload{
		private $uploadGambar	=	'upload/gambar';
		public $uploadGambarAdmin;

		public $defaultImage_admin 		=	'admin-default.png';
		public array $defaultImage;
		
		public function __construct(){
			$uploadGambarAdmin		=	$this->uploadGambar.'/admin';
			$defaultImage			=	[$this->defaultImage_admin];

			$this->uploadGambarAdmin		=	$uploadGambarAdmin;
			$this->defaultImage				=	$defaultImage;
		}	
		public function resizeImage($sourceImage, $destination, $desiredWidth = null, $desiredHeight = null){
			#dari mana mau ke mana dengan panjang berapa dan tinggi berapa dengan nama apa
			$destinationFolderArray 	=	explode('/', $destination);

			$destinationFolder 	=	'';

            list($sourceImageWidth, $sourceImageHeight) =   getimagesize($sourceImage);
            if(empty($desiredWidth)){
                $desiredWidth   =   $sourceImageWidth;
            }
            if(empty($desiredHeight)){
                $desiredHeight  =   $sourceImageHeight;
            }

			$lastItemIndex	=	(count($destinationFolderArray) - 1);
			foreach($destinationFolderArray as $index => $item){
				if($index != $lastItemIndex){
					$slash 	=	($index < ($lastItemIndex - 1))? '/' : '';
					$destinationFolder 	.=	$item.$slash;
				}
			}

			if(!file_exists($destinationFolder)){
				mkdir($destinationFolder);
			}

			$sourceImageName	=	explode('/', $sourceImage);
			$sourceImageName	=	$sourceImageName[count($sourceImageName) - 1]; //mengambil nama file di indeks array terakhir

			$imageExtension 	=	explode('.', $sourceImageName);
			$imageExtension		=	strtolower($imageExtension[count($imageExtension) - 1]);

			list($originalWidth, $originalHeight)	=	getimagesize($sourceImage);

			$blankImage 	=	imagecreatetruecolor($desiredWidth, $desiredHeight);

			if($imageExtension === 'png'){
				$resource		=	imagecreatefrompng($sourceImage);
			}else{
				$resource 		=	imagecreatefromjpeg($sourceImage);
			}

			imagecopyresampled($blankImage, $resource, 0, 0, 0, 0, $desiredWidth, $desiredHeight, $originalWidth, $originalHeight);

			if($imageExtension === 'png'){
				imagepng($blankImage, $destination);
			}else{
				imagejpeg($blankImage, $destination);
			}

			imagedestroy($blankImage);
			imagedestroy($resource);
		}
    }
?>