<?php
    $homepageModel          =   model('Homepage');
    $homepageElementModel   =   model('HomepageElement');

    $options    =   [
        'where' =>  ['parent' => $homepageModel->syaratDanKetentuanId]
    ];
    
    $syaratDanKetentuanElement  =   $homepageElementModel->getHomepageElement(null, $options);
    $syaratDanKetentuanElement  =   $homepageElementModel->convertListELementToKeyValueMap($syaratDanKetentuanElement);

    $content    =   $syaratDanKetentuanElement['_content'];
    echo $content;
?>