<?php
require_once('php/ap-config.php');
require_once('php/ap-db.php');
require_once('php/ap-assign.php');
require_once('php/ap-user-min.php');
require_once( 'php/ap-session.php' );
require_once( 'php/ap-redirect.php' );
ap_user_session_start();
$authuser = ap_user_session_auth();
$profile = ap_user_profile($authuser);
?>
<!DOCTYPE HTML>
<html>
<head>
        <title>Ace+ - Courses</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="assets/css/datepicker.css">
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
    .tokenfield input {
      width: 360px;
    }

    .datepicker{z-index:1140;}
    </style>
    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

</head>
<body>
    <?php include_once('common/header_menu.php'); ?>
    <div class="container acebox">

    <h2>Update your personal profile</h2>

    <form id = "Profile">
        <div class="span12">First Name: <input id="first" type="text" value=<?php echo "'".$profile['firstname']."'"; ?> ></div>
        <div class="span12">Last Name:  <input id="last" type="text" value=<?php echo "'".$profile['lastname']."'"; ?> ></div>
        <div class="span12">Gender:     <select id="gender" value="<?php echo $profile['gender']; ?>"><option value="M">Male</option><option value="F">Female</option></select></div>
        <div class="input-append input-prepend date span12" id="dp_knife" data-date="<?php gmdate('m/d/Y', $profile['birthday']); ?>">
            <span class="add-on">Birthday</span>
            <input class="span2" size="16" type="text" value="<?php echo gmdate('m/d/Y', $profile['birthday']); ?>" readonly>
            <span class="add-on"><i class="icon-calendar"></i></span>
        </div>
        <div class="span12">
        <br/> Write down your personal signature: <br/>
        <textarea id = "sigin" rows = "8" cols = "50"><?=$profile['sig']?></textarea>
        <br/>
        <button class="btn btn-info" id="submitBtn">Submit!</button>
        </div>
    </form>
</div>
    <div id="feedback"></div>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script type="text/javascript">
    $(function () {
        var BDate = <?php echo $profile['birthday']; ?>+0 |0;
        $('#dp_knife').datepicker().on('changeDate', function(ev){
        BDate = ev.date.getTime() /1000 |0;
        });
        var form = $("#Profile");
        form.submit(function(e){
        $.post("thor.php",
          {
            act: "updateProfile",
            ap_first: $('#first').val(),
            ap_last: $('#last').val(),
            ap_bd: BDate,
            ap_gender: $('#gender').val(),
            ap_sig: $('#sigin').val(),
            ap_user: <?php echo "'".$authuser."'";?>
          },
          function (result, status) {
            if (result.success) {
                //window.location.assign("/profile.php");
                window.location.assign("/");
            }else{
                alert("abort");
            }
          },
        "json");
        return false;
        });
        $('#submitBtn').click(function(){
            form.submit();
        });
    });
    </script>
</body>
</html>