<?php
require_once('php/ap-config.php');
require_once( 'php/ap-session.php' );
ap_user_session_start();
$authuser = ap_user_session_auth();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>About Ace+</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
        padding-bottom: 40px;
        background: #444 url(assets/img/aceplus_cal_bg.jpg);
        background-attachment: fixed;
      }
      .acebox {
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
    }
    .modal-ace {
        width: 650px;
        margin-left: -375px;
    }
    </style>
    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
  </head>
  <body>
<?php include_once('common/header_menu.php'); ?>

    <div class="container acebox">
      <h3>DarkFlameMaster</h3>
      <p>I am the Dark Flame Master. Perish, enveloped in the flames of darkness!</p>
      <p>闇の炎に抱かれて消えろっ！</p>
      <hr>

      <footer>
        <p>&copy; DarkFlameMaster 2013</p>
      </footer>

    </div> <!-- /container -->

    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>