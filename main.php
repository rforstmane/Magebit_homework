<?php include('connect.php'); ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8"/>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700" rel="stylesheet">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Magebit homework</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="style.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>

<body>
<div class="wrapper">
    <div class="auth-form">
        <div class="auth-form__column">
            <h2 class="auth-form__title">
                Don't have an account?
            </h2>
            <p class="auth-form__text">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                labore et dolore magna aliqua.
            </p>
            <a href="#signup-form" class="button" id="js-form-left">SIGN UP</a>
        </div>
        <div class="auth-form__column">
            <div class="auth-form__title">
                <h2>Have an account?</h2>
            </div>
            <div class="auth-form__text">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            </div>
            <a href="#login-form" class="button" id="js-form-right">LOGIN</a>
        </div>
        <div class="auth-form__modal auth-form__modal--login ">
            <?php include('signup.php'); ?>
            <?php include('login.php'); ?>


        </div>
    </div>

    <script src="main.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</body>

</html>
