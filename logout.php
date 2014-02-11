<?php
require_once('php/ap-config.php');
require_once( 'php/ap-session.php' );
require_once( 'php/ap-redirect.php' );
ap_user_session_start();
$authuser = ap_user_session_auth();
if ($authuser) {
	//TODO: record user logout in db
	error_log("[INFO] user " . $authuser . " logout at [" . time() . "] "); 
	ap_user_session_destroy();
}
ap_redirect(); // by default, direct to homepage
?>
