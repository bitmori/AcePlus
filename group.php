<?php
require_once('php/ap-config.php');
require_once('php/ap-db.php');
require_once( 'php/ap-session.php' );
require_once( 'php/ap-redirect.php' );
require_once( 'php/ap-assign.php' );
require_once( 'php/ap-course.php' );
require_once( 'php/ap-user-min.php' );
ap_user_session_start();
$authuser = ap_user_session_auth();
$cid = (isset($_GET['cid']))?html_entity_decode(urldecode($_GET['cid'])):"";
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>Group</title>
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
        <h2>
            <h3>People in <?php
      $courseinfo = ap_course_get_by_cid($cid)[0];
      echo $courseinfo['dept'].' '.$courseinfo['number'].' - '.$courseinfo['name'];
      ?></h3>
        </h2>
        <table class="table table-striped">
        <?php
        $list = ap_course_get_group($cid);
        $count = count($list);
        if ($count) {
            echo "<tr><th>Username</th><th>First Name</th><th>Last Name</th><th>E-Mail</th><th>Signature</th></tr>";
        }
        for ($i=0; $i < $count; $i++) {
            echo "<tr><td>" . $list[$i]['username'] . "</td><td>" . $list[$i]['firstname'] . "</td><td>". $list[$i]['lastname'] ."</td><td>". $list[$i]['email'] ."</td><td>". $list[$i]['sig'] . "</td></tr>";
        }
        ?>
        </table>
<?php if (!ap_user_in_group($authuser, $cid)): ?>
    <hr>
    <h4>I want to join this study group as well!</h4>
    <button id = "joinButton">Join this group</button>
<?php else: ?>
    <hr>
    <h4>You have already joined in this group.</h4>
<?php endif; ?>
    </div>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>
    <script>
    $(function() {

        function submit (username, cid) {
            $.post("thor.php",
              {
                act: "join",
                ap_user: username,
                ap_cid: cid
              },
              function (result, status) {
                if (result.success) {
                    window.location.reload();
                };
              },
            "json");
        }

        $("#joinButton").click(function() {
            submit(<?php echo "'".$authuser."'";?>, <?=$cid?>);
        });
    });
    </script>
</body>
</html>
