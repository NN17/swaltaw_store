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
   
   <?php if($this->session->userdata('loginState')):?>
    <div class="ui fixed inverted menu <?=$this->session->userdata('site_lang')?>">
        
        <div class="ui container fluid">
            <a href="ignite/home" class="header item">
            <img src="assets/imgs/ignite-logo-circle-white.png" />
            &nbsp; &nbsp; IGNITE SOURCE &nbsp;
            <small class="text-grey">POS System</small>
            </a>
            <?php $main_menu = $this->ignite_model->get_limit_data('link_structure_tbl', 'sub_menu', false)->result(); ?>
            <?php foreach($main_menu as $row): ?>
                <?php 
                    $machine = explode("/", $row->machine);
                ?>
                <?php if($this->auth->checkLinkAccess($this->session->userdata('Id'), $row->linkId)): ?>
                    <a href="<?=$row->machine?>" class="item <?php if($this->uri->segment(1) == $machine[0]){echo 'active';}?>">
                        <i class="icon <?=$row->color?> <?=$row->icon_class?>"></i> <?=$this->lang->line($row->lang_name)?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>

            <div class="right menu">
                <!-- Username -->
                <div class="borderless item">
                    <div class="ui dropdown">
                        <div class="default text"><i class="icon user circle green"></i> <?=$this->session->userdata('username')?></div>
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <a href="logout" class="item" data-value="female"><i class="icon sign-out"></i> <?=$this->lang->line('logout')?></a>
                        </div>
                        </div>
                </div>

                <!-- Setting -->
                <div class="borderless item">
                    <div class="ui dropdown">
                        <div class="default icon"><i class="icon grey cog"></i></div>
                        
                            <div class="ui setting menu">
                                <?php $submenu = $this->ignite_model->get_limit_data('link_structure_tbl', 'sub_menu', true)->result();?>
                                <?php foreach($submenu as $menu): ?>
                                    <?php if($this->auth->checkLinkAccess($this->session->userdata('Id'), $menu->linkId)): ?>
                                    <a href="<?=$menu->machine?>" class="item" data-value="female">
                                        <i class="icon <?=$menu->icon_class?> <?=$menu->color?>"></i> <?=$this->lang->line($menu->lang_name)?>
                                    </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                </div>


                <div class="item">
                    <div class="ui dropdown">
                        <div class="text">
                            <i class="language icon olive"></i>
                            <?=$this->session->userdata('site_lang')=='english'?'EN':'MM'?>
                        </div>
                        <div class="menu">
                            <a href="language/english" class="item" data-value="female"><i class="uk flag"></i> <?=$this->lang->line('lang_en')?></a>
                            <a href="language/myanmar" class="item" data-value="female"><i class="mm flag"></i> <?=$this->lang->line('lang_mm')?></a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <div class="breadcrumb-wrap">
        <div class="ui container fluid">
            <div class="ui breadcrumb">
                <i class="icon map signs grey"></i> | <?php $this->breadcrumb->show()?>
            </div>
        </div>
    </div>

    <div id="maincontent" class="ui main container fluid">
        <div class="content <?=$this->session->userdata('site_lang')?>">
            <?php $this->load->view($content)?>            
        </div>
    </div>

    <?php else:?>
        <?php $this->load->view('errors/error_401')?>
    <?php endif;?>

    <script src="assets/js/jquery-3.4.1.min.js"></script>
    <script src="semantic/semantic.min.js"></script>
    <script type="text/javascript" src="assets/js/Chart.js"></script>
    <script type="text/javascript" src="semantic/Semantic-UI-Alert.js"></script>
    <script type="text/javascript" src="assets/DataTables.SemanticUI/datatables.min.js"></script>
    <script src="assets/js/jquery.datetimepicker.js"></script>
    <script src="assets/js/jquery-confirm.min.js"></script>
    <script type="text/javascript" src="assets/js/croppie.js"></script>
    <script src="assets/js/custom.js"></script>
    <script type="text/javascript">
        <?php 
            if($this->session->tempdata('success')): 
        ?>
        var message = '<?=$this->session->tempdata('success')?>';
        $.uiAlert({
            textHead: 'Successful !', // header
            text: message, // Text
            bgcolor: '#19c3aa', // background-color
            textcolor: '#fff', // color
            position: 'bottom-right',// position . top And bottom ||  left / center / right
            icon: 'checkmark box', // icon in semantic-UI
            time: 3, // time
              })
        <?php
            endif; 
        ?>
    </script>
</body>
</html>