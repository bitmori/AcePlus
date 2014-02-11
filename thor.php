<?php
// this file will be used to deal with the registration and login request

require_once( 'php/ap-config.php');
require_once( 'php/ap-session.php' );
require_once( 'php/ap-user-min.php' );// original user file is not optimized.
require_once( 'php/ap-assign.php' );
require_once( 'php/ap-course.php' );
require_once( 'php/ap-redirect.php' );

	$act = (isset($_POST['act']))?html_entity_decode(urldecode($_POST['act'])):"";
	$act_g = (isset($_GET['act']))?html_entity_decode(urldecode($_GET['act'])):"";
	if (0==strcmp($act_g, "forkall")) {
		$cid = (isset($_GET['cid']))?html_entity_decode(urldecode($_GET['cid'])):"";
		print json_encode(array('code'=>200, 'success'=>true, 'msg'=>'good'));
	}
	if (0==strcmp($act, "reg")) {// reg
		$mailaddr = (isset($_POST['ap_mail']))?html_entity_decode(urldecode($_POST['ap_mail'])):"";
		$username = (isset($_POST['ap_user']))?html_entity_decode(urldecode($_POST['ap_user'])):"";
		$password = (isset($_POST['ap_pass']))?html_entity_decode(urldecode($_POST['ap_pass'])):"";
		
		$mailOK = !ap_user_email_exists($mailaddr);
		$userOK = !ap_user_profile_exists($username);
		if (!$mailOK || !$userOK) {
			print json_encode(array('code'=>401, 'success'=>false, 'mailOK'=>$mailOK, 'userOK'=>$userOK));
		} else {
			ap_user_profile_update( array('username'=>$username, 'password'=>$password, 'email'=>$mailaddr) );
			print json_encode(array('code'=>200, 'success'=>true, 'msg'=>'good'));
		}
	} elseif (0==strcmp($act, "login")) {
		ap_user_session_start();
		$authuser = ap_user_session_auth();
		if (!$authuser) {
			$username = (isset($_POST['ap_user']))?html_entity_decode(urldecode($_POST['ap_user'])):"";
			$password = (isset($_POST['ap_pass']))?html_entity_decode(urldecode($_POST['ap_pass'])):"";
			$authed = ap_user_auth($username, $password);
			if (!$authed) {
				print json_encode(array('success'=>true, 'code'=>'404'));
				return;
			}
			ap_user_session_create($username);
			$authuser = ap_user_session_auth();
			// redirect URL if needed
			$targeturl = (isset($_POST['ap_targeturl']))?html_entity_decode(urldecode($_POST['ap_targeturl'])):"";
			if (empty($targeturl)) {
				$targeturl = "/";
			}
			print json_encode(array('success'=>true, 'code'=>'200', 'redirect'=>$targeturl));
		} else {
			ap_redirect('/profile.php');
		}
		
	} elseif (0==strcmp($act, "createAssign")) {
		$title = (isset($_POST['ap_title']))?html_entity_decode(urldecode($_POST['ap_title'])):"Untitled Assignment";
		$begin = (isset($_POST['ap_begin']))?html_entity_decode(urldecode($_POST['ap_begin'])):"";
		$endat = (isset($_POST['ap_end']))?html_entity_decode(urldecode($_POST['ap_end'])):"";
		$content = (isset($_POST['ap_content']))?html_entity_decode(urldecode($_POST['ap_content'])):"Empty Assignment";
		$tag = (isset($_POST['ap_tag']))?html_entity_decode(urldecode($_POST['ap_tag'])):"aceplus_stub";
		$cid = (isset($_POST['ap_cid']))?html_entity_decode(urldecode($_POST['ap_cid'])):"0";
		$username = (isset($_POST['ap_user']))?html_entity_decode(urldecode($_POST['ap_user'])):"aceplus_admin";

		ap_assign_create( array( 'begin'=>$begin , 'end'=>$endat, 'tag'=>$tag, 'title'=>$title, 'content'=>addslashes($content), 'status'=>0, 'cid'=>$cid, 'username'=>$username) );
		print json_encode(array('code'=>200, 'success'=>true, 'msg'=>'good'));
	} elseif (0==strcmp($act, "deleteAssign")) {
		$id = (isset($_POST['ap_aid']))?html_entity_decode(urldecode($_POST['ap_aid'])):"0";
		if ($id==0){
			print json_encode(array('code'=>401, 'success'=>false));
		}else{
			ap_assign_delete($id);
			print json_encode(array('code'=>200, 'success'=>true, 'msg'=>'good'));
		}
	} elseif (0==strcmp($act, "uniqueusername")) {
		$username = (isset($_POST['ap_user']))?html_entity_decode(urldecode($_POST['ap_user'])):"";
		$userOK = !ap_user_profile_exists($username);
		print json_encode($userOK?true:"Username is already taken");
	} elseif (0==strcmp($act, "uniqueemail")) {
		$mailaddr = (isset($_POST['ap_mail']))?html_entity_decode(urldecode($_POST['ap_mail'])):"";
		$mailOK = !ap_user_email_exists($mailaddr);
		print json_encode($mailOK?true:"This E-mail address has already been used for registration");
	} elseif (0==strcmp($act, "userexist")) {
		$username = (isset($_POST['ap_user']))?html_entity_decode(urldecode($_POST['ap_user'])):"";
		$userOK = ap_user_profile_exists($username);
		print json_encode($userOK?true:"Username does not exist");
	} elseif (0==strcmp($act, "course_search")) {
		$search_str = (isset($_POST['ap_search']))?html_entity_decode(urldecode($_POST['ap_search'])):"";
		$res = ap_course_search(strtoupper($search_str));
		if (isset($res)) {
			print json_encode(array('success'=>true,'options'=>$res));
		} else {
			print json_encode(array('success'=>false, 'code'=>'404'));
		}
	} elseif (0==strcmp($act, "course_list")) {
		$search_str = (isset($_POST['ap_search']))?html_entity_decode(urldecode($_POST['ap_search'])):"";
		$res = ap_course_get_list(strtoupper($search_str), 1);
		if (isset($res)) {
			print json_encode(array('success'=>true,'options'=>$res));
		} else {
			print json_encode(array('success'=>false, 'code'=>'404'));
		}
	} elseif (0==strcmp($act, "fork")) {
		$aid = (isset($_POST['ap_aid']))?html_entity_decode(urldecode($_POST['ap_aid'])):"";
		$username = (isset($_POST['ap_user']))?html_entity_decode(urldecode($_POST['ap_user'])):"aceplus_admin";
		$res = ap_assign_fork($aid, $username);
		if ($res) {
			print json_encode(array('success'=>true,'aid'=>$res));
		} else {
			print json_encode(array('success'=>false));
		}
	} elseif (0==strcmp($act, "updateAssign")) {
		$aid = (isset($_POST['ap_aid']))?html_entity_decode(urldecode($_POST['ap_aid'])):"";
		$title = (isset($_POST['ap_title']))?html_entity_decode(urldecode($_POST['ap_title'])):"Untitled Assignment";
		$begin = (isset($_POST['ap_begin']))?html_entity_decode(urldecode($_POST['ap_begin'])):"";
		$endat = (isset($_POST['ap_end']))?html_entity_decode(urldecode($_POST['ap_end'])):"";
		$content = (isset($_POST['ap_content']))?html_entity_decode(urldecode($_POST['ap_content'])):"Empty Assignment";
		$tag = (isset($_POST['ap_tag']))?html_entity_decode(urldecode($_POST['ap_tag'])):"aceplus_stub";

		$r = ap_assign_update( array('id'=>$aid, 'begin'=>$begin , 'end'=>$endat, 'tag'=>$tag, 'title'=>$title, 'content'=>addslashes($content)) );
		if ($r) {
			print json_encode(array('code'=>200, 'success'=>true, 'msg'=>'good'));
		}else {
			print json_encode(array('code'=>200, 'success'=>false, 'msg'=>'good'));
		}
	} elseif (0==strcmp($act, "join")) {
		$username = (isset($_POST['ap_user']))?html_entity_decode(urldecode($_POST['ap_user'])):"";
		$cid = (isset($_POST['ap_cid']))?html_entity_decode(urldecode($_POST['ap_cid'])):"";

		$r = ap_user_join_group($username, $cid);
		if ($r) {
			print json_encode(array('code'=>200, 'success'=>true, 'msg'=>'good'));
		}else {
			print json_encode(array('code'=>200, 'success'=>false, 'msg'=>'good'));
		}
	} elseif (0==strcmp($act, "updateProfile")) {
		$username = (isset($_POST['ap_user']))?html_entity_decode(urldecode($_POST['ap_user'])):"";
		$firstname = (isset($_POST['ap_first']))?html_entity_decode(urldecode($_POST['ap_first'])):"";
		$lastname = (isset($_POST['ap_last']))?html_entity_decode(urldecode($_POST['ap_last'])):"";
		$bd = (isset($_POST['ap_bd']))?html_entity_decode(urldecode($_POST['ap_bd'])):"";
		$gender = (isset($_POST['ap_gender']))?html_entity_decode(urldecode($_POST['ap_gender'])):"";
		$sig = (isset($_POST['ap_sig']))?html_entity_decode(urldecode($_POST['ap_sig'])):"";

		$r = ap_user_profile_update2($username, array('firstname'=>$firstname , 'lastname'=>$lastname, 'gender'=>$gender, 'birthday'=>$bd, 'sig'=>addslashes($sig)));
		if ($r) {
			print json_encode(array('code'=>200, 'success'=>true, 'msg'=>'good'));
		}else {
			print json_encode(array('code'=>200, 'success'=>false, 'msg'=>'good'));
		}
	}

?>