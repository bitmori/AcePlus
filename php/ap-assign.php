<?php

if ( !function_exists('ap_assign_list') ) :
function ap_assign_list() {
	$query = "SELECT * FROM ASSIGNMENT ORDER BY id DESC";
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

if ( !function_exists('ap_assign_of_user') ) :
function ap_assign_of_user($username) {
	$query = "SELECT * FROM ASSIGNMENT WHERE username='".$username."' ORDER BY id DESC";
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

if ( !function_exists('ap_assign_of_course') ) :
function ap_assign_of_course($cid) {
	$query = "SELECT * FROM ASSIGNMENT WHERE cid = ".$cid;
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

if ( !function_exists('ap_assign_get') ) :
function ap_assign_get($from, $to) {
	$query = "SELECT * FROM ASSIGNMENT WHERE end > ".$from." OR begin < ".$to;
	$dbconn = ap_db_connect();
	$results = ap_db_query($query);
	$r = array();
	if (!$results) {
		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
		return $r;
	}
	while ( ($row = ap_db_next($results)) ) {
		$r[] = array(
			'id' => $row['id'],
			'title' => $row['title'],
			'url' => "/assignment.php?aid=".($row['id']),
			'class' => $row['tag'],
			'start' => $row['begin'].'000',
			'end' => $row['end'].'000'
        );
		// = $row;
	}
	return $r;
}
endif;

if ( !function_exists('ap_assign_get_of_user') ) :
function ap_assign_get_of_user($username,$from, $to) {
	$query = "SELECT * FROM ASSIGNMENT WHERE (end > ".$from." OR begin < ".$to.")AND username='".$username."'";
	$dbconn = ap_db_connect();
	$results = ap_db_query($query);
	$r = array();
	if (!$results) {
		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
		return $r;
	}
	while ( ($row = ap_db_next($results)) ) {
		$r[] = array(
			'id' => $row['id'],
			'title' => $row['title'],
			'url' => "/assignment.php?aid=".($row['id']),
			'class' => ap_assign_class($row['tag']),
			'start' => $row['begin'].'000',
			'end' => $row['end'].'000'
        );
		// = $row;
	}
	return $r;
}
endif;

if ( !function_exists('ap_assign_class') ) :
function ap_assign_class($tag) {
	switch ($tag) {
		case 'Homework':
			return "event-success";
		case 'Lab':
			return "event-information";
		case 'Project':
			return "event-special";
		case 'Midterm':
			return "event-important";
		case 'Final':
			return "event-important";
		case 'Misc':
			return "event-inverse";
		default:
			return $tag;
	}
}
endif;

if ( !function_exists('ap_assign_count') ) :
function ap_assign_count() {
	$query = "SELECT count(*) as numassigns FROM ASSIGNMENT";
	$dbconn = ap_db_connect();
	$results = ap_db_query($query);
	if (!$results) {
		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
		return 0;
	}
	$r = 0;
	while ( ($row = ap_db_next($results)) ) {
		$r = $row['numassigns'];
	}
	return $r;
}
endif;

if ( !function_exists('ap_assign_detail') ) :
function ap_assign_detail($aid) {
	if (empty($aid)) return NULL;
	$query = "SELECT * FROM ASSIGNMENT WHERE id=" . $aid;
	$dbconn = ap_db_connect();
	$results = ap_db_query($query);
	$numrows = ap_db_numrows($results);
	if (empty($numrows) || $numrows == 0) {
		error_log("NO RESULT");
		return NULL; // not found, used to check if uid's been used
	}
	$row = ap_db_next($results);
	if ( empty($row) ) {
		error_log(__FUNCTION__ . ': db returns empty for detail of ' . $aid . ' while not caught normally');
		return NULL;
	}
	return $row; // return assoc array
}
endif;

if ( !function_exists('ap_assign_exists') ) :
function ap_assign_exists($aid) {
	if (empty($aid)) {
		error_log(__FUNCTION__ . ': WARNING function called with empty aid address ');
		return FALSE;
	}
	$query = "SELECT * FROM ASSIGNMENT WHERE id='" . $aid. "'";
	$dbconn = ap_db_connect();
	$results = ap_db_query($query);
	$numrows = ap_db_numrows($results);
	if (empty($numrows) || $numrows == 0) {
		return FALSE; // not found
	}
	return TRUE;
}
endif;

if ( !function_exists('ap_assign_create') ) :
function ap_assign_create( $assign = array() ) {
	if ( !isset($assign['title']) ) {
		error_log(__FUNCTION__ . ': invalid assignment');
		return FALSE;
	}
	$dbconn = ap_db_connect();
	// compose sql statement
	$query = "INSERT INTO ASSIGNMENT ( begin, end, tag, title, content, status, cid, username) VALUES (";
	$query .= $assign['begin']. ", ";
	$query .= $assign['end']. ", ";
	$query .= "'".$assign['tag']. "', ";
	$query .= "'".$assign['title']. "', ";
	$query .= "'".$assign['content']. "', ";
	$query .= $assign['status']. ", ";
	$query .= $assign['cid']. ", ";
	$query .= "'".$assign['username']. "');";
	if ( ap_db_query($query) ) {
		return TRUE;
	} else {
		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
		return FALSE;
	}
}
endif;

if ( !function_exists('ap_assign_update') ) :
function ap_assign_update( $assign = array() ) {
	if ( !isset($assign['id']) ) {
		error_log(__FUNCTION__ . ': invalid assignment');
		return FALSE;
	}
	$dbconn = ap_db_connect();	
	// compose sql statement
	$query = "UPDATE ASSIGNMENT SET begin=". $assign['begin'] . ", end=". $assign['end'] .", tag='" .$assign['tag']. "', title='".$assign['title']. "', content='". $assign['content'] ."' WHERE id=".$assign['id'];
	if ( ap_db_query($query) ) {
		return TRUE;
	} else {
		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
		return FALSE;
	}
}
endif;

if ( !function_exists('ap_assign_fork') ) :
function ap_assign_fork($aid, $username) {
	$dbconn = ap_db_connect();
	// compose sql statement
	
	$query = "INSERT INTO ASSIGNMENT ( begin, end, tag, title, content, status, cid, username) SELECT begin, end, tag, title, content, status, cid,'".$username."' FROM ASSIGNMENT WHERE id=".$aid;
	if ( ap_db_query($query) ) {
		$results = ap_db_query("SELECT max(id) AS MAXID FROM ASSIGNMENT");
		$numrows = ap_db_numrows($results);
		if (empty($numrows) || $numrows == 0) {
			error_log("NO RESULT");
			return FALSE; // not found, used to check if uid's been used
		}
		$row = ap_db_next($results);
		if ( empty($row) ) {
			error_log(__FUNCTION__ . ': db returns empty for detail of ' . $aid . ' while not caught normally');
			return FALSE;
		}
		$bid = $row['MAXID'];
		$query = "INSERT INTO FORK ( masterid, branchid, username) VALUES (".$aid. ", ". $bid." , '" . $username. "')";
		if ( ap_db_query($query) ) {
			return $bid;
		}
	} else {
		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
		return FALSE;
	}
}
endif;

// if ( !function_exists('ap_assign_edit') ) :
// function ap_assign_edit($aid, $assign = array()) {
// 	if ( !isset($assign['title']) ) {
// 		error_log(__FUNCTION__ . ': invalid assignment');
// 		return FALSE;
// 	}
// 	$dbconn = ap_db_connect();
// 	// compose sql statement
// 	UPDATE Person SET Address = 'Zhongshan 23', City = 'Nanjing' WHERE LastName = 'Wilson'
// 	$query = "INSERT INTO ASSIGNMENT ( begin, end, tag, title, content, status, cid, username) SELECT begin, end, tag, title, content, status, cid,'".$username."' FROM ASSIGNMENT WHERE id=".$aid;
// 	if ( ap_db_query($query) ) {
// 		return TRUE;
// 	} else {
// 		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
// 		return FALSE;
// 	}
// }
// endif;

// if ( !function_exists('ap_assign_update') ) :
// function ap_assign_update( $assign = array() ) {
// 	if ( !isset($assign['begin']) ) {
// 		error_log(__FUNCTION__ . ': invalid assignment');
// 		return FALSE;
// 	}
// 	$dbconn = ap_db_connect();
// 	// compose sql statement
// 	$query = "INSERT INTO ASSIGNMENT ( begin, end, tag, content, status) VALUES (";
// 	$query .= $assign['begin']. ", ";
// 	$query .= $assign['end']. ", ";
// 	$query .= $assign['tag']. ", ";
// 	$query .= $assign['content']. ", ";
// 	$query .= $assign['status']. ");";
// 	if ( ap_db_query($query) ) {
// 		return TRUE;
// 	} else {
// 		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
// 		return FALSE;
// 	}
// }
// endif;

if ( !function_exists('ap_assign_delete') ) :
function ap_assign_delete($aid) {
	$query = "DELETE FROM ASSIGNMENT WHERE id=" . $aid;
	$dbconn = ap_db_connect();
	if ( !ap_db_query($query) ) {
		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
		return FALSE;
	}
	return TRUE;
}
endif;

?>