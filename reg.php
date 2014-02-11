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
    <title>Sign up to Ace+</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="DarkFlameMaster">    
  </head>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-responsive.css">
  <link rel="stylesheet" type="text/css" href="assets/css/reg_form.css">
  <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="assets/css/social-buttons.css">
  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/qtip2/2.1.1/jquery.qtip.min.css">
  </head>
<body>
<div class="container">
  <form id="form" method="post" action="thor.php" >
    <section class="loginBox">
        <h2 class="up">Please sign up</h2>
        <div id="mail-div" class="control-group">
          <p class="input-prepend"><span class="add-on"><i class="icon-envelope"></i></span>
            <input type="email" id="email" name="email" placeholder="E-mail Address" />
          </p>
        </div>

        <div id="user-div" class="control-group">
          <p class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
            <input type="text" id="user" name="username" placeholder="Username" />
          </p>
        </div>

        <div id="pass-div" class="control-group">
          <p class="input-prepend"><span class="add-on"><i class="icon-asterisk"></i></span>
            <input type="password" id="pass" name="password" placeholder="Password" />
          </p>
        </div>
        <div class="control-group">
          <label class="checkbox">
            <input id="term" type="checkbox" name="rememberme"> I agree to the <a href="#myModal" data-toggle="modal">Terms of service</a>
          </label>
        </div>
        <div class="control-group row-fluid">
          <button class="btn btn-primary" style="width:106px;" id="submitbtn" type="button" value=" Sign Up "><i class="icon-plus"></i>  |  Sign up</button>
          <!-- <button class="btn btn-facebook offset1"><i class="icon-facebook"></i> | Facebook</button> -->
        </div>

    </section><!-- /loginBox -->
  </form>

  <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Terms of Service</h3>
    </div>
    <div class="modal-body">
      <p>Here is the terms of service of ACE+</p>
    </div>
    <div class="modal-footer">
      <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"> <i class="icon-exclamation-sign icon-white"></i> Acknowledge </button>
      <!-- <button class="btn btn-primary">Save changes</button> -->
    </div>
  </div>
</div>
  <script src="http://code.jquery.com/jquery.js"></script>
  <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/qtip2/2.1.1/jquery.qtip.min.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="js/jquery.md5.js"></script>
  <script src="js/reg_form.js"></script>
</body>
</html>