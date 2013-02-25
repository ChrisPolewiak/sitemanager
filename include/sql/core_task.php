<?

function core_task_add(  $dane ) {
	$dane["core_task__id"] = "0";
	return core_task_edit( $dane );
}

#
##
#

function core_task_edit( $dane ) {

	$dane = trimall($dane);

	if ($dane["core_task__id"]) {
		$tmp_dane = core_task_dane( $dane["core_task__id"] );
		$dane["core_task__status"] = $tmp_dane["core_task__status"];
		$dane["core_task__result"] = $tmp_dane["core_task__result"];
		core_changed_add( $core_task__id, "core_task", $tmp_dane, "edit" );
	}
	else {
		$dane["core_task__id"] = uuid();
		$dane["core_task__params"] = serialize($dane["core_task__params"]);
		core_changed_add( $core_task__id, "core_task", $tmp_dane="", "add" );
	}
	
	$dane["core_task__status"] = $dane["core_task__status"] ? 1 : 0;

	$dane["record_create_date"] = $dane["core_task__id"] ? $tmp_dane["record_create_date"] : time();
	$dane["record_create_id"]   = $dane["core_task__id"] ? $tmp_dane["record_create_id"]   : $_SESSION["content_user"]["content_user__id"];
	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_core_task VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_task__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_task__plugin"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_task__function"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_task__params"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_task__status"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_task__result"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."'\n";
	$SQL_QUERY .= ")\n";

	try {
		if ( $GLOBALS["SM_PDO"]->query( $SQL_QUERY ) or sqlerr("core_task_edit()",$SQL_QUERY) )
			return $dane["core_task__id"];
	}
	catch(PDOException $e) { sqlerr("core_task_edit()",$SQL_QUERY,$e); }
}

#
##
#

function core_task_delete( $core_task__id ) {

	if ($deleted = core_task_dane( $core_task__id ) ) {
		core_changed_add( $core_task__id, "core_task", $deleted, "del" );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_core_task \n";
	$SQL_QUERY .= "WHERE core_task__id='". sm_secure_string_sql( $core_task__id)."'";

	try {
		if ( $result = $GLOBALS["SM_PDO"]->query( $SQL_QUERY ) or sqlerr("core_task_delete()",$SQL_QUERY) )
			return $result->rowCount();
	}
	catch(PDOException $e) { sqlerr("core_task_delete()",$SQL_QUERY,$e); }
}

#
##
#

function core_task_dane( $core_task__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_task \n";
	$SQL_QUERY .= "WHERE core_task__id='". sm_secure_string_sql( $core_task__id)."'";

	$result = $GLOBALS["SM_PDO"]->query( $SQL_QUERY ) or sqlerr("core_task_dane()",$SQL_QUERY);
	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

#
##
#

function core_task_fetch_all() {

	$SQL_QUERY  = "SELECT core_task.*, content_user__firstname, content_user__surname \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_task AS core_task \n";
	$SQL_QUERY .= "LEFT JOIN ".DB_TABLEPREFIX."_content_user AS content_user ON content_user.content_user__id = core_task.record_create_id \n";
	$SQL_QUERY .= "ORDER BY core_task.record_create_date";

	$result = $GLOBALS["SM_PDO"]->query( $SQL_QUERY ) or sqlerr("core_task_fetch_all()",$SQL_QUERY);
	return ($result->rowCount()>0 ? $result : 0);
}

#
##
#

function core_task_fetch_waiting($limit=5) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_task \n";
	$SQL_QUERY .= "WHERE core_task__status=1 \n";
	$SQL_QUERY .= "ORDER BY record_create_date DESC \n";
	$SQL_QUERY .= "LIMIT $limit ";
	$result = $GLOBALS["SM_PDO"]->query($SQL_QUERY) or sqlerr("core_task_fetch_waiting()",$SQL_QUERY);
	return ($result->rowCount()>0 ? $result : 0);
}

#
##
#

function core_task_fetch_active() {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_task \n";
	$SQL_QUERY .= "WHERE core_task__status=1 \n";
	$SQL_QUERY .= "ORDER BY record_create_date DESC ";
	$result = $GLOBALS["SM_PDO"]->query($SQL_QUERY) or sqlerr("core_task_fetch_active()",$SQL_QUERY);
	return ($result->rowCount()>0 ? $result : 0);
}

#
##
#

function core_task_result( $core_task__id, $message ) {

	$SQL_QUERY  = "UPDATE ".DB_TABLEPREFIX."_core_task \n";
	$SQL_QUERY .= "SET core_task__status=-1, \n";
	$SQL_QUERY .= "    core_task__result='". sm_secure_string_sql( $message)."', \n";
	$SQL_QUERY .= "    record_modify_date='".time()."', \n";
	$SQL_QUERY .= "    record_modify_id=NULL \n";
	$SQL_QUERY .= "WHERE core_task__id='". sm_secure_string_sql( $core_task__id)."' \n";

	if ( $GLOBALS["SM_PDO"]->query( $SQL_QUERY ) or sqlerr("core_task_result()",$SQL_QUERY) )
		return $core_task__id;
}

?>