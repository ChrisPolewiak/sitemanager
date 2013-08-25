<?
/**
 * generator
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		core
 * @category	generator
 */

/**
 * @category	mail
 * @package		core
 * @version		5.0.0
*/
function filearray_generator_recurent( $id, $fp, $db ) {
	global $html_spec;

	eval ( " \$result = ".$db."_by_parent(\"".$id."\"); ");

	if($result) {
		$counter=0;
		while($row=$result->fetch(PDO::FETCH_ASSOC)) {

			if(!$table_cols) {
				foreach($row AS $k=>$v) {
					if($k!="record_create_date" && $k!="record_create_id" && $k!="record_modify_date" && $k!="record_modify_id" && !ereg($db."__path")) {
						if(preg_match("/^".$db."__(.+)$/i", $k, $tmp) ) {
							$table_cols[ $tmp[1] ] = $k;
						}
						else {
							$table_cols[ $k ] = $k;
						}
					}
				}
			}

			$ilosc = $ilosc_by_cat = 0;
			eval ( " \$ilosc = ".$db."_count_by_parent(\"".$row[$db."__id"]."\"); ");

			$string  = "\$" . strtoupper($db) . "[\"" . $row[$db."__id"] . "\"] = ";
			$string .= "array(";
			foreach($table_cols AS $k=>$v) {
				$string .= "\"". $k ."\"=>\"". ereg_replace("(\")", "\\\"", $row[$v]) ."\",";
			}
			$string .= "\"size\"=>\""   . sizeof(json_decode( $row[$db."__path"], $assoc=true)) . "\",";
			$string .= "\"path\"=>\"" . ereg_replace("(\")", "\\\"", $row[$db."__path"])."\");";

			fputs($fp, $string);
			$path = json_decode( $row[$db."__path"], $assoc=true);
			$nazwa = "";
			if (is_array($path)) {
				foreach($path AS $key=>$val) { 
					$nazwa = $val[$db."__name"].( $nazwa ? "@@@".$nazwa : "" );
				}
				$html_spec .= "\$".strtoupper($db)."_LONGNAME[\"".$row[$db."__id"]."\"] = \"".ereg_replace("(\")", "\\\"", $nazwa)."\";\n";
			}
			fputs($fp, "\n");

			filearray_generator_recurent( $row[$db."__id"], $fp, $db );
		}
	}
}

/**
 * @category	mail
 * @package		core
 * @version		5.0.0
*/
function filearray_generator( $db ) {
	global $ROOT_DIR;
	global $html_spec;

	$html_spec = "";
	$fp = fopen($ROOT_DIR."/cache/".$db."_tmp.php","w") or error("nie moge otworzyc - ".$ROOT_DIR."/cache/".$db."_tmp.php");
	fputs($fp, "<"."?php\n# include file\n");
	fputs($fp, "# DON'T EDIT\n");
	fputs($fp, "#\n");
	fputs($fp, "# ".$db."\n");
	fputs($fp, "#\n");
	fputs($fp, "\n");
	filearray_generator_recurent(0, $fp, $db);
	fputs($fp, "\n");
	fputs($fp, $html_spec);
	fputs($fp, "\n");
	fputs($fp, "?".">");
	fclose($fp);
	if (file_exists($ROOT_DIR."/cache/".$db.".php")){
		unlink($ROOT_DIR."/cache/".$db.".php");
	}
	rename($ROOT_DIR."/cache/".$db."_tmp.php", $ROOT_DIR."/cache/".$db.".php"); 
	return 1;
}

