<!DOCTYPE html>
<html>
<head>
    <base href="<?=base_url()?>"></base>
    <link rel="shorcut icon" href="assets/imgs/ignite-logo-circle.png" />
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ignite Source</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Padauk|Quicksand&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="semantic/semantic.min.css" />
    <link rel="stylesheet" type="text/css" href="semantic/Semantic-UI-Alert.css" />
    <link rel="stylesheet" type="text/css" href="assets/DataTables.SemanticUI/datatables.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/jquery.datetimepicker.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/jquery-confirm.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/croppie.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/flaticon.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css" />
</head>
<body>
   
   <div class="header">
       <h3>Swal Taw Store</h3>
   </div>

    <div id="maincontent" class="ui main container fluid">
        <div class="content <?=$this->session->userdata('site_lang')?>">
            <?php $this->load->view($content)?>            
        </div>
    </div>

    <script src="assets/js/jquery-3.4.1.min.js"></script>
    <script src="semantic/semantic.min.js"></script>
    <script type="text/javascript" src="assets/js/Chart.js"></script>
    <script type="text/javascript" src="semantic/Semantic-UI-Alert.js"></script>
    <script type="text/javascript" src="assets/DataTables.SemanticUI/datatables.min.js"></script>
    <script src="assets/js/jquery.datetimepicker.js"></script>
    <script src="assets/js/jquery-confirm.min.js"></script>
    <script type="text/javascript" src="assets/js/croppie.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>