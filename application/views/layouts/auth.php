
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
    <title>Login Example - Semantic</title>
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
    <link rel="stylesheet" type="text/css" href="semantic/components/list.css">
    <link rel="stylesheet" type="text/css" href="semantic/components/message.css">
    <link rel="stylesheet" type="text/css" href="semantic/components/icon.css">

    <script src="assets/library/jquery.min.js"></script>
    <script src="semantic/components/form.js"></script>
    <script src="semantic/components/transition.js"></script>

    <style type="text/css">
        body {
        background-color: #404040;
        }
        body > .grid {
        height: 100%;
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
            <h2 class="ui teal image header">
            <img src="assets/imgs/ignite-logo-circle.png" class="image">
            <div class="content">
                Ignite Source POS System
            </div>
            </h2>
            <?=form_open('ignite/login','class="ui large form"')?>
            <div class="ui stacked segment">
                <div class="field">
                <div class="ui left icon input">
                    <i class="user icon"></i>
                    <!-- <input type="text" name="email" placeholder="E-mail address"> -->
                    <?=form_input('username', set_value('username'), 'placeholder="Username"')?>
                </div>
                </div>
                <div class="field">
                <div class="ui left icon input">
                    <i class="lock icon"></i>
                    <!-- <input type="password" name="password" placeholder="Password"> -->
                    <?=form_password('psw', set_value('psw'), 'placeholder="Password"')?>
                </div>
                </div>
                <?=form_submit('save','Login', 'class="ui fluid large teal submit button"')?>
                <!-- <div class="ui fluid large teal submit button">Login</div> -->
            </div>

            <div class="ui error message"></div>

            <?=form_close()?>

            <div class="copy">
                Copyright all-right reserved 2019-2020.
            </div>
        </div>
    </div>

</body>

</html>
