<?php
/* redirect page */
if ( !function_exists('ap_redirect') ) :
function ap_redirect($dstpage = '/', $targeturl = '') {
	$rurl = $dstpage;
	if (!empty($targeturl)) {
		$rurl = $rurl . "?targeturl=" . urlencode(htmlentities($targeturl));
	}
	header('Location: ' . $rurl);
	exit;
}
endif;
?>
