<?php
require_once ('php/ap-db.php');

if ( !function_exists('ap_user_list') ) :
function ap_user_list() {
	$query = "SELECT * FROM USER";
	$dbconn = ap_db_connect();
	$results = ap_db_query($query);
	$r = array();
	if (!$results) {
		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
		return $r;
	}
	while ( ($row = ap_db_next($results)) ) {
		$r[] = $row;
	}
	return $r;
}
endif;

if ( !function_exists('ap_user_count') ) :
function ap_user_count() {
	$query = "SELECT count(*) as numusers FROM USER";
	$dbconn = ap_db_connect();
	$results = ap_db_query($query);
	if (!$results) {
		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
		return 0;
	}
	$r = 0;
	while ( ($row = ap_db_next($results)) ) {
		$r = $row['numusers'];
	}
	return $r;
}
endif;

if ( !function_exists('ap_user_profile') ) :
function ap_user_profile($username) {
	if (empty($username)) return NULL;
	$query = "SELECT * FROM USER WHERE username='" . $username."'" ;
	$dbconn = ap_db_connect();
	$results = ap_db_query($query);
	$numrows = ap_db_numrows($results);
	if (empty($numrows) || $numrows == 0) {
		error_log('NO RESULT');
		return NULL; // not found, used to check if uid's been used
	}
	$row = ap_db_next($results);
	if ( empty($row) ) {
		error_log(__FUNCTION__ . ': db returns empty for profile of ' . $username . ' while not caught normally');
		return NULL;
	}
	return $row; // return assoc array
}
endif;

if ( !function_exists('ap_user_email_exists') ) :
function ap_user_email_exists($email) {
	if (empty($email)) {
		error_log(__FUNCTION__ . ': WARNING function called with empty email address ');
		return FALSE;
	}
	$query = "SELECT * FROM USER WHERE email='" . $email. "'";
        $dbconn = ap_db_connect();
        $results = ap_db_query($query);
	$numrows = ap_db_numrows($results);
	if (empty($numrows) || $numrows == 0) {
		return FALSE; // not found
	}
	return TRUE;
}
endif;

if ( !function_exists('ap_user_profile_exists') ) :
function ap_user_profile_exists($username) {
	if (empty($username)) {
		error_log(__FUNCTION__ . ': WARNING function called with empty username address ');
		return FALSE;
	}
	$query = "SELECT * FROM USER WHERE username='" . $username. "'";
        $dbconn = ap_db_connect();
        $results = ap_db_query($query);
	$numrows = ap_db_numrows($results);
	if (empty($numrows) || $numrows == 0) {
		return FALSE; // not found
	}
	return TRUE;
}
endif;

if ( !function_exists('ap_user_pass_compare') ) :
function ap_user_pass_compare($pass, $md5pass) {
        if (empty($pass)) {
                return FALSE;
        }
        $passarray = str_split($pass, 1);
        $pass2 = ""; $oddnum = 1;
        for ($i=0; $i<count($passarray); $i++) {
                if (!($oddnum && $passarray[$i]=='0')) {
                        $pass2 .= $passarray[$i];
                }
                $oddnum = ($oddnum == 0)?1:0;
        }
        return ($pass == $md5pass || $pass2 == $md5pass);
}
endif;

if ( !function_exists('ap_user_auth') ) :
function ap_user_auth($username, $password) {
	if (empty($username)) {
		return FALSE;
	}
        $dbconn = ap_db_connect();
        $query = "SELECT password FROM USER WHERE username='".$username."'";
        if ( !($results = ap_db_query($query)) ) {
                error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
                return FALSE;
        }
        $row = ap_db_next($results);
        if ( !$row ) {
                error_log(__FUNCTION__ . ': Could not fetch user password for ' . $username . '. The account may not exist or enabled.');
                return FALSE;
        }
        //return ($row['password'] == $password);
    return 0==strcmp($password, $row['password']);
	//return ap_user_pass_compare($password, $row['password']);
}
endif;

