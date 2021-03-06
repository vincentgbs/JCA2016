<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Management Module</title>

    <link rel="stylesheet" type="text/css" href="<?php echo DOMAIN; ?>/css/library/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo DOMAIN; ?>/css/library/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo DOMAIN; ?>/css/style.css">
    <script type="text/javascript" src="<?php echo DOMAIN; ?>/js/library/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="<?php echo DOMAIN; ?>/js/library/sha256.js"></script>
    <script type="text/javascript" src="<?php echo DOMAIN; ?>/js/library/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php echo DOMAIN; ?>/js/vanilla.js"></script>
</head>

<body class="container">
    <div class="noscript_lightbox">
        <div class="text-center bg-primary lead">Your browser does not support JavaScript!</div>
    </div>
    <div id="flash_message"></div>

    <div class="row col-md-12">
        <a href="/user/home"><button class="btn btn-default">Home</button></a>
        <a href="/user/update"><button class="btn btn-default">Update Account</button></a>
        <a href="/user/register"><button class="btn btn-default">Register</button></a>
        <a href="/user/login"><button class="btn btn-default">Login</button></a>
        <a href="/user/logout"><button class="btn btn-default">Logout</button></a>
        <a href="/user/reset"><button class="btn btn-default">Reset Password</button></a>
        <a href="/user/deactivate"><button class="btn btn-default">Deactivate</button></a>
    </div> <!-- </div class="row col-md-12"> -->
