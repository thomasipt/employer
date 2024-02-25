<?php
    $homepageModel          =   model('Homepage');
    $homepageElementModel   =   model('HomepageElement');

    $options    =   [
        'where' =>  ['parent' => $homepageModel->kebijakanPrivasiId]
    ];
    
    $kebijakanPrivasiElement  =   $homepageElementModel->getHomepageElement(null, $options);
    $kebijakanPrivasiElement  =   $homepageElementModel->convertListELementToKeyValueMap($kebijakanPrivasiElement);

    $content    =   $kebijakanPrivasiElement['_content'];
    echo $content;
?>