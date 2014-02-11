<?php
require_once( 'php/ap-config.php');
require_once( 'php/ap-session.php' );
require_once( 'php/ap-redirect.php' );
ap_user_session_start();
$authuser = ap_user_session_auth();
if ($authuser) {
  ap_redirect();
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title>Begin to use Ace+</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="DarkFlameMaster">    
  </head>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="assets/css/login_form.css">
  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/qtip2/2.1.1/jquery.qtip.min.css">
  </head>
  <body>
    <div class="container">
      <form id="form" method="post" action="thor.php" >
        <section class="loginBox row-fluid">
          <section class="span7 left">
            <h2>Login</h2>
            <div id="user-div" class="control-group">
              <p class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                <input type="text" id="user" name="username" placeholder="Username" />
              </p>
            </div>
            <div id="pass-div" class="control-group">
              <p class="input-prepend"><span class="add-on"><i class="icon-lock"></i></span>
                <input type="password" id="pass" name="password" placeholder="Password" />
              </p>
            </div>
            <section class="row-fluid">
              <section class="span8 lh30">
                <label class="checkbox">
                  <input type="checkbox" name="rememberme"> Remember me
                </label>
              </section>
              <section class="span1 offset8"><input id="submitbtn" type="button" value=" Login " class="btn btn-primary"></section>
            </section>
          </section>
          <section class="span5 right">
            <h2>Have no account?</h2>
            <section>
              <p>
                Assignment <br>
                Course <br>
                Excellence <br>
                + <br>
                PLUS <br>
              </p>
              <p><input type="button" value=" Sign Up " class="btn" onclick="window.location='reg.php'"></p>
            </section>
          </section>
        </section><!-- /loginBox -->
      </form>
    </div> <!-- /container -->
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/qtip2/2.1.1/jquery.qtip.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.md5.js"></script>
    <script src="js/login_form.js"></script>
  </body>
</html>

