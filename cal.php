<?php
require_once('php/ap-config.php');
require_once( 'php/ap-session.php' );
require_once( 'php/ap-redirect.php' );
ap_user_session_start();
$authuser = ap_user_session_auth();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ace+ - Schedule</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/calendar.min.css">
    <link rel="stylesheet" href="assets/css/datepicker.css">
    <link rel="stylesheet" href="assets/css/summernote.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap-responsive.css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.1/css/font-awesome.min.css">
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
        /* new custom width */
        width: 650px;
        /* must be half of the width, minus scrollbar on the left (30px) */
        margin-left: -375px;
    }
    
    .cal-year-box .cal-cell {
      position: relative;
      width:25%;
      min-height: 60px !important;
      margin-left: 0px !important;
      padding-left: 15px;
      padding-right: 15px;
    }
   .cal-year-box #cal-day-box {
     margin-right: -14px !important;
   }
   
   .datepicker{z-index:1140;}
    </style>
</head>
<body>
<?php include_once('common/header_menu.php'); ?>
<div class="container acebox">

    <div class="page-header">
        <div class="pull-right form-inline">

            <div class="btn-group">
                <button class="btn btn-primary" data-calendar-nav="prev"><i class="icon-chevron-left icon-white"></i> Prev</button>
                <button class="btn btn-info" data-calendar-nav="today">Today</button>
                <button class="btn btn-primary" data-calendar-nav="next">Next <i class="icon-chevron-right icon-white"></i></button>
            </div>
            <div class="btn-group">
                <button class="btn btn-warning" data-calendar-view="year">Year</button>
                <button class="btn btn-warning active" data-calendar-view="month">Month</button>
                <button class="btn btn-warning" data-calendar-view="week">Week</button>
                <button class="btn btn-warning" data-calendar-view="day">Day</button>
            </div>
        </div>

        <h3></h3>
        <small>Do it now.</small>
    </div>

    <div class="row">
        <div class="span9">
            <div id="calendar"></div>
        </div>
        <div class="span3">
<!--             <div class="row-fluid">
                <label class="checkbox">
                    <input type="checkbox" value="#events-modal" id="events-in-modal"> Open events in modal window
                </label>
            </div> -->

            <h4>Recent events</h4>
            <small>Things due recently</small>
            <ul id="eventlist" class="nav nav-list"></ul>
        </div>
    </div>

    <div class="clearfix"></div>
    <br><br>
 
    <!-- Modal NEW -->
<!--     <div id="modalNew" class="modal modal-ace hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 id="myModalLabel">Create a new Assignment</h3>
      </div>
      <div class="modal-body">
        <form id="newAssign">

            <div id="new-title-div" class="control-group">
              <div class="input-prepend"><span class="add-on"><i class="icon-plus"></i></span>
                <input type="text" id="newtitle" name="newtitle" placeholder="Title" />
              </div>
            </div>


        <div class="input-append date" id="dp_knife" data-date="<?php echo date('m/d/Y'); ?>">
        <input class="span2" size="16" type="text" value="<?php echo date('m/d/Y'); ?>" readonly>
        <span class="add-on"><i class="icon-calendar"></i></span>
        </div>
            <textarea id="knifeText" style="width:600px; height:220px;" placeholder="Enter text ..."></textarea>
        </form>

      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary">Create</button>
      </div>
    </div> --><!-- Eo Modal NEW -->

    <!-- Modal FORK -->
<!--     <div id="modalFork" class="modal modal-ace hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 id="myModalLabel">Fork an Assignment</h3>
      </div>
      <div class="modal-body">
        <form id="forkAssign">

            <div id="fork-title-div" class="control-group">
              <p class="input-prepend"><span class="add-on"><i class="icon-leaf"></i></span>
                <input type="text" id="forktitle" style="width:576px;" name="forktitle" placeholder="Title" />
              </p>
            </div>
          <div class="input-append date" id="dp_fork" data-date="<?php echo date('m/d/Y'); ?>">
          <input class="span2" size="16" type="text" value="<?php echo date('m/d/Y'); ?>" readonly>
          <span class="add-on"><i class="icon-calendar"></i></span>
          </div>
            <textarea id="forkText" style="width:600px; height:220px;" placeholder="Enter text ..."></textarea>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary">Fork</button>
      </div>
    </div> --><!-- Eo Modal FORK -->
</div>

    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>
    <script src="js/calendar.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/summernote.min.js"></script>
    <script src="js/markdown.js"></script>
    <script src="js/markdown-editor.js"></script>
    <script src="js/cal_form.js"></script>

</body>
</html>