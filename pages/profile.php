<?php if(!defined('ALLOW_ACCESS')) exit(header('Location: /')); //Only allow access to this file through the router ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>AdVenture - <?= $this->data['username'] ?></title>
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
    <div id="userid" style="display:none;"><?= $id ?></div>
    <div id="rating" style="display:none;"><?= $rating ?></div>
    <div id="preloader"></div>
    <a href="#" class="scrollToTop"><i class="material-icons 48dp">keyboard_arrow_up</i></a>
    <div class="top-bar">
      <div class="container">
        <div class="row">
          <div class="col-sm-6 hidden-xs">
          </div>
          <div class="col-sm-6">
            <ul class="list-inline pull-right">
              <?php if(!$this->validSession()): ?>
                <li><a href="/login"><i class="material-icons">perm_identity</i> Log in</a></li>
                <li><a href="/register"><i class="material-icons">perm_identity</i> Register</a></li>
              <?php else: ?>
                <li><a href="/logout"><i class="material-icons">perm_identity</i> Log out</a></li>
              <?php endif; ?>
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
          <a class="navbar-brand" href="/"><img src="/pages/images/logo.png" alt="logo.png"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="/">Home</a></li>
            <?php if($this->validSession()): ?>
              <li><a href="/myprofile">My Profile & Ads</a></li>
              <li><a href="/messages">Messages</a></li>
              <li><a href="/post">Post Ad</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
    <div class="page-breadcrumb margin-b-30">
      <div align="center" class="container">
        <h4 class="margin-b-5"><?= $this->data['username'] ?>'s Profile & Advertisements</h4>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-sm-9">
          <div id="userads" class="row">
          </div>
        </div>
        <div class="col-sm-3 filter-row">
          <?php if($this->validSession()): ?>
            <div class="sidebar-widget margin-b-40">
              <h4>Actions</h4>
              <button id="messageBtn" align="center" type="button" class="btn btn-primary" style="width:100%;">Message</button>
              <?php if($this->validSession() && $_SESSION['rank'] > 0): ?>
                <div class="space-10"></div>
                <button id="banBtn" align="center" type="button" class="btn btn-primary" style="width:100%;">Ban</button>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          <div class="sidebar-widget margin-b-40">
            <h4>Rating</h4>
            <div align="center" class="well well-sm pull-left" style="width:100%;">
              <span class="rating">
              <i id="star1" class="fa fa-star"></i>
              <i id="star2" class="fa fa-star"></i>
              <i id="star3" class="fa fa-star"></i>
              <i id="star4" class="fa fa-star"></i>
              <i id="star5" class="fa fa-star"></i>
              </span>
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
    <script src="/pages/js/custom/resetRating.js"></script>
    <?php if($this->validSession() && $_SESSION['rank'] > 0): ?>
      <script src="/pages/js/custom/profileadmin.js"></script>
    <?php else: ?>
      <script src="/pages/js/custom/profile.js"></script>
    <?php endif; ?>
    <?php if($this->validSession()): ?><script src="/pages/js/custom/rating.js"></script><?php endif; ?>
  </body>
</html>