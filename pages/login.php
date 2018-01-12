<?php if(!defined('ALLOW_ACCESS')) exit(header('Location: /')); //Only allow access to this file through the router ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>AdVenture - Log in</title>
    <!-- Common plugins -->
    <link href="/pages/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/pages/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/pages/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="/pages/plugins/owl-carousel/assets/owl.carousel.css" rel="stylesheet">
    <link href="/pages/plugins/owl-carousel/assets/owl.theme.default.css" rel="stylesheet">
    <link href="/pages/plugins/icheck/skins/minimal/blue.css" rel="stylesheet">
    <!-- Template CSS -->
    <link href="/pages/css/style.css" rel="stylesheet">
    <!-- AdVenture CSS -->
    <link href="/pages/css/adventure.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div id="preloader"></div>
    <a href="#" class="scrollToTop"><i class="material-icons 48dp">keyboard_arrow_up</i></a>
    <div class="top-bar">
      <div class="container">
        <div class="row">
          <div class="col-sm-6 hidden-xs">
          </div>
          <div class="col-sm-6">
            <ul class="list-inline pull-right">
              <li><a href="/register"><i class="material-icons">perm_identity</i> Register</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <nav class="navbar navbar-default navbar-static-top yamm sticky-header">
      <div class="container">
        <div class="pull-right">
          <ul class="right-icon-nav nav navbar-nav list-inline">
            <li class="dropdown">
              <a href="/search" class="dropdown-toggle"><i class="material-icons">search</i> Search</a>
            </li>
          </ul>
        </div>
        <div class="navbar-header">
          <a class="navbar-brand" href="/"><img src="/pages/images/logo.png" alt=""></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="/">Home</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="page-breadcrumb margin-b-60">
      <div class="container text-center">
        <div class="row">
          <div class="col-sm-6 col-sm-offset-3">
            <h4 class="margin-b-20">Log In</h4>
            <p class="text-white">Enter your email and password below to log in.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-sm-6 col-sm-offset-3 margin-b-60">
          <div class="login-register-box">
            <div id="login-error" class="login-register-error"></div>
            <form id="login-form" class="margin-b-30" role="form" data-toggle="validator">
              <div class="form-group">
                <i class="material-icons icon">email</i>
                <label class="sr-only" for="email">Email</label>
                <input id="email" name="email" type="email" class="form-control login-email" placeholder="Email" maxlength="255" required>
                <div class="help-block with-errors"></div>
              </div>
              <div class="form-group">
                <i class="material-icons icon">lock</i>
                <label class="sr-only" for="pass">Password</label>
                <input id="pass" name="pass" type="password" class="form-control login-password" placeholder="Password" maxlength="25" required>
                <div class="help-block with-errors"></div>
              </div>
              <button type="submit" class="btn btn-lg btn-block btn-primary">Log in</button>
            </form>
            <div align="center">
              New user? <a href="/register"><b>Register here!</b></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Common plugins -->
    <script src="/pages/plugins/jquery/dist/jquery.min.js"></script>
    <script src="/pages/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/pages/plugins/pace/pace.min.js"></script>
    <script src="/pages/plugins/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
    <script src="/pages/plugins/owl-carousel/owl.carousel.min.js"></script>
    <script src="/pages/plugins/sticky/jquery.sticky.js"></script>
    <script src="/pages/plugins/icheck/icheck.min.js"></script>
    <script src="/pages/js/jquery.stellar.min.js"></script>
    <script src="/pages/js/boland.custom.js"></script>
    <!-- Custom JS -->
    <script src="/pages/plugins/validator/validator.min.js"></script>
    <script src="/pages/js/custom/login.js"></script>
  </body>
</html>