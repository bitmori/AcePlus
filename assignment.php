<?php
require_once('php/ap-config.php');
require_once('php/ap-db.php');
require_once('php/ap-assign.php');
require_once('php/ap-course.php');
require_once( 'php/ap-session.php' );
require_once( 'php/ap-redirect.php' );
ap_user_session_start();
$authuser = ap_user_session_auth();
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="assets/css/datepicker.css">
    <link rel="stylesheet" href="assets/css/bootstrap-tokenfield.css">
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
<?php
	$assignment = ap_assign_detail($_GET['aid']);
    $courseinfo = ap_course_get_by_cid($assignment['cid'])[0];
?>
<div class="container acebox">
    <div class="page-header">
        <div class="pull-right form-inline">
            <div class="btn-group">
                <button class="btn btn-success" data-toggle="modal" data-target="#modalEdit" ><i class="icon-edit icon-white"></i> Edit</button>
                <button class="btn btn-success" id="forkBtn">Fork <i class="icon-leaf icon-white"></i></button>
            </div>
        </div>
        <h3><?=$assignment['title']?></h3>
     </div>
        <p><?php echo $courseinfo['dept'].' '.$courseinfo['number'].' - '.$courseinfo['name']; ?></p>
        <p>Begin: <?=gmdate("Y-m-d", $assignment['begin'])?>  End: <?=gmdate("Y-m-d", $assignment['end'])?></p>
        <p><?=$assignment['content']?></p>

    <div>
    	
    </div>
    <div id="modalEdit" class="modal modal-ace hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 id="myModalLabel">Edit Assignment</h3>
      </div>
      <div class="modal-body">
        <form id="newAssign" action="thor.php">
          <div class="row-fluid">
            <div id="new-title-div" class="control-group span3">
              <div class="input-prepend"><span class="add-on"><i class="icon-plus"></i></span>
                <input type="text" style="width:560px;" id="newtitle" name="newtitle" placeholder="Title" />
              </div>
            </div>
          </div>
          <select id="typeselect" style="width:120px;">
            <option>Homework</option>
            <option>Lab</option>
            <option>Project</option>
            <option>Midterm</option>
            <option>Final</option>
            <option>Misc</option>
          </select>
          <div class="input-append input-prepend date" id="dp_knife_begin" data-date="<?php echo date('m/d/Y'); ?>">
            <span class="add-on">Begin</span>
            <input class="span2" size="16" type="text" value="<?php echo date('m/d/Y'); ?>" readonly>
            <span class="add-on"><i class="icon-calendar"></i></span>
          </div>
          <div class="input-append input-prepend date" id="dp_knife_end" data-date="<?php echo date('m/d/Y'); ?>">
            <span class="add-on">End</span>
            <input class="span2" size="16" type="text" value="<?php echo date('m/d/Y'); ?>" readonly>
            <span class="add-on"><i class="icon-calendar"></i></span>
          </div>
            <textarea id="knifeText" style="width:600px; height:220px; resize:none;" placeholder="Enter text ..."><?=$assignment['content']?></textarea>
            <!-- <textarea name="content" data-provide="markdown" rows="10"></textarea> -->
        </form>

      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true" id="btnCancel">Cancel</button>
        <button class="btn btn-primary" id="btnCreate">Update</button>
      </div>
    </div><!-- Eo Modal NEW -->
</div>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script type="text/javascript">
    function do_fork(){
        $.post("thor.php",
          {
            act: "fork",
            ap_aid: <?php echo "'".$_GET['aid']."'";?>,
            ap_user: <?php echo "'".$authuser."'";?>
          },
          function (result, status) {
            if (result.success){
                window.location.assign("/assignment.php?aid="+result.aid);
            }
          },
        "json");
    }
    $(function () {
        $("#newtitle").val(<?php echo "'".$assignment['title']."'"; ?>);
        $("#typeselect").val(<?php echo "'".$assignment['tag']."'"; ?>);
        var beginDate = <?=$assignment['begin']?> |0;
        var endDate = <?=$assignment['end']?> |0;
        $('#dp_knife_begin').datepicker().on('changeDate', function(ev){
            beginDate = ev.date.getTime() /1000 |0;
        });
        $('#dp_knife_end').datepicker().on('changeDate', function(ev){
            endDate = ev.date.getTime() /1000 |0;
        });
        var form = $("#newAssign");
        form.submit(function(e){
        
        $.post(form.attr("action"),
          {
            act: "updateAssign",
            ap_aid: <?php echo $_GET['aid']; ?>,
            ap_title: $('#newtitle').val(),
            ap_begin: beginDate,
            ap_end: endDate,
            ap_content: $('#knifeText').val(),
            ap_tag: $('#typeselect').val(),
            // ap_cid: <?php echo "'".$cid."'";?>,
            // ap_user: <?php echo "'".$authuser."'";?>,
          },
          function (result, status) {
            if (result.success) {
                window.location.reload();
            };
          },
        "json");
        return false;
      });
      $('#btnCreate').click(function(){
        form.submit();
      });

        $("#forkBtn").click(function () {
            do_fork();            
        });
    });
    </script>
</body>
</html>