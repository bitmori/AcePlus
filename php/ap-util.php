<?php
/** create a unique string of specified length and prefix **/
if ( !function_exists('ap_util_uniqid') ) :
function ap_util_uniqid($prefix='', $length=16) {
    /** total length is len(prefix) + $length **/
    $uid = crypt(uniqid($prefix . rand() . rand(), 1));
    /** remove php/html tags and backslash **/
    $uid = strip_tags(stripslashes($uid));
    /** Removing special chars **/
    $uid = str_replace(".","o",$uid);
    $uid = str_replace("/","H",$uid);
    $uid = str_replace("$","X",$uid);
    if ($length > strlen($uid)) {
	$length = strlen($uid);
    }
    /** recombine **/
    return substr(substr($uid, strlen($uid) / 2, strlen($uid)) . substr($uid, 0, strlen($uid) / 2), 0, $length);
}
endif;

?>
