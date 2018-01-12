<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>AdVenture - Search</title>
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
        <div class="navbar-header">
          <a class="navbar-brand" href="/"><img src="/pages/images/logo.png" alt="logo.png"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <?php if($this->validSession()): ?>
              <li><a href="/myprofile">My Profile & Ads</a></li>
              <li><a href="/messages">Messages</a></li>
              <li><a href="/post">Post Ad</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
    <div class="page-breadcrumb margin-b-60">
      <div class="container">
        <h4 class="margin-b-20">Search for Ads</h4>
        <form id="search-form" class="margin-b-30" role="form" data-toggle="validator">
          <div class="form-group">
            <input id="search" name="search" type="text" class="form-control" placeholder="Enter your search here to search ad titles and tags..." maxlength="50" <?php if(isset($_GET['q'])) echo 'value=' . $_GET['q']; ?> required>
          </div>
          <button type="submit" class="btn btn-lg btn-block btn-primary">Search</button>
        </form>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-sm-9">
          <div id="searchads" class="row">
            <h3>Enter a search query above!</h3>
          </div>
          <div class="space-30"></div>
            <nav id="navbuttons" aria-label="Page navigation" class="text-center margin-b-30">
                <ul class="pagination">
                    <li>
                        <a id="prev" aria-label="Previous">
                            <span aria-hidden="true">«</span>
                        </a>
                    </li>
                    <li>
                        <a id="next" aria-label="Next">
                            <span aria-hidden="true">»</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="col-sm-3 filter-row">
          <div class="sidebar-widget margin-b-40">
            <h4>Refine Your Search</h4>
            <form id="type-form" class="margin-b-30">
              <div class="form-group">
                <label for="sel1">Type</label>
                <select class="form-control" id="sel1" name="type">
                  <option id="choose">Please Choose a Type</option>
                  <option id="general">General</option>
                  <option id="vehicles">Vehicle</option>
                  <option id="property">Property</option>
                </select>
              </div>
            </form>
            <div id="general_filter" class="collapse">
              <div class="sidebar-widget margin-b-40">
                <form id="general-form" class="margin-b-30">
                  <h4>Price</h4>
                  <div class="form-group">
                    <input type="number" class="form-control" min="1" max="1000000000" id="gprice_min" name="price_min" placeholder="Minimum" data-validmin="#gprice_max">
                  </div>
                  <div class="form-group">
                    <input type="number" class="form-control" min="1" max="1000000000" id="gprice_max" name="price_max" placeholder="Maximum" data-validmax="#gprice_min">
                  </div>
                  <button type="submit" class="btn btn-lg btn-block btn-primary">Refine Your Search</button>
                  <label>(Leave fields blank to skip filters)</label>
                </form>
              </div>
            </div>
            <div id="vehicles_filter" class="collapse">
              <div class="sidebar-widget margin-b-40">
                <form id="vehicle-form" class="margin-b-30">
                  <h4>Price</h4>
                  <div class="form-group">
                    <input type="number" class="form-control" min="1" max="1000000000" id="vprice_min" name="price_min" placeholder="Minimum" data-validmin="#vprice_max">
                  </div>
                  <div class="form-group">
                    <input type="number" class="form-control" min="1" max="1000000000" id="vprice_max" name="price_max" placeholder="Maximum" data-validmax="#vprice_min">
                  </div>
                  <h4>Year</h4>
                  <div class="form-group">
                    <input type="number" class="form-control" min="1908" max="<?= intval(date('Y')) + 1; ?>" id="year_min" name="year_min" placeholder="Minimum" data-validmin="#year_max">
                  </div>
                  <div class="form-group">
                    <input type="number" class="form-control" min="1908" max="<?= intval(date('Y')) + 1; ?>" id="year_max" name="year_max" placeholder="Maximum" data-validmax="#year_min">
                  </div>
                  <h4>Kilometers</h4>
                  <div class="form-group">
                    <input type="number" class="form-control" min="0" max="2000000" id="km_min" name="km_min" placeholder="Minimum" data-validmin="#km_max">
                  </div>
                  <div class="form-group">
                    <input type="number" class="form-control" min="0" max="2000000" id="km_max" name="km_max" placeholder="Maximum" data-validmax="#km_min">
                  </div>
                  <h4>Transmission</h4>
                  <div class="form-group">
                    <select class="form-control" id="trans" name="trans">
                      <option id="automatic">Please Choose a Type</option>
                      <option id="automatic">Automatic</option>
                      <option id="manual">Manual</option>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-lg btn-block btn-primary">Refine Your Search</button>
                  <label>(Leave fields blank to skip filters)</label>
                </form>
              </div>
            </div>
            <div id="property_filter" class="collapse">
              <div class="sidebar-widget margin-b-40">
                <form id="property-form" class="margin-b-30">
                  <h4>Price</h4>
                  <div class="form-group">
                    <input type="number" class="form-control" min="1" max="1000000000" id="pprice_min" name="price_min" placeholder="Minimum" data-validmin="#pprice_max">
                  </div>
                  <div class="form-group">
                    <input type="number" class="form-control" min="1" max="1000000000" id="pprice_max" name="price_max" placeholder="Maximum" data-validmax="#pprice_min">
                  </div>
                  <h4>Type</h4>
                  <div class="form-group">
                    <select class="form-control" id="propType" name="propType">
                      <option id="choose">Please Choose a Type</option>
                      <option id="rent">For Rent</option>
                      <option id="sale">For Sale</option>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-lg btn-block btn-primary">Refine Your Search</button>
                  <label>(Leave fields blank to skip filters)</label>
                </form>
              </div>
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
    <script src="/pages/js/custom/search.js"></script>
  </body>
</html>