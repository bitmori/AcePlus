<?php
require_once( 'php/ap-config.php');
require_once( 'php/ap-session.php' );
require_once( 'php/ap-user.php' );
require_once( 'php/ap-redirect.php' );

ap_user_session_start();
$authuser = ap_user_session_auth();
$authed = FALSE;
if (!$authuser) {
	$username = (isset($_POST['ap-user']))?html_entity_decode(urldecode($_POST['ap-user'])):"";
	$password = (isset($_POST['ap-pass']))?html_entity_decode(urldecode($_POST['ap-pass'])):"";

	$authed = ap_user_auth($username, MD5($password));

	if ($authed) {
		ap_user_login_update($username, time());
		ap_user_session_create($username);
		// redirect URL if needed
		$targeturl = (isset($_POST['ap-targeturl']))?html_entity_decode(urldecode($_POST['ap-targeturl'])):"";
		error_log("[INFO] user " . $username . " login at [" . time() . "] from " . $_SERVER['REMOTE_ADDR'] . ' target=' . $targeturl);
		if (empty($targeturl)) {
			$targeturl = '/';
		}
		print json_encode(array('success'=>true, 'redirect'=>$targeturl));
	} else {
		print json_encode(array('success'=>false, 'msg'=>'User ID or password does not match, or the account is disabled.'));
	}
} else { // already logged in
	ap_redirect( '/' ); // by default, direct to homepage
}
?>
