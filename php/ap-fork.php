<?php
// if ( !function_exists('ap_assign_get') ) :
// function ap_fork_do($master, $brance, $userid) {
// 	$query = "SELECT * FROM ASSIGNMENT WHERE end > ".$from." OR begin < ".$to;
// 	$dbconn = ap_db_connect();
// 	$results = ap_db_query($query);
// 	$r = array();
// 	if (!$results) {
// 		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
// 		return $r;
// 	}
// 	while ( ($row = ap_db_next($results)) ) {
// 		$r[] = array(
// 			'id' => $row['id'],
// 			'title' => $row['title'],
// 			'url' => "/assignment.php?aid=".($row['id']),
// 			'class' => $row['tag'],
// 			'start' => $row['begin'].'000',
// 			'end' => $row['end'].'000'
//         );
// 		// = $row;
// 	}
// 	return $r;
// }
// endif;

if ( !function_exists('ap_fork_create') ) :
function ap_fork_create( $assign = array() ) {
	if ( !isset($assign['title']) ) {
		error_log(__FUNCTION__ . ': invalid assignment');
		return FALSE;
	}
	$dbconn = ap_db_connect();
	// compose sql statement
	$query = "INSERT INTO FORK ( masterid, branchid, userid) VALUES (";
	$query .= $assign['masterid']. ", ";
	$query .= $assign['branchid']. ", ";
	$query .= $assign['userid']. ");";
	// $query2 = "INSERT INTO C2A ( cid, aid) VALUES (";
	// $query2 .= $assign['userid']. ", ";
	// $query2 .= $assign['branchid']. ");";
	if ( ap_db_query($query)) {
		return TRUE;
	} else {
		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
		return FALSE;
	}
}
endif;

// if ( !function_exists('ap_fork_all') ) :
// function ap_fork_all($cid) {
// 	/* fork all assignment in a course*/
// 	$query = "SELECT * FROM C2A WHERE cid = ".$cid;
// 	$dbconn = ap_db_connect();
// 	$results = ap_db_query($query);
// 	$r = array();
// 	if (!$results) {
// 		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
// 		return $r;
// 	}
// 	while ( ($row = ap_db_next($results)) ) {
// 		$r[] = array(
// 			'id' => $row['id'],
// 			'title' => $row['title'],
// 			'url' => "/assignment.php?aid=".($row['id']),
// 			'class' => $row['tag'],
// 			'start' => $row['begin'].'000',
// 			'end' => $row['end'].'000'
//         );
// 		// = $row;
// 	}
// 	return $r;
// }
// endif;
?>