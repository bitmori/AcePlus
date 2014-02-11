<?php
// this file will be used to deal with the registration and login request

require_once( 'php/ap-config.php');
require_once( 'php/ap-session.php' );
require_once( 'php/ap-user-min.php' );// original user file is not optimized.
require_once( 'php/ap-assign.php' );
require_once( 'php/ap-redirect.php' );
ap_user_session_start();
$authuser = ap_user_session_auth();

	$act = (isset($_POST['act']))?html_entity_decode(urldecode($_POST['act'])):"";
	$fromDate = (isset($_GET['from']))?html_entity_decode(urldecode($_GET['from'])):"";
	$toDate = (isset($_GET['to']))?html_entity_decode(urldecode($_GET['to'])):"";
	if (0==strcmp($act, "")) {
		$res = ap_assign_get_of_user($authuser, $fromDate/1000, $toDate/1000 );
		print json_encode(array('success'=>1, 'result'=>$res));
	}
?>