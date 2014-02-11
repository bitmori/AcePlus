<?php
/* connect db */
if ( !function_exists('ap_db_connect') ) :
function ap_db_connect($dbname = '') {
	switch (DB_TYPE) {
	case 'mysql':
	default:
		$dbconn = mysql_connect(DB_HOST . ':' . DB_PORT, DB_USER, DB_PASS);
		if (!$dbconn) {
			return NULL;
		}
		if (empty($dbname)) {
			$dbname = DB_NAME;
		}
		if (!mysql_select_db($dbname)) {
			return NULL;
		}
		return $dbconn;
	}
	return NULL;
}
endif;

/* query db */
if ( !function_exists('ap_db_query') ) :
function ap_db_query($query = '') {
	switch (DB_TYPE) {
	case 'mysql':
	default:
		return mysql_query($query);
	}
	return NULL;
}
endif;

/* db result iterator: next */
if ( !function_exists('ap_db_next') ) :
function ap_db_next($results) {
       switch (DB_TYPE) {
        case 'mysql':
        default:
		return mysql_fetch_assoc($results);
	}
	return NULL;
}
endif;

/* db result iterator: get number of rows */
if ( !function_exists('ap_db_numrows') ) :
function ap_db_numrows($results) {
	if (!$results) {
		return 0;
	}
    switch (DB_TYPE) {
        case 'mysql':
        default:
		return mysql_num_rows($results);
	}
	return 0;
}
endif;

/* db conn: close */
if ( !function_exists('ap_db_close') ) :
function ap_db_close($dbconn = NULL) {
        switch (DB_TYPE) {
        case 'mysql':
        default:
                return mysql_close($dbconn);
        }
        return 0;
}
endif;

/*
if ( !function_exists('ap_') ) :
function ap_() {
}
endif;
*/
/* TODO:
 * - to avoid too many db conns, share one that is previously there
 */
?>
