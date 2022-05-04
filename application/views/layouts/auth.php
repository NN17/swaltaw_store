<?php if(!$this->session->userdata('loginState')): ?>
<!DOCTYPE html>
<html>
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <base href="<?=base_url()?>"></base>
    <link rel="shorcut icon" href="assets/imgs/ignite-logo-circle.png" />
    <!-- Site Properties -->
    <title>POS version 2.0</title>
    <link rel="stylesheet" type="text/css" href="semantic/components/reset.css">
    <link rel="stylesheet" type="text/css" href="semantic/components/site.css">

    <link rel="stylesheet" type="text/css" href="semantic/components/container.css">
    <link rel="stylesheet" type="text/css" href="semantic/components/grid.css">
    <link rel="stylesheet" type="text/css" href="semantic/components/header.css">
    <link rel="stylesheet" type="text/css" href="semantic/components/image.css">
    <link rel="stylesheet" type="text/css" href="semantic/components/menu.css">

    <link rel="stylesheet" type="text/css" href="semantic/components/divider.css">
    <link rel="stylesheet" type="text/css" href="semantic/components/segment.css">
    <link rel="stylesheet" type="text/css" href="semantic/components/form.css">
    <link rel="stylesheet" type="text/css" href="semantic/components/input.css">
    <link rel="stylesheet" type="text/css" href="semantic/components/button.css">
    <link rel="stylesheet" type="text/css" href="semantic/components/checkbox.css">
    <link rel="stylesheet" type="text/css" href="semantic/components/list.css">
    <link rel="stylesheet" type="text/css" href="semantic/components/message.css">
    <link rel="stylesheet" type="text/css" href="semantic/components/icon.css">

    <script src="assets/library/jquery.min.js"></script>
    <script src="semantic/components/form.js"></script>
    <script src="semantic/components/transition.js"></script>

    <style type="text/css">
        body {
        background: url('./assets/imgs/backgrounds/back3.png');
        }
        body > .grid {
        height: 100%;
        }
        .auth-form-body{
            background: #ffffff38 !important;
        }
        .ignite-logo{
            margin: 40px;
        }
        .version{
            color: #fff;
            margin-bottom: 20px;
        }
        .header{
            color: #eee !important;
        }
        .image {
        margin-top: -100px;
        }
        .column {
        max-width: 450px;
        }
        .copy{
            color: #eee;
        }
    </style>
    <script>
    $(document)
        .ready(function() {
        $('.ui.form')
            .form({
            fields: {
                email: {
                identifier  : 'email',
                rules: [
                    {
                    type   : 'empty',
                    prompt : 'Please enter your e-mail'
                    },
                    {
                    type   : 'email',
                    prompt : 'Please enter a valid e-mail'
                    }
                ]
                },
                password: {
                identifier  : 'password',
                rules: [
                    {
                    type   : 'empty',
                    prompt : 'Please enter your password'
                    },
                    {
                    type   : 'length[6]',
                    prompt : 'Your password must be at least 6 characters'
                    }
                ]
                }
            }
            })
        ;
        })
    ;
    </script>
    </head>
    <body>

    <div class="ui middle aligned center aligned grid">
        <div class="column">
            <h2 class="ui red image header">
            <img src="assets/imgs/swaltaw_logo.png" class="image ignite-logo"><br/>
            <div class="content">
                Ignite Source POS System
            </div>
            </h2>
            <div class="version">
                Version 2.0.1
            </div>
            <?=form_open('ignite/login','class="ui large form"')?>
            <div class="ui stacked segment auth-form-body">
                <div class="field">
                    <div class="ui left icon input">
                        <i class="user icon"></i>
                        <input type="text" name="username" value="<?=isset($_COOKIE['loginId'])?$_COOKIE['loginId']:''?>" placeholder="Username">
                    </div>
                </div>
                <div class="field">
                    <div class="ui left icon input">
                        <i class="lock icon"></i>
                       <input type="password" name="psw" value="<?=isset($_COOKIE['loginPass'])?$_COOKIE['loginPass']:''?>" placeholder="Password">
                    </div>
                </div>
                <?=form_submit('save','Login', 'class="ui fluid large red submit button"')?>

                <div class="field" style="margin-top:20px"> 
                    <div class="ui checkbox">
                        <input type="checkbox" name="remember" <?=isset($_COOKIE['loginId'])?'checked':''?>>
                        <label>Remember Me</label>
                    </div>
                </div>
                <!-- <div class="ui fluid large teal submit button">Login</div> -->
            </div>

            <div class="ui error message"></div>

            <?=form_close()?>

            
            <div class="copy">
                Copyright all-right reserved by Ignitesource <?=date('Y')?>.
            </div>
        </div>
    </div>

</body>

</html>

<?php else: redirect('home')?>

<?php endif; ?>