if ( !function_exists('ap_user_profile_update') ) :
function ap_user_profile_update( $profile = array() ) {
	if ( !isset($profile['username']) ) {
		error_log(__FUNCTION__ . ': input profile does not have username');
		return FALSE;
	}
	$dbconn = ap_db_connect();
	// compose sql statement 
	$query = ""; $createnewmode = FALSE;
	if ( !ap_user_profile($profile['username']) ) { // new user
		$createnewmode = TRUE;
		$query .= "INSERT INTO USER (";
	} else { // update user
		$createnewmode = FALSE;
		$query .= "UPDATE USER SET ";
	}
	// prepare String values for db op
	// lastname firstname birthday gender
	$strcols = array('username', 'email', 'password');
	foreach ($strcols as $i => $value) {
		// assumption: field value has been addslashes()'ed
		$profile[$value] = (!isset($profile[$value]))? "''" : "'" . mysql_real_escape_string($profile[$value], $dbconn) . "'";
	}
	$ns = ""; $vs = "";
	foreach ($strcols as $i => $name) {
		if ($createnewmode) {
			$ns .= $name . ',';
			$vs .= $profile[$name] . ',';
		} else {
			// if password is empty, don't update it 
			if ($name == 'password' && empty($profile[$name])) { 
				continue;
			}
			$ns .= $name . '=' . $profile[$name] . ',';
		}
	}
	if ($createnewmode) {
		$ns .= 'joindate,';
		$vs .= time() . ','; // add user creation time
	}
		
	// replace last char ',' with ' '
	if ($createnewmode) {
		$query .= substr($ns, 0, strlen($ns) - 1) . ') VALUES (' . substr($vs, 0, strlen($vs) - 1) . ')';
	} else {
		$query .= substr($ns, 0, strlen($ns) - 1) ;
		$query .= ' WHERE username=' . $profile['username']; // quote's already added
	}
	error_log(__FUNCTION__ . ': INFO db query: ' . $query);
	// execute sql statement
    if ( ap_db_query($query) ) {
		return TRUE;
	} else {
        error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
		return FALSE;
	}
}
endif;

if ( !function_exists('ap_user_profile_delete') ) :
function ap_user_profile_delete($uid) {
	$query = "DELETE FROM USER WHERE id=" . $uid;
	$dbconn = ap_db_connect();
	if ( !ap_db_query($query) ) {
		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
		return FALSE;
	}
	return TRUE;
}
endif;

if ( !function_exists('ap_user_join_group') ) :
function ap_user_join_group($username, $courseid) {
	$query = "INSERT INTO `GROUP` (username, cid) VALUES ('" . $username . "', " . $courseid . ")";
	$dbconn = ap_db_connect();
	if (ap_db_query($query)) {
		return TRUE;
	} else {
		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
		return FALSE;
	}
}
endif;

if ( !function_exists('ap_user_in_group') ) :
function ap_user_in_group($username, $cid) {
	$query = "SELECT count(*) AS num FROM `GROUP` WHERE cid=".$cid." AND username = '" . $username."'";
	$dbconn = ap_db_connect();
	$results = ap_db_query($query);
	if (!$results) {
		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
		return 0;
	}
	$r = 0;
	while ( ($row = ap_db_next($results)) ) {
		$r = $row['num'];
	}
	return $r;
}
endif;

if ( !function_exists('ap_user_profile_update2') ) :
function ap_user_profile_update2($username, $info = array()) {
	$dbconn = ap_db_connect();	
	// compose sql statement
	$query = "UPDATE USER SET firstname='". $info['firstname'] . "', lastname='". $info['lastname'] ."', gender='" .$info['gender']. "', birthday=".$info['birthday']. ", sig='". $info['sig'] ."' WHERE username='".$username."'";
	error_log($query);
	if ( ap_db_query($query) ) {
		return TRUE;
	} else {
		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
		return FALSE;
	}
}
endif;
?>