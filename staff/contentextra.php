<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$content_extra__id = $_REQUEST["content_extra__id"];
$danecontent_extralist = $_REQUEST["danecontent_extralist"];
$content_extralist__id = $_REQUEST["content_extralist__id"];

if ( isset($action["add"]) || isset($action["edit"]) ) {
	$dane=trimall($dane);
	if(!$dane["content_extra__object"]){
		$ERROR[] = __("core", "Wybierz obiekt");
	}
	if(!$dane["content_extra__name"]){
		$ERROR[] = __("core", "Podaj nazwę pola");
	}
	if(!$dane["content_extra__dbname"]){
		$ERROR[] = __("core", "Podaj nazwę pola w bazie danych");
	}
	if(!$dane["content_extra__dbtype"]){
		$ERROR[] = __("core", "Wybierz typ pola w bazie danych");
	}
	if(!$dane["content_extra__info"]){
		$ERROR[] = __("core", "Podaj opis pola do formularza");
	}
	if(!$dane["content_extra__input"]){
		$ERROR[] = __("core", "Wybierz typ pola w formularzu");
	}
	if(! preg_match("/^[\d\w\_]+$/", $dane["content_extra__dbname"])) {
		$ERROR[] = __("core", "Nieprawidłowe znaki w nazwie systemowej");
	}
}

