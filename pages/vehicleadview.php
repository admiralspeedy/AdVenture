<?php if(!defined('ALLOW_ACCESS')) exit(header('Location: /')); //Only allow access to this file through the router ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>AdVenture - <?= $this->data['title'] ?></title>
    <!-- Common plugins -->
    <link href="/pages/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/pages/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/pages/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="/pages/plugins/owl-carousel/assets/owl.carousel.css" rel="stylesheet">
    <link href="/pages/plugins/owl-carousel/assets/owl.theme.default.css" rel="stylesheet">
    <link href="/pages/plugins/icheck/skins/minimal/blue.css" rel="stylesheet">
    <!-- Master Slider -->
    <link href="/pages/plugins/masterslider/style/masterslider.css" rel="stylesheet">
    <link href="/pages/plugins/masterslider/skins/default/style.css" rel='stylesheet'>
    <link href="/pages/css/ms-showcase2.css" rel="stylesheet">
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
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.11';
      fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    </script>
    <div id="preloader"></div>
    <a href="#" class="scrollToTop"><i class="material-icons 48dp">keyboard_arrow_up</i></a>
    <div class="top-bar">
      <div class="container">
        <div class="row">
          <div class="col-sm-6 hidden-xs">
          </div>
          <div class="col-sm-6">
            <ul class="list-inline pull-right">
              <?php if(!$loggedIn): ?>
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
          <a class="navbar-brand" href="/"><img src="/pages/images/logo.png" alt=""></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="/">Home</a></li>
            <?php if($loggedIn): ?>
              <li><a href="/myprofile">My Profile & Ads</a></li>
              <li><a href="/messages">Messages</a></li>
              <li><a href="/post">Post Ad</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
    <div class="space-60"></div>
    <div class="container">
      <div class="row">
        <div class="col-sm-7 margin-b-40">
          <div class="ms-showcase2-template ms-showcase2-vertical">
            <div class="master-slider ms-skin-default" id="masterslider">
              <?php foreach ($this->data['image'] as $image): ?>
              <div class="ms-slide">
                <img src="plugins/masterslider/style/blank.gif" data-src="/uploads/<?= $image ?>"/>
                <img class="ms-thumb" src="/uploads/<?= $image ?>" alt="thumb" />
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <div class="col-sm-5">
          <div class="item-description">
            <h2><?= $this->data['title'] ?></h2>
            <h4 id="date">Posted: <?= date('F jS, Y', $this->data['time']); ?></h4>
            <div class="space-20"></div>
            <span class="priceLabel">$<?= $this->data['price'] ?>.00</span>
            <div class="space-20"></div>
            <p id="adDescription" class="adDescriptionView">
              <?= $this->data['description'] ?>
            </p>
            <div class="space-20"></div>
            <!--Google Maps-->
            <h4>Location</h4>
            <div class="google-map-container margin-b-60">
              <div id="googlemaps" style="width: 100%;height: 300px;"></div>
            </div>
            <!--/Google Maps-->
          </div>
        </div>
      </div>
      <hr>
      <div class="space-20"></div>
      <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
          <div>
            <ul class="tabs-nav list-inline text-center" role="tablist">
              <li role="presentation" class="active"><a href="#vehicle" aria-controls="vehicle" role="tab" data-toggle="tab">Vehicle Information</a></li>
              <li role="presentation"><a href="#info" aria-controls="info" role="tab" data-toggle="tab">Contact Information</a></li>
            </ul>
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="vehicle">
                <table class="table table-striped">
                  <tbody>
                    <tr>
                      <td><strong>Make & Model:</strong></td>
                      <td><?= $this->data['make'] ?></td>
                    </tr>
                    <tr>
                      <td><strong>Year:</strong></td>
                      <td><?= $this->data['year'] ?></td>
                    </tr>
                    <tr>
                      <td><strong>Kilometers:</strong></td>
                      <td><?= $this->data['kilometers'] ?></td>
                    </tr>
                    <tr>
                      <td><strong>Transmission:</strong></td>
                      <td><?= $this->data['transmission'] ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div role="tabpanel" class="tab-pane" id="info">
                <table class="table table-striped">
                  <tbody>
                    <tr>
                      <td><strong>Poster:</strong></td>
                      <td><?= $posterName ?></td>
                    </tr>
                    <?php if(strlen($this->data['email'])): ?>
                      <tr>
                        <td><strong>Email Address:</strong></td>
                        <td><?= $this->data['email'] ?></td>
                      </tr>
                    <?php endif; ?>
                    <tr>
                      <td><strong>Postal Code:</strong></td>
                      <td><?= $this->data['postal'] ?></td>
                    </tr>
                  </tbody>
                </table>
                <a href="/profile?id=<?= $this->data['userid'] ?>" class="btn btn-dark btn-xl btn-block">VIEW SELLER'S PROFILE TO SEND A MESSAGE</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="space-60"></div>
    <?php if(count($tags)): ?>
      <div class="container">
        <h4 class="margin-b-20">Tags</h4>
        <?php for($i = 0; $i < count($tags); $i++): ?>
          <a href="/search?q=<?= $tags[$i] ?>"><?= $tags[$i] ?></a><?php if($i + 1 < count($tags)) echo ','; ?>
        <?php endfor; ?>
      </div>
      <div class="space-30"></div>
    <?php endif; ?>
    <div class="container">
      <h4 class="margin-b-20">Share</h4>
      <a class="fa fa-facebook-square fa-3x" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fadventure.ddns%2Fad%3F%3D<?= $this->data['id'] ?>&amp;src=sdkpreparse"></a>
      <a href="https://twitter.com/intent/tweet?text=Check%20out%20this%20item%20for%20sale%20on%20AdVenture%3A%20&url=http%3A%2F%2Fadventure.ddns.net%2Fad%3Fid%3D<?= $this->data['id'] ?>" target="_blank" class="fa fa-twitter-square fa-3x"></a>
    </div>
    <div class="space-60"></div>
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
    <script src="/pages/plugins/masterslider/masterslider.min.js"></script>
    <script src="https://maps.google.com/maps/api/js?key=AIzaSyB68Lu051Yc0dFGcquz06xc5Iw31q2qkzA"></script>
    <!-- Custom JS -->
    <script src="/pages/js/jquery.gmaps.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function () {
          var slider = new MasterSlider();
          slider.control('scrollbar', {dir: 'h'});
          slider.control('thumblist', {autohide: false, dir: 'v', arrows: false, align: 'left', width: 127, height: 84, margin: 5, space: 5, hideUnder: 300});
          slider.setup('masterslider', {
              width: 540,
              height: 586,
              space: 5,
              fillMode: "fit"
          });
      });

      (function ($) {
          $(document).ready(function () {

              $('#googlemaps').gMap({
                  maptype: 'ROADMAP',
                  scrollwheel: false,
                  zoom: 13,
                  markers: [
                      {
                          address: '<?= $this->data['postal'] ?>', // Your Adress Here
                          html: '<strong>Postal Code</strong><br><?= $this->data['postal'] ?>',
                          popup: false,
                          icon: {
                              image: "/pages/images/marker.png",
                              iconsize: [28, 40],
                              iconanchor: [20, 40]
                          }
                      }
                  ]
              });
          });
      })(this.jQuery);
    </script>
  </body>
</html>