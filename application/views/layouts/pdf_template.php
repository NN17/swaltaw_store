<!DOCTYPE html>
<html>
<head>
    <base href="<?=base_url()?>"></base>
    <link rel="shorcut icon" href="assets/imgs/ignite-logo-circle.png" />
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ignite Source</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        body {
            font-family: 'padaukbook' !important;
        }   
        table {
            /*border: 1px solid #333;*/
            border-collapse: collapse;
            width: 100%;
        }    

        tr td, tr, th {
            border: 1px solid black;
            padding: 10px;
        }   
        .ui.right.aligned {
            text-align: right;
        }                                      
    </style>
</head>
<body>
   
   <div class="header">
       <h3 class="text-center">Swal Taw Store ( <?=$name?> )</h3>
       <div class="ui right aligned"><?=date('d / M / Y h:i:s A')?></div>
   </div>

    <div id="maincontent" class="ui main container fluid">
        <div class="content">
            <?php $this->load->view($content)?>            
        </div>
    </div>

    
</body>
</html>