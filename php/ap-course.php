<?php
if ( !function_exists('ap_course_search') ) :
function ap_course_search($want) {
	$dbconn = ap_db_connect();
	$want = mysql_real_escape_string(preg_replace("/[^A-Za-z0-9]/", " ", $want ));
	if (strlen($want) < 1 || $want == ' '){
		return NULL;
	}
	$query = 'SELECT dept, number FROM COURSE WHERE concat(dept, " ", number) LIKE "%'.$want.'%"';
	$results = ap_db_query($query);
	$r = array();
	if (!$results) {
		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
		return NULL;
	}
	$counter = 0;
	while ( ($row = ap_db_next($results)) ) {
		$r[] = $row['dept']." ".$row['number'];
		$counter +=1;
		if ($counter==50) {
			return $r;
		}
	}
	return $r;
}
endif;

if ( !function_exists('ap_course_get_list') ) :
function ap_course_get_list($want ,$page) {
	$dbconn = ap_db_connect();
	$want = mysql_real_escape_string(preg_replace("/[^A-Za-z0-9]/", " ", $want ));
	if (strlen($want) < 1 || $want == ' '){
		return NULL;
	}
	//SELECT * FROM table WHERE 查询条件 ORDER BY 排序条件 LIMIT ((页码-1)*页大小),页大小;
	$query = 'SELECT * FROM COURSE WHERE concat(dept, " ", number) LIKE "%'.$want.'%"';
	$results = ap_db_query($query);
	$r = array();
	if (!$results) {
		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
		return NULL;
	}
	while ( ($row = ap_db_next($results)) ) {
		$r[] = $row;
	}
	return $r;
}
endif;

if ( !function_exists('ap_course_get_assignment') ) :
function ap_course_get_assignment($want ,$page) {
	$dbconn = ap_db_connect();
	$want = mysql_real_escape_string(preg_replace("/[^A-Za-z0-9]/", " ", $want ));
	if (strlen($want) < 1 || $want == ' '){
		return NULL;
	}
	//SELECT * FROM table WHERE 查询条件 ORDER BY 排序条件 LIMIT ((页码-1)*页大小),页大小;
	$query = 'SELECT * FROM COURSE WHERE concat(dept, " ", number) LIKE "%'.$want.'%"';
	$results = ap_db_query($query);
	$r = array();
	if (!$results) {
		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
		return NULL;
	}
	while ( ($row = ap_db_next($results)) ) {
		$r[] = $row;
	}
	return $r;
}
endif;

if ( !function_exists('ap_course_get_by_cid') ) :
function ap_course_get_by_cid($cid) {
	$query = 'SELECT * FROM COURSE WHERE id='.$cid;
	$dbconn = ap_db_connect();
	$results = ap_db_query($query);
	$r = array();
	if (!$results) {
		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
		return NULL;
	}
	while ( ($row = ap_db_next($results)) ) {
		$r[] = $row;
	}
	return $r;
}
endif;

if ( !function_exists('ap_course_get_group') ) :
function ap_course_get_group($cid) {
	$query = "SELECT * FROM USER JOIN `GROUP` ON GROUP.username=USER.username WHERE cid = " . $cid;
	$dbconn = ap_db_connect();
	$results = ap_db_query($query);
	$r = array();
	if (!$results) {
		error_log(__FUNCTION__ . ': ERROR on db query: ' . $query);
		return NULL;
	}
	while ( ($row = ap_db_next($results)) ) {
		$r[] = $row;
	}
	// foreach ($r[0] as $key => $value) {
	// 	error_log($key);
	// }
	return $r;
}
endif;
?>