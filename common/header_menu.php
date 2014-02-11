<?php
require_once( 'php/ap-config.php');
require_once( 'php/ap-session.php' );
function setActiveClass($pagename)
{
  if (0==strcmp($_SERVER['PHP_SELF'], $pagename)) {
    echo ' class="active"';
  }
}
?>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="/">Ace+</a>
          <div class="nav-collapse collapse">

            <ul class="nav">
              <li<?php setActiveClass("/index.php") ?>><a href="/">Home</a></li>
              <li<?php setActiveClass("/assign.php") ?>><a href="/assign.php">Assignments</a></li>
              <li<?php setActiveClass("/cal.php") ?>><a href="/cal.php">Schedules</a></li>
              <li<?php setActiveClass("/course.php") ?>><a href="/course.php">Courses</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Documentation <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="/about.php">About</a></li>
                  <li><a href="/db2.html">DB description</a></li>
                  <!-- <li><a href="#">Something else here</a></li> -->
                  <li class="divider"></li>
                  <li class="nav-header">CS 411 DEMO</li>
                  <li><a href="https://wiki.engr.illinois.edu/pages/viewpage.action?pageId=227750634">Project Page</a></li>
                  <li><a href="https://wiki.engr.illinois.edu/pages/viewpage.action?pageId=230916101">Final Report</a></li>
                </ul>
              </li>
            </ul>
            <?php if ($authuser): ?>
              <ul class="nav pull-right">
                <li class="dropdown">
                  <a class="dropdown-toggle" data-toggle="dropdown"><?=$authuser?> <b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li><a href="/profile.php">Profile</a></li>
                    <li><a href="/logout.php">Logout</a></li>
                  </ul>
                </li>
              </ul>
            <?php else: ?>
              <p class="navbar-text pull-right"><a class="navbar-link" href="/login.php">Begin to use</a></p>
            <?php endif; ?>

          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>