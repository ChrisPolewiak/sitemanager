<?

if( $_xpar = core_configadminview_get_by_adminview($menu_id) ) {

	$params["button_back"] = $_xpar["core_configadminview__button_back"];
	$params["button_addnew"] = $_xpar["core_configadminview__button_addnew"];
	$params["dbname"] = $_xpar["core_configadminview__dbname"];
	$params["function_fetch"] = $_xpar["core_configadminview__function"]."()";
	$params["mainkey"] = $_xpar["core_configadminview__mainkey"];
	$params["row_per_page_default"] = $_xpar["core_configadminview__rowperpage"];
	if ($_xres = core_configadminviewcolumn_fetch_by_adminview( $_xpar["core_configadminview__id"] ) ) {
		unset($params["columns"]);
		while($_xrow=$_xres->fetch(PDO::FETCH_ASSOC)){
			$params["columns"][] = array(
				"title" => $_xrow["core_configadminviewcolumn__title"],
				"width" => $_xrow["core_configadminviewcolumn__width"],
				"value" => $_xrow["core_configadminviewcolumn__value"],
				"order" => $_xrow["core_configadminviewcolumn__order"],
			);
		}
	}
}

$params["actions"]["edit"]   = $params["actions"]["edit"] ? $params["actions"]["edit"] : array( "display" => true );
$params["actions"]["delete"] = $params["actions"]["delete"] ? $params["actions"]["delete"] : array("display" => true );
$params["action-doubleclick"] = isset($params["action-doubleclick"]) ? $params["action-doubleclick"] : true;
$params["datatype"] = $params["datatype"] ? $params["datatype"] : "sql";

$param["row_per_page_default"] = $param["row_per_page_default"] ? $param["row_per_page_default"] : 25;
eval(" \$result = ".$params["function_fetch"]."; ");

?>
					<div class="btn-toolbar">