if(!is_array($ERROR)) {
	if ( isset($action["add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		# sm_sql_transaction_begin();
		$content_extra__id = content_extra_add($dane);
	}
	elseif ( isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$prev_dane = content_extra_dane($dane["content_extra__id"]);
		# sm_sql_transaction_begin();
		$content_extra__id = content_extra_edit($dane);
	}
	elseif ( isset($action["delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$prev_dane = content_extra_dane($content_extra__id);
		# sm_sql_transaction_begin();
		$content_extra__id = content_extra_delete($content_extra__id);
		unset($content_extra__id);
	}
	elseif ( isset($action["extralist_add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_extralist_add($danecontent_extralist);
		$content_extralist__id = $dane["content_extralist__id"];
	}
	elseif ( isset($action["extralist_edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_extralist_edit($danecontent_extralist);
		$content_extralist__id = $dane["content_extralist__id"];
	}
	elseif ( isset($action["extralist_delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_extralist_delete( $content_extralist__id );
	}

	if ( isset($action["add"]) || isset($action["edit"]) || isset($action["delete"]) ) {
		if (content_extra_modify_db( $dane, $prev_dane, key($action) )) {
			# sm_sql_transaction_commit();
		}
		else {
			# sm_sql_transaction_rollback();
		}
	}
}
else {
	$content_extra__id = $content_extra__id ? $content_extra__id : "0";
}

if( $content_extra__id ) {
	$dane = content_extra_dane( $content_extra__id );
}
if( $content_extralist__id ) {
	$danecontent_extralist = content_extralist_dane( $content_extralist__id );
}

include "_page_header5.php";

$dane = htmlentitiesall($dane);

if (!$content_extra__id && $content_extra__id!="0") {
        $params = array(
		"button_back" => 1,
		"button_addnew" => 1,
		"dbname" => "content_extra",
		"function_fetch" => "content_extra_fetch_all()",
                "mainkey" => "content_extra__id",
                "columns" => array(
                        array( "title"=>__("core", "Obiekt"), "width"=>"200", "value"=>"%%{content_extra__object}%%", "order"=>1 ),
                        array( "title"=>__("core", "Nazwa w bazie danych"), "width"=>"200", "value"=>"%%{content_extra__dbname}%%", "order"=>1 ),
                        array( "title"=>__("core", "Nazwa pola"), "width"=>"100%", "value"=>"%%{content_extra__name}%%", "order"=>1 ),
                        array( "title"=>__("core", "Typ pola DB"), "width"=>"100", "value"=>"%%{content_extra__dbtype}%%", "order"=>1 ),
                        array( "title"=>__("core", "Typ pola"), "width"=>"100", "value"=>"%%{content_extra__input}%%", "order"=>1 ),
                ),
		"row_per_page_default" => 100,
        );
        include "_datatable_list5.php";
}
else {
?>
					<div class="btn-toolbar">
						<div class="btn-group">
							<a class="btn btn-small btn-info" href="?"><i class="icon-list icon-white"></i>&nbsp;<?=__("core", "BUTTON__BACK_TO_LIST")?></a>
							<a class="btn btn-small btn-info" href="?content_extra__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD_NEW")?></a>
						</div>
					</div>

					<form action="" method=post enctype="multipart/form-data" id="sm-form">

						<div class="row-float">
							<div class="span8">

								<fieldset class="no-legend">
<?
	$inputfield_options=array();
	$inputfield_options[]="---";
	if(is_array($CONTENT_EXTRA_OBJECT_ARRAY)) {
		foreach($CONTENT_EXTRA_OBJECT_ARRAY AS $k=>$v) {
			$inputfield_options[ $v["name"] ] = $v["name"]." - ".$v["title"];
		}
	}
?>
									<?=sm_inputfield( "select", "Wybierz obiekt do modyfikacji", "", "dane_content_extra__object", "dane[content_extra__object]", $dane["content_extra__object"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1, $inputfield_options);?>
									<?=sm_inputfield( "text", "Nazwa pola", "używana wewnętrznie", "dane_content_extra__name", "dane[content_extra__name]", $dane["content_extra__name"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
									<div class="row-float">
										<div class="span8">
											<?=sm_inputfield( "text", "Nazwa pola w bazie danych", "nazwa systemowa bazy danych - wyłącznie litery, cyfry i podkreślenie", "dane_content_extra__dbname", "dane[content_extra__dbname]", $dane["content_extra__dbname"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
										</div>
										<div class="span4">
<?
	$inputfield_options=array();
	$inputfield_options[]="---";
	if(is_array($CONTENT_EXTRA_DBTYPE_ARRAY)) {
		foreach($CONTENT_EXTRA_DBTYPE_ARRAY AS $k=>$v) {
			$inputfield_options[ $v["name"] ] = $v["name"]." - ".$v["title"];
		}
	}
?>
											<?=sm_inputfield( "select", "Typ danych", "Sposób przechowywania danych", "dane_content_extra__dbtype", "dane[content_extra__dbtype]", $dane["content_extra__dbtype"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1, $inputfield_options);?>
										</div>
									</div>
									<div class="row-float">
										<div class="span8">
											<?=sm_inputfield( "text", "Opis pola w formularzu", "", "dane_content_extra__info", "dane[content_extra__info]", $dane["content_extra__info"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
										</div>
										<div class="span4">
<?
	$inputfield_options=array();
	$inputfield_options[]="---";
	if(is_array($CONTENT_EXTRA_INPUT_ARRAY)) {
		foreach($CONTENT_EXTRA_INPUT_ARRAY AS $k=>$v) {
			$inputfield_options[ $v["name"] ] = $v["name"]." - ".$v["title"];
		}
	}
?>
											<?=sm_inputfield( "select", "Typ pola w formularzu", "Jak wygląda pole w formularzu", "dane_content_extra__input", "dane[content_extra__input]", $dane["content_extra__input"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1, $inputfield_options);?>
										</div>
									</div>
									<div class="row-float">
										<div class="span6">
											<?=sm_inputfield( "checkbox", "Pole wymagane", "Musi zostać wypełnione", "dane_content_extra__required", "dane[content_extra__required]", $dane["content_extra__required"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
										</div>
										<div class="span6">
											<?=sm_inputfield( "checkbox", "Pokazuj na liście", "Pojawi się na liście rekordów", "dane_content_extra__listview", "dane[content_extra__listview]", $dane["content_extra__listview"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
										</div>
									</div>

<?
	if ($content_extra__id && $dane["content_extra__input"]=="relation") {
?>
									<?=sm_inputfield( "text", "Nazwa tabeli", "", "dane_content_extra__relationtable", "dane[content_extra__relationtable]", $dane["content_extra__relationtable"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
									<?=sm_inputfield( "text", "Pole wyświetlane", "Nazwa kolumny z bazy wyświetlanej jako pozycja na liście", "dane_content_extra__relationname", "dane[content_extra__relationname]", $dane["content_extra__relationname"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
									<?=sm_inputfield( "text", "Funkcja do pobierania danych", "", "dane_content_extra__relationfunction", "dane[content_extra__relationfunction]", $dane["content_extra__relationfunction"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
<?
	}
?>

								</fieldset>
<?
	if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) {
?>
								<div class="btn-toolbar">
									<input type=hidden name="dane[content_extra__id]" value="<?=$dane["content_extra__id"]?>">
									<input type=hidden name="content_extra__id" value="<?=$content_extra__id?>">
<?
		if ($dane["content_extra__id"]) {
?>
									<a class="btn btn-normal btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
									<a class="btn btn-normal btn-danger" id="action-delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<?
		} else {
?>
									<a class="btn btn-normal btn-info" id="action-add"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD")?></a>
<?
		}
?>
								</div>
<?
	}
?>

							</div>
							<div class="span4">

<?
	if ($content_extra__id && $dane["content_extra__input"]=="select") {
?>
								<div class="fieldset-title">
									<div>Elementy listy</div>
								</div>
								<fieldset class="sm-fileselected no-legend">
									<table class=table>
										<thead>
											<tr>
												<th width="100%"><?=__("core", "Nazwa")?></th>
												<th><?=__("core", "Wartość")?></th>
												<th></th>
												<th></th>
											</tr>
										</thead>
										<tbody>
<?
		if($result = content_extralist_fetch_by_content_extra( $content_extra__id )) {
			$even="";
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$even=$even?0:1;
?>
											<tr class="<?=$even?"even":"odd"?>">
												<td><?=$row["content_extralist__name"]?></td>
												<td><?=$row["content_extralist__value"]?></td>
												<td><a href="?content_extra__id=<?=$content_extra__id?>&content_extralist__id=<?=$row["content_extralist__id"]?>"><i class="icon-edit"></i></a></td>
												<td><a href="?content_extra__id=<?=$content_extra__id?>&content_extralist__id=<?=$row["content_extralist__id"]?>&action[extralist_delete]=1" OnClick="return confirm()"><i class="icon-remove"></i></a></td>
											</tr>
<?
				$maxval = $maxval>$row["content_extralist__value"]?$maxval:$row["content_extralist__value"];
			}
		}
?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan=4><a class="btn btn-mini btn-info" href="?content_extra__id=<?=$content_extra__id?>&content_extralist__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "nowy element")?></a></td>
												</tr>
											</tfoot>
										</table>
<?
		if ($danecontent_extralist || isset($content_extralist__id)) {
?>
<? $danecontent_extralist["content_extralist__value"] = isset($danecontent_extralist["content_extralist__value"]) ? $danecontent_extralist["content_extralist__value"] : $maxval+1;?>
										<?=sm_inputfield( "text", "Nazwa wartości", "", "danecontent_extralist_content_extralist__name", "danecontent_extralist[content_extralist__name]", $danecontent_extralist["content_extralist__name"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
										<?=sm_inputfield( "text", "Wartość", "musi być unikalna w ramach całej listy", "danecontent_extralist_content_extralist__value", "danecontent_extralist[content_extralist__value]", $danecontent_extralist["content_extralist__value"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
<?
			if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) {
?>
									<div class="btn-toolbar">
										<input type=hidden name="danecontent_extralist[content_extralist__id]" value="<?=$danecontent_extralist["content_extralist__id"]?>">
										<input type=hidden name="content_extralist__id" value="<?=$content_extralist__id?>">
										<input type=hidden name="danecontent_extralist[content_extra__id]" value="<?=$content_extra__id?>">
										<input type=hidden name="content_extra__id" value="<?=$content_extra__id?>">
<?				if ($danecontent_extralist["content_extralist__id"]) {?>
										<a class="btn btn-mini btn-info" id="action-extralist_edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
										<a class="btn btn-mini btn-danger" id="action-extralist_delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<?				} else {?>
										<a class="btn btn-mini btn-info" id="action-extralist_add"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD")?></a>
<?				} ?>
									</div>
<?
			}
?>
<?
		}
?>
								</fieldset>
<?
	}
?>
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
$('#action-extralist_edit').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[extralist_edit]" value=1>');
	$('#sm-form').submit();
});
$('#action-extralist_delete').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[extralist_delete]" value=1>');
	$('#sm-form').submit();
});
$('#action-extralist_add').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[extralist_add]" value=1>');
	$('#sm-form').submit();
});
</script>
					</form>

<?
}
?>

<? include "_page_footer5.php" ?>
