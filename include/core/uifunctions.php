<?
/**
 * uifunctions
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		core
 * @category	uifunctions
 */

/**
 * @category	uifunctions
 * @package		core
 * @version		5.0.0
*/
function header_gzip_start() {
	if (strstr($_SERVER["HTTP_SERVER_VARS"]["HTTP_ACCEPT_ENCODING"], "gzip")) {
		if (!headers_sent()) {
			ob_start("gzencode");
			header("Content-Encoding: gzip");
		}
	}
}

/**
 * @category	uifunctions
 * @package		core
 * @version		5.0.0
*/
function header_gzip_end() {
	ob_end_flush();
}

/**
 * @category	uifunctions
 * @package		core
 * @version		5.0.0
*/
function sm_inputfield( $params ) {
	global $SM_ADMIN_PANEL;

/*
	$params = array(
		"type"=>$type,
		"title"=>$title,
		"help"=>$help,
		"id"=>$id,
		"name"=>$name,
		"value"=>$value,
		"size"=>$size,
		"disabled"=>false,
		"validation"=>false,
		"prepend"=>false,
		"append"=>false,
		"rows"=>3,
		"options"=>"",
		"xss_secured"=true
	)
*/

	if($params["xss_secured"]) {
		$params["value"] = sm_secure_string_xss($params["value"]);
	}

	$_sizes = array("min"=>1, "small"=>1, "medium"=>1, "large"=>1, "xlarge"=>1, "xxlarge"=>1, "block-level"=>1);
	if(!$params["size"] || !$_sizes[$params["size"]]) {
		$params["size"] = "medium";
	}
	$_validations = array("warning"=>1, "error"=>1, "info"=>1, "success"=>1);
	if(!$params["validation"] || !$_validations[$params["validation"]]) {
		$params["validation"] = "";
	}

	$_prepend_html1 = $_prepend_html2 = "";
	if($params["prepend"] || $params["append"]) {
		$_prepend_html1 .= "<div class=\"".($params["prepend"]?"input-prepend":"")." ".($params["append"]?"input-append":"")."\">";
		if($params["prepend"])
			$_prepend_html1 .= "<span class=\"add-on\">".$params["prepend"]."</span>";
		if($params["append"])
			$_prepend_html2 .= "<span class=\"add-on\">".$params["append"]."</span>";
		$_prepend_html2 .= "</div>";
	}	

	switch($params["type"]) {
		case "htmleditor":
			$html  = "<div class=\"control-group\">";
			$html .= "<label class=\"control-label\" for=\"".$params["id"]."\">".$params["title"]."</label>";
			$html .= "<span class=\"help-block\">".$params["help"]."</span>";
			$html .= "<script src=\"/staff/htmleditor/ckeditor.js\"></script>";
			$html .= "<div id=\"".$params["id"]."\" contenteditable=\"true\" style=\"height:200px;padding:10px;border:1px solid #ccc;\">";
			$html .= $params["value"];
			$html .= "</div>";
			$html .= "<script>";
			$html .= "CKEDITOR.replace( '".$params["id"]."', {\n";
			$html .= "	filebrowserBrowseUrl: '/".$SM_ADMIN_PANEL."/contentfile_popup.php',\n";
			$html .= "	filebrowserUploadUrl: '/".$SM_ADMIN_PANEL."/contentfile_popup.php',\n";
			$html .= "	filebrowserWindowWidth: '90%',\n";
			$html .= "	filebrowserWindowHeight: '70%'\n";
			$html .= "});\n";
			$html .= "</script>\n";
			
			break;
		case "checkbox":
			$html  = "<label class=\"checkbox\" for=\"".$params["id"]."\">";
			$html .= $_prepend_html1;
			$html .= "<input type=\"checkbox\" id=\"".$params["id"]."\" name=\"".$params["name"]."\" ".($params["value"]?"checked":"")." ".($params["disabled"]?"disabled":"").">";
			$html .= $_prepend_html2;
			$html .= $params["title"];
			$html .= "<span class=\"help-block\">".$params["help"]."</span>";
			$html .= "</label>";
			break;

		case "checkbox-multi":
			$html  = "<div class=\"control-group\">";
			$html .= "<label class=\"control-label\">".$params["title"]."</label>";
			$html .= "<span class=\"help-block\">".$params["help"]."</span>";
			$html .= "<span class=\"help-block\">".$params["help"]."</span>";
			foreach($options AS $k=>$v) {
				$html .= "<label class=\"checkbox\" for=\"".$params["id"]."-".$k."\">";
				$html .= $_prepend_html1;
				$html .= "<input type=\"checkbox\" id=\"".$params["id"]."-".$k."\" name=\"".$params["name"]."[".$k."]\" ".($params["value"][$k]?"checked":"")." ".($params["disabled"]?"disabled":"").">";
				$html .= $_prepend_html2;
				$html .= $v;
				$html .= "</label>";
			}
			$html .= "</div>";
			break;

		case "select": case "select-multi":


			$html  = "<div class=\"control-group\">";
			$html .= "<label class=\"control-label\" for=\"".$params["id"]."\">".$params["title"]."</label>";
			$html .= "<span class=\"help-block\">".$params["help"]."</span>";
			if($params["type"]=="select") {
				$html .= "<select id=\"".$params["id"]."\" name=\"".$params["name"]."\" class=\"input-".$params["size"]."\" ".($params["disabled"]?"disabled":"").">";
			}
			elseif($params["type"]=="select-multi") {
				$html .= "<select multiple size=\"".$rows."\" id=\"".$params["id"]."\" name=\"".$params["name"]."\" class=\"input-".$params["size"]."\" ".($params["disabled"]?"disabled":"").">";
			}
			foreach($params["options"] AS $k=>$v) {
				$html .= "<option value=\"".$k."\" ".($k === $params["value"]?"selected":"").">".$v."</option>";
			}
			$html .= "</select>";	
			$html .= "</div>";
			break;

		case "text": case "password": case "calendar": case "file": case "file-multi":
			$html  = "<div class=\"control-group\">";
			$html .= "<label class=\"control-label\" for=\"".$params["id"]."\">".$params["title"]."</label>";
			$html .= "<span class=\"help-block\">".$params["help"]."</span>";
			$html .= $_prepend_html1;
			if($params["type"]=="text") {
				$html .= "<input type=\"text\" id=\"".$params["id"]."\" name=\"".$params["name"]."\" value=\"".$params["value"]."\" class=\"input-".$params["size"]."\" ".($params["disabled"]?"disabled":"").">";
			}
			elseif($params["type"]=="password") {
				$html .= "<input type=\"password\" id=\"".$params["id"]."\" name=\"".$params["name"]."\" class=\"input-".$params["size"]."\" ".($params["disabled"]?"disabled":"").">";
			}
			elseif($params["type"]=="file") {
				$html .= "<input type=\"file\" id=\"".$params["id"]."\" name=\"".$params["name"]."\" class=\"input-".$params["size"]."\" ".($params["disabled"]?"disabled":"").">";
			}
			elseif($params["type"]=="file-multi") {
				$html .= "<input type=\"file\" id=\"".$params["id"]."\" name=\"".$params["name"]."\" multiple class=\"input-".$params["size"]."\" ".($params["disabled"]?"disabled":"").">";
			}
			elseif($params["type"]=="calendar") {
				$html .= "<input type=\"text\" id=\"".$params["id"]."\" autocomplete=\"off\" name=\"".$params["name"]."\" value=\"".$params["value"]."\" class=\"input-".$params["size"]."\" ".($params["disabled"]?"disabled":"").">";
				$html .= "<script type=\"text/javascript\">\n";
				$html .= "	\$(function() {\n";
				$html .= "		\$('#".$params["id"]."').datepicker({\n";
				$html .= "			showButtonPanel: true,\n";
				$html .= "			dateFormat: 'yy-mm-dd',\n";
				$html .= "			currentText: 'Now',\n";
				$html .= "			changeMonth: true,\n";
				$html .= "			changeYear: true\n";
				$html .= "		});\n";
				$html .= "	});\n";
				$html .= "</script>\n";
			}
			$html .= $_prepend_html2;
			$html .= "</div>";
			break;

		case "textarea":
			$html = "<div class=\"control-group\">";
			$html .= "<label class=\"control-label\" for=\"".$params["id"]."\">".$params["title"]."</label>";
			$html .= $_prepend_html1;
			$html .= "<span class=\"help-block\">".$params["help"]."</span>";
			$html .= "<textarea id=\"".$params["id"]."\" name=\"".$params["name"]."\" class=\"input-".$params["size"]."\" rows=\"".$params["rows"]."\" ".($params["disabled"]?"disabled":"").">".$params["value"]."</textarea>";
			$html .= $_prepend_html2;
			$html .= "</div>";
			break;
	}

	if($params["disabled"]) {
		$html .= "<input type=\"hidden\" name=\"".$params["name"]."\" value=\"".$params["value"]."\">";
	}

	return $html;
}

/**
 * @category	uifunctions
 * @package		core
 * @version		5.0.0
*/
function cms_core_geturl_by_name($name) {
	global $XML_CONTENTPAGE, $ENGINE;
	
	if(is_object($XML_CONTENTPAGE)){
		$xpath = $XML_CONTENTPAGE->xpath("//item[@name='$name']");
		if(sizeof($xpath)>0){
			return $ENGINE."/". (string) xml_findAttribute($xpath[0],"url");
		}
	}
	return  "";
}

?>