<?
	if($params["button_addnew"]) { ?><a class="btn btn-small btn-info" href="?<?=$params["mainkey"]?>=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "DATATABLE__NEW_RECORD")?></a><? }

	if(is_array($datatable_btn_add)) {
		foreach($datatable_btn_add AS $btn) {
?>
						<a class="btn btn-small btn-<?=$btn["color-class"]?>" href="<?=$btn["url"]?>"><i class="icon-<?=$btn["icon"]?> icon-white"></i>&nbsp;<?=$btn["title"]?></a>
<?
		}
	}
?>
					</div>

					<table class="table table-striped table-bordered" id="dataTable">
						<thead>
							<tr>
<?
	foreach($params["columns"] AS $column) {
?>
								<th nowrap></th>
<?
	}

	foreach($params["actions"] AS $act_k=>$act_v) {
		if ($act_k == "edit" && $act_v["display"]) {
?>
								<th></th>
<?
		}
		elseif ($act_k == "delete" && $act_v["display"]) {
?>
								<th></th>
<?
		}
		elseif ($act_v["display"]) {
?>
								<th></th>
<?
		}
	}

?>	
							</tr>
						</thead>
						<tbody>
<?
	$FUNCTION_ALLOW = array(
		"date"=>array(
			"function"=>"date",
			"params"=>array(
				"0"=>"Y-m-d H:i:s",
				"1"=>"_VALUE_"
			),
		),
		"image"=>array(
			"function"=>"sm_datatable_image",
			"params"=>array(
				"0"=>"_VALUE_"
			),
		),
		"substr"=>array(
			"function"=>"substr",
			"params"=>array(
				"0"=>"_VALUE_",
				"1"=>"0",
				"2"=>"_ARGS_",
			),
		),
	);

	function sm_datatable_image( $id ) {
		return "<img src=\"/staff/__contentfile_image_resize.php?id=".$id."&w=40\">";
	}


	if($result) {
		if ( $params["datatype"]=="sql" ) {
			while($row=$result->fetch(PDO::FETCH_ASSOC)) {
				$datatable_data[] = $row;
			}
		}
		elseif( $params["datatype"]=="array" ) {
			$datatable_data = $result;
		}
		
		foreach($datatable_data AS $row) {
			if($subidk && $subidv) {
				if($row[$subidk]!=$subidv) {
					continue;
				}
			}

			echo "							<tr id=\"".$row[ $params["mainkey"] ]."\">\n";
			foreach($params["columns"] AS $column) {
				echo "								<td>";
				if (preg_match_all("/(%%.+?%%)/s", $column["value"], $tmp)) {
					unset($_find2); 
					unset($_repl2);
					unset($_function); 
					unset($_args);
					foreach($tmp[1] AS $_print_string) {
						$_print_elements = preg_replace("/%%(.+)%%/", "\\1", $_print_string);
						$_print_string = preg_replace("/(\/)/s", "\/", $_print_string);
						if(preg_match("/\[(\w+)=*(.*)\](.*)%%/", $_print_string, $tmp)) {
							$_print_elements = $tmp[3];
							$_function = $tmp[1];
							$_args = $tmp[2];
						}
						preg_match_all("/(\{.+?\})/", $_print_elements, $tmp);
						$_print_subelements = $tmp[0];
						unset($_find); 
						unset($_repl);
						foreach($_print_subelements AS $v) {
							$v2 = preg_replace("/{(.+)}/", "\\1", $v);
							$_find[] = "/".addslashes($v)."/";
							$_repl[] = $row[$v2];
						}
						$_print_elements_fill = preg_replace($_find, $_repl, $_print_elements);
						if($_function && $FUNCTION_ALLOW[$_function]) {

							$str = $FUNCTION_ALLOW[$_function]["function"]."(";
							$_str = "";
							foreach($FUNCTION_ALLOW[$_function]["params"] AS $param) {
								$_str .= $_str ? ", " : "";
								if($param=="_VALUE_")
									$_str .= "\"".$_print_elements_fill."\"";
								elseif($param=="_ARGS_")
									$_str .= "\"".$_args."\"";
								else
									$_str .= "\"$param\"";
							}
							$str .= $_str . ");";
							eval("\$column[value] = ".$str);
						}
						//$_print_string = addslashes($_print_string);
						$_print_string = preg_replace("/(\[)/","\[",$_print_string);
						$_print_string = preg_replace("/(\])/","\]",$_print_string);
						$values[$_col] = $row[$_col];
						$_find2[] = "/".$_print_string."/";
						$_repl2[] = $_print_elements_fill;
					}
					$column["value"] = preg_replace($_find2, $_repl2, $column["value"]);
					if($column["valuesmatch"]) {
						$column["value"] = $column["valuesmatch"][$column["value"]];
					}
				}
				echo $column["value"];
				echo "</td>\n";
			}

			foreach($params["actions"] AS $act_k=>$act_v) {
				if ($act_k == "edit" && $act_v["display"]) {
?>
								<td><a href="?<?=$params["mainkey"]?>=<?=$row[ $params["mainkey"] ]?>"><i class="icon-edit"></i></a></td>
<?
				}
				elseif ($act_k == "delete" && $act_v["display"]) {
?>
								<td><a href="?<?=$params["mainkey"]?>=<?=$row[ $params["mainkey"] ]?>&action[delete]=1" onClick="return confDelete()" title="<?=__("CORE","BUTTON__DELETE")?>"><i class="icon-remove"></i></a></td>
<?
				}
				elseif ($act_v["display"]) {
?>
								<td><a href="#" class="<?=$act_v["class"]?>"><i class="icon-<?=$act_v["icon"]?>"></i></a></td>
<?
				}
			}
?>
							</tr>
<?				
        }
    }
?>
						</tbody>
					</table>
						
<script>

var oTable;
var asInitVals = new Array();

$().ready(function() {
	oTable = jQuery('#dataTable').dataTable( {
		"sPaginationType": "bootstrap",

		"bStateSave": true,
		"bSortClasses": true,
<? if($params["sScrollY"]) { ?>
		"sScrollY": "<?=$params["sScrollY"]?>",
<? } ?>
		"aoColumns": [
<?
	foreach($params["columns"] AS $id=>$column) {
?>
			{ "bSortable": true, "sTitle": "<?=$column["title"]?>", "bSearchable": <?=$column["order"]?"true":"false"?>, "sWidth": "<?=$column["width"]?>", "sClass": "<?=$column["align"]?>", "overflow": "hidden" },
<?
	}

	foreach($params["actions"] AS $act_k=>$act_v) {
		if ($act_k == "edit" && $act_v["display"]) {
?>
			{ "bSortable": false, "bSearchable": false, "sWidth": "5", },
<?
		}
		elseif ($act_k == "delete" && $act_v["display"]) {
?>
			{ "bSortable": false, "bSearchable": false, "sWidth": "5", },
<?
		}
		elseif ($act_v["display"]) {
?>
			{ "bSortable": false, "bSearchable": false, "sWidth": "5", },
<?
		}
	}
?>
		],
		"oLanguage": {
			"sProcessing":   "<?=__("core", "DATATABLE__PROCESSING")?>",
			"sLengthMenu":   "<?=__("core", "DATATABLE__SCRIPT_SLENGTHMENU")?>",
			"sZeroRecords":  "<?=__("core", "DATATABLE__SCRIPT_SZERORECORDS")?>",
			"sInfo":         "<?=__("core", "DATATABLE__SCRIPT_SINFO")?>",
			"sInfoEmpty":    "<?=__("core", "DATATABLE__SCRIPT_SINFOEMPTY")?>",
			"sInfoFiltered": "<?=__("core", "DATATABLE__SCRIPT_SINFOFILTERED")?>",
			"sInfoPostFix":  "",
			"sSearch":       "<?=__("core", "DATATABLE__SCRIPT_SSEARCH")?>",
			"sUrl":          "",
			"oPaginate": {
				"sFirst":    "<?=__("core", "DATATABLE__SCRIPT_OPAGINATE_SFIRST")?>",
				"sPrevious": "<?=__("core", "DATATABLE__SCRIPT_OPAGINATE_SPREVIOUS")?>",
				"sNext":     "<?=__("core", "DATATABLE__SCRIPT_OPAGINATE_SNEXT")?>",
				"sLast":     "<?=__("core", "DATATABLE__SCRIPT_OPAGINATE_SLAST")?>"
			}
		},
		"bAutoWidth": false,
		"aaSorting": [[ 1, "asc" ]],
		"bPaginate": true,
		"bLengthChange": <?=($params["bLengthChange"]?$params["bLengthChange"]:"true")?>,
		"iDisplayLength": <?=$params["row_per_page_default"]?$params["row_per_page_default"]:50?>

	} );
<? if($params["action-doubleclick"]) { ?>
	$('#dataTable tr').dblclick(function() {
		id = this.id;
		window.location = '?<?=$params["mainkey"]?>=' + id;
	});
<? } ?>

	$('#dataTable tbody tr').css({cursor:'pointer'});

});
</script>

<?
