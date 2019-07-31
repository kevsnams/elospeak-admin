<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LEGO Admin &#8212; Login</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,400,700i,900i&display=swap" rel="stylesheet">

    <link href="<?php echo url('/') ?>/uikit-3.1.6/css/uikit.min.css" rel="stylesheet">
    <link href="<?php echo url('/') ?>/css/login.css" rel="stylesheet">
</head>
<body>
    <div uk-height-viewport="expand: true" class="login-bg">
        <div class="uk-flex uk-flex-center">
            <div class="uk-margin-large-top">
                <h1 class="login-header uk-text-center">LEGO Admin</h1>
                <div class="login-box uk-margin-top uk-border-rounded uk-box-shadow-medium">
                    <form action="<?php echo route('login') ?>" method="POST">
                        @csrf
                        <div class="uk-margin">
                            <div class="uk-inline">
                                <span class="uk-form-icon" uk-icon="icon: user"></span>
                                <input class="uk-input uk-form-width-large uk-border-rounded" name="username" type="text" placeholder="Username">
                            </div>
                        </div>

                        <div class="uk-margin">
                            <div class="uk-inline">
                                <span class="uk-form-icon" uk-icon="icon: lock"></span>
                                <input class="uk-input uk-form-width-large uk-border-rounded" name="password" type="password" placeholder="Password">
                            </div>
                        </div>
                        
                        <div class="uk-margin">
                            <div class="uk-grid-small" uk-grid>
                                <div class="uk-width-auto@m">
                                    <button class="uk-button uk-button-primary" type="submit">Login</button>
                                </div>
                                <div class="uk-width-expand@m login-remember-me">
                                    <label><input class="uk-checkbox" type="checkbox" checked name="remember_me"> Remember me?</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="<?php echo url('/') ?>/uikit-3.1.6/js/uikit.min.js"></script>
    <script src="<?php echo url('/') ?>/uikit-3.1.6/js/uikit-icons.min.js"></script>
</body>
</html>