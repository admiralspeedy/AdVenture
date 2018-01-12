<?php if(!defined('ALLOW_ACCESS')) exit(header('Location: /')); //Only allow access to this file through the router ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>AdVenture - Edit Your Ad</title>
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
    <div id="adid" style="display:none;"><?= $this->data['id'] ?></div>
    <div id="preloader"></div>
    <a href="#" class="scrollToTop"><i class="material-icons 48dp">keyboard_arrow_up</i></a>
    <div class="top-bar">
      <div class="container">
        <div class="row">
          <div class="col-sm-6 hidden-xs">
          </div>
          <div class="col-sm-6">
            <ul class="list-inline pull-right">
              <li><a href="/logout"><i class="material-icons">perm_identity</i> Log out</a></li>
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
            <li><a href="/myprofile">My Profile & Ads</a></li>
            <li><a href="/messages">Messages</a></li>
            <li><a href="/post">Post Ad</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="space-50"></div>
    <div class="container">
      <h3>Edit Your Ad</h3>
      <div id="ad-error" class="login-register-error"></div>
      <form id="adForm" class="boland-contact">
        <div class="row">
          <div class="col-sm-12">
            <div class="row">
              <div class="col-sm-12 margin-b-20">
                <div class="form-group">
                  <input type="text" id="adTitle" name="title" class="form-control" placeholder="Title" maxlength="50" value="<?= $this->data['title'] ?>" data-minlength="10" required>
                  <div class="help-block with-errors"></div>
                </div>
              </div>
              <div class="col-sm-12 margin-b-20">
                <div class="form-group">
                  <input type="number" id="adPrice" name="price" class="form-control" placeholder="Price ($)" min="1" max="1000000000" value="<?= $this->data['price'] ?>" required>
                  <div class="help-block with-errors"></div>
                </div>
              </div>
              <div class="col-sm-12 margin-b-20">
                <div class="form-group">
                  <textarea id="adDescription" name="description" class="form-control" rows="5" placeholder="Description" maxlength="500" required><?= $this->data['description'] ?></textarea>
                  <label id="adDescriptionCharCount"><?= strlen($this->data['description']) ?></label><label>/500</label>
                  <div class="help-block with-errors"></div>
                </div>
              </div>
              <div class="col-sm-12 margin-b-20">
                <div class="form-group">
                  <input id="postalcode" type="text" name="address" class="form-control" placeholder="Postal Code" value="<?= $this->data['postal'] ?>" data-postalcode required>
                  <div class="help-block with-errors"></div>
                </div>
              </div>
              <div class="col-sm-12 margin-b-20">
                <div class="form-group">
                  <input type="email" name="email" class="form-control" placeholder="Email Address (Displayed) (Optional)" maxlength="255" value="<?= $this->data['email'] ?>">
                  <div class="help-block with-errors"></div>
                </div>
              </div>
              <div class="col-sm-12 margin-b-20">
                <div class="form-group">
                  <input type="text" name="tags" class="form-control" placeholder="Tags Separated by Commas (Tag1,Tag2,Tag3) (Optional)" maxlength="50" value="<?= $this->data['tags'] ?>" data-tags>
                  <div class="help-block with-errors"></div>
                </div>
              </div>
              <div class="col-sm-12 margin-b-20">
                <div class="form-group">
                  Upload more images: <input type="file" id="images" name="images" accept=".png,.jpg,.jpeg" multiple>
                  <div class="help-block with-errors"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12 text-left">
            <button type="submit" name="submit" class="btn btn-lg btn-dark">Submit</button>
          </div>
        </div>
      </form>
    </div>
    <div class="space-80"></div>
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
    <script src="/pages/js/custom/generalform.js"></script>
    <script src="/pages/js/custom/editform.js"></script>
  </body>
</html>