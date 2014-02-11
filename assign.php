<?php
require_once('php/ap-config.php');
require_once('php/ap-db.php');
require_once('php/ap-assign.php');
require_once('php/ap-course.php');
require_once( 'php/ap-session.php' );
require_once( 'php/ap-redirect.php' );
ap_user_session_start();
$authuser = ap_user_session_auth();
$cid = (isset($_GET['cid']))?html_entity_decode(urldecode($_GET['cid'])):"";
$uid = (isset($_GET['uid']))?html_entity_decode(urldecode($_GET['uid'])):"";
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Ace+ - Assignment</title>
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

    <div class="container acebox">
        <div class="page-header">
        <div class="pull-right form-inline">
        <?php if ($cid):?>
        <?php if ($authuser):?>
            <div class="btn-group">
                <button class="btn btn-success" data-toggle="modal" data-target="#modalNew" ><i class="icon-plus icon-white"></i> New</button>
            </div>
        <?php else: ?>
            <p>You need to login first to create new assignment.</p>
        <?php endif; ?>
        <?php endif; ?>
<!-- 
            <div class="btn-group">
                <button class="btn btn-warning" data-calendar-view="year">Year</button>
                <button class="btn btn-warning active" data-calendar-view="month">Month</button>
                <button class="btn btn-warning" data-calendar-view="week">Week</button>
                <button class="btn btn-warning" data-calendar-view="day">Day</button>
            </div> -->
        </div>
    <?php if ($cid):?>
      <h3>All assignments of <?php
      $courseinfo = ap_course_get_by_cid($cid)[0];
      echo $courseinfo['dept'].' '.$courseinfo['number'].' - '.$courseinfo['name'];
      ?></h3>
    <?php else: ?>
      <h3>Your latest assignments</h3>
    <?php endif; ?>
	    </div>
    
      <div>
	<table class="table table-striped">
		<tr><th>ID</th><th>Title</th><th>Begin</th><th>End</th><th>Type</th><th>Operation</th></tr>
	<?php
    if ($cid) {
      $assign_list = ap_assign_of_course($cid);
    } else {
      if ($authuser) {
        $assign_list = ap_assign_of_user($authuser);
      } else {
        $assign_list = ap_assign_list();
      }
    }
    $assign_count = count($assign_list);
    for ($i=0; $i < $assign_count; $i++) { 
      $tstr = '<tr><td>'.$assign_list[$i]['id'].'</td>';
      $tstr.= '<td><a href="/assignment.php?aid='. $assign_list[$i]['id'] .'">'.$assign_list[$i]['title'].'</a></td>';
      $tstr.= '<td>'.gmdate("Y-m-d", $assign_list[$i]['begin']).'</td>';
      $tstr.= '<td>'.gmdate("Y-m-d", $assign_list[$i]['end']).'</td>';
      //$tstr.= '<td>'.$assign_list[$i]['content'].'</td>';
      $tstr.= '<td>'.$assign_list[$i]['tag'].'</td>';
      $tstr.= '<td><a onclick="del_click('.$assign_list[$i]['id'].')">DEL</a><a onclick="do_fork('.$assign_list[$i]['id'].')">FORK</a></td></tr>';
      echo $tstr;
    }
	?>
	</table>
      </div>
      <hr />
      <footer>
        <p>&copy; DarkFlameMaster 2013</p>
      </footer>
	<!-- Modal NEW -->
  <?php if ($cid):?>
    <div id="modalNew" class="modal modal-ace hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 id="myModalLabel">New Assignment for <?php echo $courseinfo['dept'].' '.$courseinfo['number'];?></h3>
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
            <textarea id="knifeText" style="width:600px; height:220px; resize:none;" placeholder="Enter text ..."></textarea>
            <!-- <textarea name="content" data-provide="markdown" rows="10"></textarea> -->
        </form>

      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true" id="btnCancel">Cancel</button>
        <button class="btn btn-primary" id="btnCreate">Create</button>
      </div>
    </div><!-- Eo Modal NEW -->
<?php endif; ?>
    </div> <!-- /container -->

    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/bootstrap-tokenfield.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap-editable/js/bootstrap-editable.min.js"></script>

    <script>
    function del_click(id){
        $.post("thor.php",
          {
            act: "deleteAssign",
            ap_aid: id
          },
          function (result, status) {
            switch (result.code){
              case 200:
                console.log('assign is deleted');
                window.location.assign("/assign.php");
              break;
              case 401:
                alert('encountered an error, please check the error_log');
              break;
            }
          },
        "json");
    }

    function do_fork(id){
        $.post("thor.php",
          {
            act: "fork",
            ap_aid: id,
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
      var beginDate = new Date().getTime() /1000 |0;
      var endDate = new Date().getTime() /1000 |0;
      $('#dp_knife_begin').datepicker().on('changeDate', function(ev){
        beginDate = ev.date.getTime() /1000 |0;
      });
      $('#dp_knife_end').datepicker().on('changeDate', function(ev){
        endDate = ev.date.getTime() /1000 |0;
        //console.log(endDate);
      });
      var form = $("#newAssign");
      form.submit(function(e){
        
        $.post(form.attr("action"),
          {
            act: "createAssign",
            ap_title: $('#newtitle').val(),
            ap_begin: beginDate,
            ap_end: endDate,
            ap_content: $('#knifeText').val(),
            ap_tag: $('#typeselect').val(),
            ap_cid: <?php echo "'".$cid."'";?>,
            ap_user: <?php echo "'".$authuser."'";?>,
          },
          function (result, status) {
            switch (result.code){
              case 200:
                window.location.assign("/assign.php");
              break;
              case 401:
                alert('encountered an error, please check the error_log');
              break;
            }
          },
        "json");
        return false;
      });
      $('#btnCreate').click(function(){
        form.submit();
      });
    });
    </script>
  </body>
</html>