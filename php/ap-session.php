<?php
/* keep user session alive */
if ( !function_exists('ap_user_session_start') ) :
function ap_user_session_start() {
	session_name('ACEPLUS_SID');
	session_set_cookie_params( AP_SESSION_LIFETIME );
	session_start();
	// if user logged in, keep session alive
	ap_user_session_continue();
}
endif;

/* keep user session alive */
if ( !function_exists('ap_user_session_continue') ) :
function ap_user_session_continue() {
	if (isset($_SESSION['ACEPLUS_LOGGEDIN']) && $_SESSION['ACEPLUS_LOGGEDIN']) {
		$sn = session_name();
		if (isset($_COOKIE[$sn])) {
			setcookie($sn, $_COOKIE[$sn], time() + AP_SESSION_LIFETIME, '/');
		}
	}
}
endif;

/* setup user session */
//TODO: Use Memcache to store user session info and verify no sniffing attack
if ( !function_exists('ap_user_session_create') ) :
function ap_user_session_create($username) {
	if (!isset($_SESSION['ACEPLUS_LOGGEDIN']) || !$_SESSION['ACEPLUS_LOGGEDIN']) {
		$_SESSION['ACEPLUS_LOGGEDIN']=1;
		$_SESSION['ACEPLUS_U']=$username;
		ap_user_session_continue();
	}
}
endif;
/* verify a session */
if ( !function_exists('ap_user_session_auth') ) :
function ap_user_session_auth() {
	if (!(isset($_SESSION['ACEPLUS_LOGGEDIN']) && $_SESSION['ACEPLUS_LOGGEDIN'])) {
		return NULL;
	}
	return $_SESSION['ACEPLUS_U'];
}
endif;
/* destroy a session */
if ( !function_exists('ap_user_session_destroy') ) :
function ap_user_session_destroy() {
	if (isset($_SESSION['ACEPLUS_LOGGEDIN']) && $_SESSION['ACEPLUS_LOGGEDIN']) {
		$_SESSION = array();
		if (isset($_COOKIE[session_name()])) {
			session_set_cookie_params(-1);
		}
		session_destroy();
	}
}
endif;
?>
