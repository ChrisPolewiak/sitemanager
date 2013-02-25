<?

function core_translation_add( $dane ) {
	$dane["core_translation__id"] = "0";
	return core_translation__edit( $dane );
}

#
##
#

function core_translation_edit( $dane ) {
	$dane = trimall($dane);

	if ($dane["core_translation__id"]) {
		$tmp_dane = core_translation__dane( $dane["core_translation__id"] );
		core_changed_add( $core_translation__id, "core_translation", $tmp_dane, "edit" );
	}
	else {
		$dane["core_translation__id"] = uuid();
		core_changed_add( $core_translation__id, "core_translation", $tmp_dane="", "add" );
	}

	$dane["record_create_date"] = $dane["core_translation__id"] ? $tmp_dane["record_create_date"] : time();
	$dane["record_create_id"]   = $dane["core_translation__id"] ? $tmp_dane["record_create_id"]   : $_SESSION["contentuser"]["id_contentuser"];
	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["contentuser"]["id_contentuser"];

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_core_translation VALUES ( \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_translation__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_translation__source"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_translation__target"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_translation__module"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_translation__lang"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."' \n";
	$SQL_QUERY .= ")\n";

	if ( $GLOBALS["SM_PDO"]->query( $sqlquery ) or sqlerr("core_translation__edit()",$SQL_QUERY) ) {
		return $dane["core_translation__id"];
	}
}

#
##
#

function core_translation_delete( $core_translation__id ) {

	if ($deleted = core_translation_dane( $core_translation__id ) ) {
		core_changed_add( $core_translation__id, "core_translation", $deleted, "del" );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_core_translation \n";
	$SQL_QUERY .= "WHERE core_translation__id = '". sm_secure_string_sql( $core_translation__id)."'";
	return $GLOBALS["SM_PDO"]->query( $sqlquery ) or sqlerr("core_translation__edit()",$SQL_QUERY);
}

#
##
#

function core_translation_get_by_source( $source, $module, $lang ) {
	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_translation \n";
	$SQL_QUERY .= "WHERE core_translation__source = binary '".addslashes($source)."' ";
	$SQL_QUERY .= "  AND core_translation__module = '". sm_secure_string_sql( $module)."'";
	$SQL_QUERY .= "  AND core_translation__lang = '". sm_secure_string_sql( $lang)."'";
	$result = $GLOBALS["SM_PDO"]->query( $sqlquery ) or sqlerr("core_translation__get_by_source()",$SQL_QUERY);
	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

#
##
#

function core_translation_fetch_by_source( $source, $module ) {
	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_translation \n";
	$SQL_QUERY .= "WHERE core_translation__source = binary '".addslashes($source)."' ";
	$SQL_QUERY .= "  AND core_translation__module = '". sm_secure_string_sql( $module)."'";
	$result = $GLOBALS["SM_PDO"]->query( $sqlquery ) or sqlerr("core_translation__get_by_source()",$SQL_QUERY);
	return ($result->rowCount()>0 ? $result : 0);
}

#
##
#

function core_translation_fetch_by_lang( $lang ) {
	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_translation \n";
	$SQL_QUERY .= "WHERE core_translation__lang = '". sm_secure_string_sql( $lang)."'";
	$SQL_QUERY .= "ORDER BY core_translation__module, core_translation__source ";
	$result = $GLOBALS["SM_PDO"]->query( $sqlquery ) or sqlerr("core_translation__fetch_by_lang()",$SQL_QUERY);
	return ($result->rowCount()>0 ? $result : 0);
}

#
##
#

function core_translation_dane( $core_translation__id ) {
	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_translation \n";
	$SQL_QUERY .= "WHERE core_translation__id='". sm_secure_string_sql( $core_translation__id)."'";
	$result = $GLOBALS["SM_PDO"]->query( $sqlquery ) or sqlerr("core_translation__dane()",$SQL_QUERY);
	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

#
##
#

function core_translation_fetch_all() {
	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_translation \n";
	$SQL_QUERY .= "ORDER BY core_translation__module, core_translation__lang, core_translation__source \n";
	$result = $GLOBALS["SM_PDO"]->query( $sqlquery ) or sqlerr("core_translation__fetch_all()",$SQL_QUERY);
	return ($result->rowCount()>0 ? $result : 0);
}

?>