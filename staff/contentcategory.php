<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$content_category__id = $_REQUEST["content_category__id"];

if ($_REQUEST["ajax"]=="content_category-sort" ) {
	foreach($_POST["content_category_sort"] AS $k=>$v){
		$tmp_content_category = content_category_get($v);
		$tmp_content_category["content_category__order"] = $k;
		content_category_change($tmp_content_category);
	}
	set_time_limit(3600);
	filearray_generator( "content_category" );
	xmlarray_generator( "content_category" );
	exit;
}

// validation
if( isset($action["add"]) || isset($action["edit"]) ){
	$dane=trimall($dane);
	if(!$dane["content_category__name"]){
		$ERROR[] = __("core", "Podaj tytuł podkategorii");
	}
}

// actions
if(!$ERROR) { 
	if ( isset($action["add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);

		$dane["content_category__order"] = content_category_get_last_order( $dane["content_category__idparent"] ) + 1;
		if( $content_category__id = content_category_add($dane)) {
			$MESSAGE = __("core", "Dodano rekord do bazy");
			$dane["content_category__id"] = $content_category__id;
			content_category_change($dane);
		}
		$action["generator"]=1;
	}
	elseif ( isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);

		if( $content_category__id = content_category_change($dane)) {
			$MESSAGE = __("core", "Zmodyfikowano rekord w bazie");
		}
		$action["generator"]=1;
	}
	elseif ( isset($action["delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);

		$content_category__id = content_category_delete($dane["content_category__id"]);
		unset($dane);
		$action["generator"]=1;
	}

	if( isset($action["generator"])) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);

		set_time_limit(3600);
		filearray_generator( "content_category" );
		xmlarray_generator( "content_category" );
		header("Location: ".$ENGINE."/".$page."?content_category__id=".$content_category__id); 
		exit;
	}
}
else {
	$content_category__id = $content_category__id ? $content_category__id : $dane["content_category__id"];
}

if(!$ERROR && $content_category__id ) {
	$dane = content_category_get($content_category__id);
}


include "_page_header5.php";

?>
					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">

						<div class="row-float">
							<div class="span3">
								<fieldset class="no-legend">
<?
$inputfield_options=array();	
if(is_array($CONTENT_CATEGORY_LONGNAME)){
	foreach($CONTENT_CATEGORY_LONGNAME AS $k=>$v) {
		$v = preg_replace( "/(.+?)@@@/is", " / .. ", $v);
		$v = preg_replace( "/(@@@)/is", " / ", $v);
		$inputfield_options[ $k ] = $v;
	}
}
?>
									<?=sm_inputfield( "select-multi", "", "", "content_category__id", "content_category__id", $content_category__id, "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=10, $inputfield_options);?>
<script>
$('#content_category__id').click(function(){
	window.location = '?content_category__id='+this.value;
});
</script>
									<a class="btn btn-mini btn-info" href="?content_category__idparent=<?=$content_category__id?>"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "nowa podkategoria")?></a>
								</fieldset>
<? if($content_category__id) { ?>
								<div class="fieldset-title">
									<div>Zmiana kolejności</div>
								</div>
								<fieldset class="no-legend">
<?	if ($result=content_category_show_parent($dane["content_category__idparent"])) { ?>
									<ul id="content_category-sort" class="sitemanager-sortable nav nav-pills nav-stacked">
<?		foreach($result AS $row){ ?>
										<li id="content_category-sort-<?=$row["content_category__id"]?>" class="sortitem">
											<a href="#"><?=$row["content_category__name"]?>
												<input type="hidden" name="content_category_sort[]" value="<?=$row["content_category__id"]?>">
											</a>
										</li>
<?		} ?>
									</ul>
<?	} ?>
<script type="text/javascript">
$(document).ready(
	function() {
		$("#content_category-sort").sortable({
			accept: 'sortitem',
			placeholder: "ui-state-highlight",
			axis: 'y',
			cursor: 's-resize',
			opacity: 0.6,
			start: function ( event, ui ) {
				height = $('#'+ui.item.attr('id')).height();
				$("#content_category-sort .ui-state-highlight").css({'height': height+'px'});
			},
			update: function (sorted) {
				var order = $('#sm-form').serialize();
				$.ajax({
					url: "<?=$page?>?ajax=content_category-sort", type: "POST", data: order,
					success: function(html){
					//	alert(html);
					}
				});
			}
		});
	}
);
</script>
								</fieldset>

<?	if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
								<div class="btn-toolbar">
									<input type=hidden name="content_category__id" value="<?=$content_category__id?>">
									<a class="btn btn-mini btn-info" id="action-reorder"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "zapisz kolejność")?></a>
								</div>
<?	} ?>
<?
}
?>

							</div>
							<div class="span9">

								<div class="fieldset-title">
									<div>Edycja pozycji</div>
								</div>
								<fieldset class="no-legend">
<?
if (!$content_category__id){
	$dane["content_category__idparent"] = $content_category__idparent;
}
?>
									<?=sm_inputfield( "text", "Nazwa", "", "dane_content_category__name", "dane[content_category__name]", $dane["content_category__name"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=10);?>
									<?=sm_inputfield( "text", "Komentarz", "", "dane_content_category__comment", "dane[content_category__comment]", $dane["content_category__comment"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=10);?>

<?
$inputfield_options=array();
$inputfield_options[0]="START";
if(is_array($CONTENT_CATEGORY_LONGNAME)){
	foreach($CONTENT_CATEGORY_LONGNAME AS $k=>$v) {
		$v = preg_replace( "/(@@@)/is", " / ", $v);
		if($content_category__id != $k) {

			$_error=false;
			$path = unserialize($CONTENT_CATEGORY[ $k ]["path"]);
			foreach($path AS $pk=>$pv) {
				if( $content_category__id == $pv["content_category__id"]) {
					$_error=true;
				}
			}
			if(!$_error) {
				$inputfield_options[ $k ] = $v;
			}
		}
	}
}
?>
									<?=sm_inputfield( "select", "Kategoria nadrzędna", "", "dane_content_category__idparent", "dane[content_category__idparent]", $dane["content_category__idparent"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=10, $inputfield_options );?>
								</fieldset>

<? if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
								<div class="btn-toolbar">
									<input type=hidden name="dane[content_category__id]" value="<?=$content_category__id?>">
									<input type=hidden name="content_category__id" value="<?=$content_category__id?>">
<?	if ($content_category__id) { ?>
									<a class="btn btn-normal btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
									<a class="btn btn-normal btn-danger" id="action-delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<?	} else {?>
									<a class="btn btn-normal btn-info" id="action-add"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD")?></a>
<?	} ?>
									</div>
<? } ?>
								</div>
							</div>
						</div>
<script>
$('#action-edit').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[edit]" value=1>');
	$('#sm-form').submit();
});
$('#action-delete').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[delete]" value=1>');
	$('#sm-form').submit();
});
$('#action-add').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[add]" value=1>');
	$('#sm-form').submit();
});
</script>
					</form>

<? include "_page_footer5.php" ?>