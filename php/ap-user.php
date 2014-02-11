<?php
require_once ('php/ap-db.php');
//require_once ('php/ap-util.php');

/**
 * Get list of users
 * @return array The list of USERINFO entries as array of associated arrays
 */
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

/**
 * Count the number of users
 * @returns integer the number of users
 */
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

/**
 * Get user profile by id
 * @param string $uid user id as a string
 * @return array user profile as an associated array
 */
if ( !function_exists('ap_user_profile') ) :
function ap_user_profile($username) {
	if (empty($username)) return NULL;
	$query = "SELECT * FROM USER WHERE username=" . $username ;
	$dbconn = ap_db_connect();
	$results = ap_db_query($query);
	$numrows = ap_db_numrows($results);
	if (empty($numrows) || $numrows == 0) {
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

/**
 * Check if user email address exists. Used by user registration
 * @param string $email E-mail address
 * @return bool true - exists; false - not exists
 */
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

if ( !function_exists('ap_user_profile_update') ) :
/**
 * Update user profile. This fuction supports both creation and
 * update of user account. If a new account, creation time is recorded;
 * if account update, password is ignored if not given.
 * @param array $profile associated array of USERINFO entry
 * @return bool success or failure. Log is put into Gateway log upon failure
 * or new account creation
 */
function ap_user_profile_update( $profile = array() ) {
	if ( !isset($profile['id']) ) {
		error_log(__FUNCTION__ . ': input profile does not have id');
		return FALSE;
	}
	$dbconn = ap_db_connect();
	// compose sql statement 
	$query = ""; $insertmode = FALSE;
	if ( !ap_user_profile($profile['id']) ) { // new user
		$insertmode = TRUE;
		$query .= "INSERT INTO USER (";
	} else { // update user
		$insertmode = FALSE;
		$query .= "UPDATE USER SET ";
	}
	// prepare String values for db op
	$strcols = array('id', 'lastname', 'firstname', 'email', 'password');
	foreach ($strcols as $i => $value) {
		// assumption: field value has been addslashes()'ed
		$profile[$value] = (!isset($profile[$value]))? "''" : "'" . mysql_real_escape_string($profile[$value], $dbconn) . "'";
	}
	$ns = ""; $vs = "";
	foreach ($strcols as $i => $name) {
		if ($insertmode) {
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
	if ($insertmode) {
		$ns .= 'created,';
		$vs .= time() . ','; // add user creation time
	}
		
	// replace last char ',' with ' '
	if ($insertmode) {
		$query .= substr($ns, 0, strlen($ns) - 1) . ') VALUES (' . substr($vs, 0, strlen($vs) - 1) . ')';
	} else {
		$query .= substr($ns, 0, strlen($ns) - 1) ;
		$query .= ' WHERE id=' . $profile['id']; // quote's already added
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

/* delete user profile by user id string*/
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


/* login stat update */
if ( !function_exists('ap_user_login_update') ) :
function ap_user_login_update($uid, $timestamp) {
        $dbconn = ap_db_connect();
	$query = "SELECT numvisit FROM USERINFO WHERE id='" . $uid . "'";
        if ( !($results = ap_db_query($query)) ) {
                error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
                return FALSE;
        }
	$row = ap_db_next($results);
	if ( !$row ) {
                error_log(__FUNCTION__ . ': Could not fetch result row for ' . $uid);
                return FALSE;
        }
	$count = $row['numvisit'] + 1;
        $query = "UPDATE USERINFO SET lastlogin=" . $timestamp . ", numvisit=" . $count . " WHERE id='" . $uid . "'";
        if ( !ap_db_query($query) ) {
                error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
                return FALSE;
        }
	return TRUE;
}
endif;

/* find most recent users */
if ( !function_exists('ap_user_mostrecent_visitors') ) :
function ap_user_mostrecent_visitors($howmany) {
        $dbconn = ap_db_connect();
	/**TODO: LIMIT applies to MySQL and Postgresql, not Oracle **/
        $query = "SELECT firstname, id, org FROM USERINFO ORDER BY lastlogin DESC LIMIT " . $howmany;
        if ( !($results = ap_db_query($query)) ) {
                error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
                return NULL;
        }
	$r = array(); 
        while ( ($row = ap_db_next($results)) ) {
		$r[] = array('firstname' => $row['firstname'], 'id' => $row['id'], 'org' => $row['org']);
	}
	return $r;
}
endif;

/* encode password: as an abstraction for configureable encoding methods */
if ( !function_exists('ap_user_pass_encode') ) :
function ap_user_pass_encode($passtext) {
	return (empty($passtext)?'':MD5($passtext));
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

/* authenticate a user login */
if ( !function_exists('ap_user_auth') ) :
function ap_user_auth($username, $password) {
	if (empty($username)) {
		return FALSE;
	}
        $dbconn = ap_db_connect();
        $query = "SELECT password FROM USERINFO WHERE id='" . $username . "' and status=0";
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
	return ap_user_pass_compare($password, $row['password']);
}
endif;

/* create password reset link */
// if ( !function_exists('ap_user_passreset_link') ) :
// function ap_user_passreset_link($email) {
//         $dbconn = ap_db_connect();
//         $query = "SELECT id FROM USERINFO WHERE email='" . $email . "'";
//         if ( !($results = ap_db_query($query)) ) {
//                 error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
//                 return NULL;
//         }
// 	$row = ap_db_next($results);
//         if ( !$row || !isset($row['id'])) {
//                 error_log(__FUNCTION__ . ': Could not find user for ' . $email);
//                 return NULL;
//         }
// 	$link = ap_util_uniqid('passreset');
// 	$query = "INSERT INTO passreset (id, timestamp, link) VALUES ('" . $row['id'] . "', " . time() . ", '" . $link . "')";
//         if ( !($results = ap_db_query($query)) ) {
//                 error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
//                 return NULL;
//         }
// 	return $link;
// }
// endif;

/* check password reset link is valid */
// return user's id
if ( !function_exists('ap_user_passreset_check') ) :
function ap_user_passreset_check($resetlink) {
        $dbconn = ap_db_connect();
        $query = "SELECT id, timestamp FROM passreset WHERE link='" . $resetlink . "'";
        if ( !($results = ap_db_query($query)) ) {
                error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
                return NULL;
        }
	$row = ap_db_next($results);
        if ( !$row || !isset($row['id'])) {
                error_log(__FUNCTION__ . ': Could not find user for ' . $resetlink);
                return NULL;
        }
	$now = time();
	if ($now - $row['timestamp'] > 7200) {
		return NULL;
	} else {
		return $row['id'];
	}
}
endif;

/* reset password */
// password is already encrypted
if ( !function_exists('ap_user_passreset') ) :
function ap_user_passreset($username, $password, $resetlink) {
	/** recheck to make sure action's valid **/
	if (ap_user_passreset_check($resetlink) != $username) {
		error_log(__FUNCTION__ . ': SECURITY ' . $username . ' wanted to reset password, but served a wrong reset link');
		return FALSE;
	}
	/** update password **/
        $dbconn = ap_db_connect();
	$query = "UPDATE USERINFO SET password='" . $password . "' WHERE id='" . $username . "'";
        if ( !($results = ap_db_query($query)) ) {
                error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
                return FALSE;
        }
	/** clear this user's password reset record, including those issued but not finished **/
	//$query = "DELETE FROM passreset WHERE id='" . $username . "' and link='" . $resetlink . "'";
	$query = "DELETE FROM passreset WHERE id='" . $username . "'";
        if ( !($results = ap_db_query($query)) ) {
                error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
                return FALSE;
        }
	return TRUE;
}
endif;

?>