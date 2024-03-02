<?php
    if(!isset($pageTitle) || empty($pageTitle)){
        $pageTitle  =   'Dashboard Mitra';
    }

    $appConfig  =   config('Config\App');
    $appName    =   $appConfig->appName;
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$pageTitle?> | <?=$appName?></title>

    <!-- jQuery -->
    <script src="<?= base_url(assetsFolder('plugins/jquery/jquery.min.js')) ?>/"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="<?= base_url(assetsFolder('plugins/jquery-ui/jquery-ui.min.js')) ?>"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url(assetsFolder('plugins/bootstrap/js/bootstrap.bundle.min.js')) ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url(assetsFolder('dist/js/adminlte.js')) ?>"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/fontawesome-free/css/all.min.css')) ?>" />
    <link rel="stylesheet" href="<?= base_url(assetsFolder('plugins/ionicons/ionicons.min.css')) ?>" />
    <link rel="stylesheet" href="<?= base_url(assetsFolder('dist/css/adminlte.min.css')) ?>" />
    <link rel="shortcut icon" href="<?= base_url(assetsFolder('img/icon.png')) ?>" />

    <script src='<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.js'))?>'></script>
    <link rel="stylesheet" href='<?=base_url(assetsFolder('plugins/sweetalert2/sweetalert2.min.css'))?>' />
    
    <style type='text/css'>
        .cp{
            cursor:pointer;
        }
        .vam{
            vertical-align: middle !important;
        }
        .img-50-50{
            width: 50px;
            height: 50px;
        }
        .img-100-100{
            width: 100px;
            height: 100px;
        }
        .img-150-150{
            width: 150px;
            height: 150px;
        }
        .of-cover{
            object-fit: cover;
        }
        .blink{
            animation: blink 1s linear infinite;
        }
        .border-image{
            border: 3.5px solid #0D9AD7;
            border-radius: 50%;
            padding: 3.75px;
        }
        @keyframes blink{
            0%{opacity: 0;}
            50%{opacity: .5;}
            100%{opacity: 1;}
        }
        .img-icon{
            width: 20px;
            height: 20px;
        }
    </style>
    <script language='Javascript'>
        async function copy(stringToCopy){
            navigator.clipboard.writeText(stringToCopy);
            alert('Coppied!');
        }
        async function fileHandler(thisContext){
            let _el         =   $(thisContext);
            let imgData     =   _el.prop('files')[0];
            let _preview    =   _el.data('preview');

            let _previewEl  =   $(_preview);

            let fileReader  =   new FileReader();
            fileReader.readAsDataURL(imgData);
            fileReader.onload   =   (e) =>  {
                let imgResult   =   e.target.result;

                _previewEl.attr('src', imgResult);
                _previewEl.attr('class', 'w-100 img-thumbnail');
            }
        }
        

        function formatNumber(jQueryElement) {
            return jQueryElement.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        }

        function formatCurrency(thisContext){
            let input   =   $(thisContext);
            // appends $ to value, validates decimal side
            // and puts cursor back in right position.
            
            // get input value
            var input_val = input.val();
            
            // don't validate empty input
            if (input_val === "") { return; }
            
            // original length
            var original_len = input_val.length;

            // initial caret position 
            var caret_pos = input.prop("selectionStart");
                
            // check for decimal
            if (input_val.indexOf(".") >= 0) {

                // get position of first decimal
                // this prevents multiple decimals from
                // being entered
                var decimal_pos = input_val.indexOf(".");

                // split number by decimal point
                var left_side = input_val.substring(0, decimal_pos);
                var right_side = input_val.substring(decimal_pos);

                // add commas to left side of number
                left_side = formatNumber(left_side);

                // validate right side
                right_side = formatNumber(right_side);
                                
                // Limit decimal to only 2 digits
                right_side = right_side.substring(0, 2);

                // join number by .
                input_val = left_side + "." + right_side;

            } else {
                // no decimal entered
                // add commas to number
                // remove all non-digits
                input_val = formatNumber(input_val);
            }
            
            // send updated string to input
            input.val(input_val);

            // put caret back in the right position
            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }
        
        async function togglePassword(thisContext, parentElement){
            let _el     =   $(thisContext);
            let _parent =   $(parentElement);

            let _passwordElements   =   _parent.find('.password');
            _passwordElements.each((_, passwordElement) => {
                let _element                =   $(passwordElement);
                let _passwordIconElement    =   _parent.find('.password-icon');

                let _type               =   _element.attr('type');
                let _isTypePassword     =   _type == 'password';

                _element.attr('type', (_isTypePassword)? 'text' : 'password');
                _passwordIconElement.attr('class', (_isTypePassword)? 'fa fa-eye-slash password-icon' : 'fa fa-eye password-icon');
            });
        }
    </script>
</head>