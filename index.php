<?php
require_once('php/ap-config.php');
require_once( 'php/ap-session.php' );
ap_user_session_start();
$authuser = ap_user_session_auth();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Ace+</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
        padding-bottom: 40px;
      }
    </style>
    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
  </head>
  <body>

<?php include_once('common/header_menu.php'); ?>

    <div class="container">

      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="hero-unit">
        <h1>Welcome to ACE+</h1>
        <p>This is the home page for <em>Assignment Course Excellence Plus</em></p>
        <p><a href="#" class="btn btn-info btn-large">Learn more &raquo;</a></p>
        <p><a href="login.php" class="btn btn-primary btn-large">Try it NOW &raquo;</a></p>
      </div>

      <div class="row">
        <div class="span4">
          <h2><img src="/assets/img/index/img_mc.png" alt="Manage Courses">Fork Assignments</h2>
<!--           <p> Ace+ provides all students to contribute to a trunk copy of calendar and each can "fork" from it and make personal customizations.</p>
          <p><a class="btn" href="#">View details &raquo;</a></p> -->
          <p>Our website is quite useful because students take different courses that each has different assignments due dates and it is hard for students to have a clear overview of course progress. Keeping track of when each assignment will due in a weekly and monthly time frame, in terms of courses and assignment types, will facilitate students to make a short run study plan. </p>
        </div>
        <div class="span4">
          <h2><img src="/assets/img/index/img_gc.png" alt="Google Calendar">Calendar</h2>
<!--           <p>Integration with Google Calendar to get sync with all your other schedules and get reminders and notifications.</p>
          <p><a class="btn" href="#">View details &raquo;</a></p> -->
          <p>When students are taking several courses every semester and going over each course website to find every due date, it is hard for students to enter those information at once. Most course management applications, either web based or mobile based, failed to attract students to use them for a long time. Students can "fork" from existed copy and have those due dates and assignments info in their own calendar.</p>
       </div>
        <div class="span4">
          <h2><img src="/assets/img/index/img_fb.png" alt="Facebook">Study Group</h2>
<!--           <p>Integration with Facebook to get Facebook login and users can find out which courses their friends are taking.</p>
          <p><a class="btn" href="#">View details &raquo;</a></p> -->
          <p>This website provided detailed course information for courses offer at University of Illinois at Urbana Champaign. Students can search for courses they are taking each semester and view existing study groups. Also, they can enroll the study group themselves so that other student will contact them through emails and learn more about other student from their public profile.</p>
        </div>
      </div>

      <hr>

      <footer>
        <p>&copy; DarkFlameMaster 2013</p>
      </footer>

    </div> <!-- /container -->

    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>