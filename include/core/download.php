<?
/**
 * download
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		core
 * @category	download
 */

/**
 * @category	download
 * @package		core
 * @version		5.0.0
*/
function download_file( $type, $id, $method ) {
	global $SOFTWARE_INFORMATION, $ROOT_DIR, $user, $ENGINE;
	
	$method = $method ? $method : 1;
	if($row = contentfile_dane( $id ) ) {
		if ( $row["contentfile_private"] == 1 && (!is_array($user) || $user["user_status"]!="1")  ) header("Location: ".$ENGINE."/loguj?backto=".$backto);;
	}
	if ($type){
		if (function_exists( $type."_dane") ) {
			eval(" \$dane = ".$type."_dane(".$id."); ");
			if ($dane) {
				$filepath = $ROOT_DIR."/html".$dane[$type."_filepath"];
				$filetype = $dane[$type."_filetype"];
				$filename = $dane[$type."_filename"];
				$filesize = $dane[$type."_filesize"];
			}
		}
		else {
			echo "Niepoprawne wywołanie";
		}
	}

	if (is_file($filepath)) {
		$fp = fopen ($filepath, "rb");
		$file_content = fread ($fp, filesize($filepath));
		fclose ($fp);
		header("Expires: ".date("r")."\n");
		header("Last-Modified: ".gmdate("D,d M YH:i:s")." GMT\n");
		if(!$_SERVER["HTTPS"]) {
			header("Pragma: no-cache\n");
		}
		header("Content-type: ".$filetype."\n");
		$row = contentfile_dane( $id );
		if($method == 1 && $row["contentfile_filetype"] == "application/pdf"){
			header("Content-Disposition: inline; filename=".str_replace(" ", "_", $filename)."\n");
		}
		elseif($method == 2 || $method == 1 && $row["contentfile_filetype"] != "application/pdf"){
			header("Content-Disposition: attachment; filename=".str_replace(" ", "_", $filename)."\n");
		}
		header("Content-Description: ".$SOFTWARE_INFORMATION["author"]."/".$SOFTWARE_INFORMATION["name"]." Generated Data" );
		echo $file_content;
		exit;
	}
}

if ( isset($_REQUEST["actiondownload"]) ){
	if (list($__type,$__id,$_method)=split("\|", $_REQUEST["actiondownload"])){
		download_file( $__type, $__id, $_method );
	}
}

?>