/**
 * @category	mail
 * @package		core
 * @version		5.0.0
*/
function xmlarray_generator( $db ) {
	global $ROOT_DIR;

	$fp = fopen($ROOT_DIR."/cache/".$db."_tmp.xml","w") or error("nie moge otworzyc - ".$ROOT_DIR."/cache/".$db."_tmp.xml");
	fputs($fp, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
	fputs($fp, "<".$db.">\n");
	fputs($fp, xmlarray_generator_recurent(0, $db) );
	fputs($fp, "</".$db.">\n");
	fclose($fp);
	if (file_exists($ROOT_DIR."/cache/".$db.".xml")){
		unlink($ROOT_DIR."/cache/".$db.".xml");
	}
	rename($ROOT_DIR."/cache/".$db."_tmp.xml", $ROOT_DIR."/cache/".$db.".xml");
	return 1;
}

/**
 * @category	mail
 * @package		core
 * @version		5.0.0
*/
function xmlarray_generator_recurent( $id, $db ) {

	eval ( " \$result = ".$db."_by_parent(\"".$id."\"); ");

	if($result) {
		while($row=$result->fetch(PDO::FETCH_ASSOC)) {

if(!$table_cols) {
	foreach($row AS $k=>$v) {
		if($k!="record_create_date" && $k!="record_create_id" && $k!="record_modify_date" && $k!="record_modify_id" && !ereg($db."__path")) {
			if(preg_match("/^".$db."__.+)$/i", $k, $tmp) ) {
				$table_cols[ $tmp[1] ] = $k;
			}
			else {
				$table_cols[ $k ] = $k;
			}
		}
	}
}

			$path = json_decode($row[$db."_path"], $assoc=true);
			$xml .= str_repeat("  ", sizeof($path));
			$xml .= "<item size=\"".sizeof($path)."\" ";
			$add_childs=false;
			foreach($table_cols AS $k1=>$v2) {

				$k = $v2;
				$v = $row[$v2];
				$k = preg_replace("/(_*".$db."*__)/", "", $k);
				$v = preg_replace("/(\&)/s", "&amp;", $v);
				$v = preg_replace("/(\")/s", "&quot;", $v);
				if ( strlen($v)<=100 && $k!="path" ) {
					$xml .= "$k=\"".$v."\" ";
				}
				else {
					$add_childs=true;
				}
				if ($k=="params"){
					$_params = split("([\|;])", $v);
					foreach($_params AS $_param) {
						list($k2,$v2) = split("=",$_param);
						if ($k2 && $v2) {
							$xml .= "param_$k2=\"$v2\" ";
						}
					}
				}
			}
			$tmp = xmlarray_generator_recurent($row[$db."__id"], $db);
			if ($tmp || $add_childs){
				$xml .= ">\n";
				if($add_childs) {
					foreach($row AS $k=>$v) {
						$k = preg_replace("/(_*".$db."*__)/", "", $k);
						//$v = iconv("ISO-8859-2","UTF-8", $v);
						if ((strlen($v)>100 && $k!="params") || $k=="path") {
							$xml .= str_repeat("  ", sizeof($path)+1);
							$xml .= "<".$k."><![CDATA[".$v."]]></".$k.">\n";
						}
					}
				}
				$xml .= $tmp;
				$xml .= str_repeat("  ", sizeof($path));
				$xml .= "</item>\n";
			}
			else {
				$xml .= "/> \n";
			}
		}
		return $xml;
	}
}

/**
 * @category	mail
 * @package		core
 * @version		5.0.1
*/
function sm_serialize_array_generator_recurent( $id, $db ) {
	global $html_spec;
	global $SERIALIZE_ARRAY;

	eval ( " \$result = ".$db."_by_parent( $id ); ");

	if($result) {
			$counter=0;
		while($row=$result->fetch(PDO::FETCH_ASSOC)) {
			$counter++;
			// pierwszy array
			$row[$db."_order"] = $counter;

			eval ( " ".$db."_edit( \$row ); ");
			eval ( " \$row = ".$db."_get( \$row[".$db."__id\"] ); " );

			$ilosc = $ilosc_by_cat = 0;
			eval ( " \$ilosc = ".$db."_count_by_parent( ".$row[$db."__id"]." ); ");

			$SERIALIZE_ARRAY[$row[$db."__id"]] = array(
				"id"=>       $row[$db."__id"],
				"par"=>      $row[$db."__idparent"],
				"order"=>    $row[$db."__order"],
				"top"=>      $row[$db."__idtop"],
				"stat1"=>    $ilosc,
				"stat2"=>    $ilosc_by_cat,
				"size"=>     sizeof(json_decode( $row[$db."__path"], $assoc=true)),
				"name"=>     ereg_replace("(\")", "\\\"", $row[$db."__name"]),
				"namelong"=> ereg_replace("(\")", "\\\"", $row[$db."__namelong"]),
				"url"=>      ereg_replace("(\")", "\\\"", $row[$db."__url"]),
				"path"=>     json_decode( $row[$db."__path"], $assoc=true),
			);
			if($row[$db."_params"]) {
				$SERIALIZE_ARRAY[$row[$db."__id"]]["field_content_page__params"] = ereg_replace("(\")", "\\\"", $row[$db."__params"]);
			}
			sm_serialize_array_generator_recurent( $row[$db."__id"], $db );
		}
	}
}

/**
 * @category	mail
 * @package		core
 * @version		5.0.0
*/
function sm_serialize_array_generator( $db ) {
	global $CACHE_DIR;
	global $SERIALIZE_ARRAY;

	$cache_file = $CACHE_DIR."/cache-".$db.".json";
	
	$fp = fopen($cache_file.".tmp","w") or trigger_error(__("core", "Nie mogę otworzyć").": ".$cache_file.".tmp", E_USER_ERROR);
	sm_serialize_array_generator_recurent(0, $db);
	fputs($fp, json_encode($SERIALIZE_ARRAY));
	unset($SERIALIZE_ARRAY);
	fclose($fp);
	if (file_exists($cache_file)){
		unlink($cache_file);
	}
	rename($cache_file.".tmp", $cache_file); 
	return 1;
}

?>