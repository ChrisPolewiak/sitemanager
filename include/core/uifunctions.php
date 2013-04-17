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
function sm_inputfield( $type, $title, $help, $id, $name, $value, $size, $disabled=false, $validation=false, $prepend=false, $append=false, $rows=3, $options="", $xss_secured=1 ) {
	global $SM_ADMIN_PANEL;

	if($xss_secured) {
		$value = sm_secure_string_xss($value);
	}

	$sizes = array("min"=>1, "small"=>1, "medium"=>1, "large"=>1, "xlarge"=>1, "xxlarge"=>1, "block-level"=>1);
	if(!size || !$sizes[$size]) {
		$size = "medium";
	}
	$validations = array("warning"=>1, "error"=>1, "info"=>1, "success"=>1);
	if(!validation || !$validations[$validation]) {
		$validation = "";
	}

	$prepend_html1 = $prepend_html2 = "";
	if($prepend || $append) {
		$prepend_html1 .= "<div class=\"".($prepend?"input-prepend":"")." ".($append?"input-append":"")."\">";
		if($prepend)
			$prepend_html1 .= "<span class=\"add-on\">".$prepend."</span>";
		if($append)
			$prepend_html2 .= "<span class=\"add-on\">".$append."</span>";
		$prepend_html2 .= "</div>";
	}	

	switch($type) {
		case "htmleditor":
			$html  = "<div class=\"control-group\">";
			$html .= "<label class=\"control-label\" for=\"".$id."\">".$title."</label>";
			$html .= "<span class=\"help-block\">".$help."</span>";
			$html .= "<script src=\"/staff/htmleditor/ckeditor.js\"></script>";
			$html .= "<div id=\"".$id."\" contenteditable=\"true\" style=\"height:200px;padding:10px;border:1px solid #ccc;\">";
			$html .= $value;
			$html .= "</div>";
			$html .= "<script>";
			$html .= "CKEDITOR.replace( '".$id."', {\n";
			$html .= "	filebrowserBrowseUrl: '/".$SM_ADMIN_PANEL."/contentfile_popup.php',\n";
			$html .= "	filebrowserUploadUrl: '/".$SM_ADMIN_PANEL."/contentfile_popup.php',\n";
			$html .= "	filebrowserWindowWidth: '90%',\n";
			$html .= "	filebrowserWindowHeight: '70%'\n";
			$html .= "});\n";
			$html .= "</script>\n";
			
			break;
		case "checkbox":
			$html  = "<label class=\"checkbox\" for=\"".$id."\">";
			$html .= $prepend_html1;
			$html .= "<input type=\"checkbox\" id=\"".$id."\" name=\"".$name."\" ".($value?"checked":"")." ".($disabled?"disabled":"").">";
			$html .= $prepend_html2;
			$html .= $title;
			$html .= "<span class=\"help-block\">".$help."</span>";
			$html .= "</label>";
			break;

		case "checkbox-multi":
			$html  = "<div class=\"control-group\">";
			$html .= "<label class=\"control-label\">".$title."</label>";
			$html .= "<span class=\"help-block\">".$help."</span>";
			$html .= "<span class=\"help-block\">".$help."</span>";
			foreach($options AS $k=>$v) {
				$html .= "<label class=\"checkbox\" for=\"".$id."-".$k."\">";
				$html .= $prepend_html1;
				$html .= "<input type=\"checkbox\" id=\"".$id."-".$k."\" name=\"".$name."[".$k."]\" ".($value[$k]?"checked":"")." ".($disabled?"disabled":"").">";
				$html .= $prepend_html2;
				$html .= $v;
				$html .= "</label>";
			}
			$html .= "</div>";
			break;

		case "select": case "select-multi":
			$html  = "<div class=\"control-group\">";
			$html .= "<label class=\"control-label\" for=\"".$id."\">".$title."</label>";
			$html .= "<span class=\"help-block\">".$help."</span>";
			if($type=="select") {
				$html .= "<select id=\"".$id."\" name=\"".$name."\" class=\"input-".$size."\" ".($disabled?"disabled":"").">";
			}
			elseif($type=="select-multi") {
				$html .= "<select multiple size=\"".$rows."\" id=\"".$id."\" name=\"".$name."\" class=\"input-".$size."\" ".($disabled?"disabled":"").">";
			}
			foreach($options AS $k=>$v) {
				$html .= "<option value=\"".$k."\" ".($k==$value?"selected":"").">".$v."</option>";
			}
			$html .= "</select>";	
			$html .= "</div>";
			break;

		case "text": case "password": case "calendar": case "file": case "file-multi":
			$html  = "<div class=\"control-group\">";
			$html .= "<label class=\"control-label\" for=\"".$id."\">".$title."</label>";
			$html .= "<span class=\"help-block\">".$help."</span>";
			$html .= $prepend_html1;
			if($type=="text") {
				$html .= "<input type=\"text\" id=\"".$id."\" name=\"".$name."\" value=\"".$value."\" class=\"input-".$size."\" ".($disabled?"disabled":"").">";
			}
			elseif($type=="password") {
				$html .= "<input type=\"password\" id=\"".$id."\" name=\"".$name."\" class=\"input-".$size."\" ".($disabled?"disabled":"").">";
			}
			elseif($type=="file") {
				$html .= "<input type=\"file\" id=\"".$id."\" name=\"".$name."\" class=\"input-".$size."\" ".($disabled?"disabled":"").">";
			}
			elseif($type=="file-multi") {
				$html .= "<input type=\"file\" id=\"".$id."\" name=\"".$name."\" multiple class=\"input-".$size."\" ".($disabled?"disabled":"").">";
			}
			elseif($type=="calendar") {
				$html .= "<input type=\"text\" id=\"".$id."\" autocomplete=\"off\" name=\"".$name."\" value=\"".$value."\" class=\"input-".$size."\" ".($disabled?"disabled":"").">";
				$html .= "<script type=\"text/javascript\">\n";
				$html .= "	\$(function() {\n";
				$html .= "		\$('#".$id."').datepicker({\n";
				$html .= "			showButtonPanel: true,\n";
				$html .= "			dateFormat: 'yy-mm-dd',\n";
				$html .= "			currentText: 'Now',\n";
				$html .= "			changeMonth: true,\n";
				$html .= "			changeYear: true\n";
				$html .= "		});\n";
				$html .= "	});\n";
				$html .= "</script>\n";
			}
			$html .= $prepend_html2;
			$html .= "</div>";
			break;

		case "textarea":
			$html = "<div class=\"control-group\">";
			$html .= "<label class=\"control-label\" for=\"".$id."\">".$title."</label>";
			$html .= $prepend_html1;
			$html .= "<span class=\"help-block\">".$help."</span>";
			$html .= "<textarea id=\"".$id."\" name=\"".$name."\" class=\"input-".$size."\" rows=\"".$rows."\" ".($disabled?"disabled":"").">".$value."</textarea>";
			$html .= $prepend_html2;
			$html .= "</div>";
			break;
	}

	if($disabled) {
		$html .= "<input type=\"hidden\" name=\"".$name."\" value=\"".$value."\">";
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