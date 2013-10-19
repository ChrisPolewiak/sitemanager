<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$content_section__id = $_REQUEST["content_section__id"];
$content_page__id = $_REQUEST["content_page__id"];
$danecontent_section2page = $_REQUEST["danecontent_section2page"];
$content_section2content_page__id = $_REQUEST["content_section2content_page__id"];

if ( isset($action["add"]) || isset($action["edit"]) ) {
	$dane=trimall($dane);
	if(!$dane["content_section__sysname"]){
		$ERROR[] = __("core", "Podaj nazwę wewnętrzną");
	}
	if(! preg_match("/^[\d\w\-\_]+$/", $dane["content_section__sysname"])) {
		$ERROR[] = __("core", "Nieprawidłowe znaki w nazwie wewnętrznej");
	}
	if(!$dane["content_section__name"]){
		$ERROR[] = __("core", "Podaj nazwę sekcji");
	}
}
if ( isset($action["section2page_add"]) || isset($action["section2page_edit"]) ) {
	$danecontent_section2page=trimall($danecontent_section2page);
	if(!$danecontent_section2page["content_page__id"]){
		$ERROR[] = __("core", "Wybierz podstronę serwisu");
	}
}

if(!is_array($ERROR)) {
	if ( isset($action["add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_section__id = content_section_add($dane);
	}
	elseif ( isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_section__id = content_section_edit($dane);
	}
	elseif ( isset($action["delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_section_delete($content_section__id);
		unset($content_section__id);
	}
	elseif ( isset($action["section2page_add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_section2content_page_edit($danecontent_section2page);
		$content_page__id = $danecontent_section2page["content_page__id"];
	}
	elseif ( isset($action["section2page_edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_section2content_page_edit($danecontent_section2page);
		$content_page__id = $danecontent_section2page["content_page__id"];
	}
	elseif ( isset($action["section2page_delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_section2content_page_delete( $content_section2content_page__id );
	}
}
else {
	$content_section__id = $content_section__id ? $content_section__id : "0";
}

if( $content_section__id ) {
	$dane = content_section_dane( $content_section__id );
}
if( $content_page__id ) {
	$danecontent_page = content_page_get( $content_page__id );
}
if( $content_section2content_page__id ) {
	$danecontent_section2page = content_section2content_page_get( $content_section2content_page__id );
}

include "_page_header5.php";

$dane = htmlentitiesall($dane);

if (!$content_section__id && $content_section__id!="0") {
        $params = array(
		"button_back" => 1,
		"button_addnew" => 1,
		"dbname" => "content_section",
		"function_fetch" => "content_section_fetch_all()",
		"mainkey" => "content_section__id",
                "columns" => array(
                        array( "title"=>__("core", "Nazwa sekcji"), "width"=>"100%", "value"=>"%%{content_section__name}%%", "order"=>1, ),
                        array( "title"=>__("core", "Nazwa wewnętrzna"), "width"=>"200", "value"=>"%%{content_section__sysname}%%", "order"=>1, ),
                        array( "title"=>__("core", "Tytuł"), "width"=>"300", "value"=>"%%{content_section__title}%%", "order"=>1, ),
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
							<a class="btn btn-small btn-info" href="?content_section__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD_NEW")?></a>
						</div>
					</div>

					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">

						<div class="row-fluid">
							<div class="span6">
								<fieldset class="no-legend">
									<?=sm_inputfield(array(
										"type"=>"text",
										"title"=>"Nazwa wewnętrzna",
										"help"=>"wyłącznie litery, cyfry, myślnik i podkreślenie",
										"id"=>"dane_content_section__sysname",
										"name"=>"dane[content_section__sysname]",
										"value"=> $dane["content_section__sysname"],
										"size"=>"block-level",
										"disabled"=>0,
										"validation"=>0,
										"prepend"=>0,
										"append"=>0,
										"rows"=>1,
										"options"=>"",
										"xss_secured"=>true
									));?>
									<?=sm_inputfield(array(
										"type"=>"text",
										"title"=>"Nazwa sekcji",
										"help"=>"",
										"id"=>"dane_content_section__name",
										"name"=>"dane[content_section__name]",
										"value"=> $dane["content_section__name"],
										"size"=>"block-level",
										"disabled"=>0,
										"validation"=>0,
										"prepend"=>0,
										"append"=>0,
										"rows"=>1,
										"options"=>"",
										"xss_secured"=>true
									));?>
									<?=sm_inputfield(array(
										"type"=>"text",
										"title"=>"Tytuł",
										"help"=>"wyświetlany na stronie",
										"id"=>"dane_content_section__title",
										"name"=>"dane[content_section__title]",
										"value"=> $dane["content_section__title"],
										"size"=>"block-level",
										"disabled"=>0,
										"validation"=>0,
										"prepend"=>0,
										"append"=>0,
										"rows"=>1,
										"options"=>"",
										"xss_secured"=>true
									));?>
								</fieldset>

<?	if ($content_section__id) { ?>
								<div class="fieldset-title">
									<div>Relacje ze stronami</div>
								</div>
								<fieldset class="no-legend">
									<table class="table">
										<thead>
											<tr>
												<th style="width:50%"><?=__("core", "Strona")?></th>
												<th><?=__("core", "Kolumna")?><br><?=__("core", "Kolejność")?></th>
												<th style="width:50%"><?=__("core", "Wymagany poziom dostępu")?></th>
												<th></th>
												<th></th>
											</tr>
										</thead>
										<tbody>
<?
		if($result = content_section2content_page_fetch_by_content_section( $content_section__id )) {
			$even="";
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$even=$even==1?0:1;
?>
											<tr class=<?=($even?"even":"odd")?>>
												<td><?=$row["content_page__name"]?></td>
												<td align=center><?=$row["content_section2content_page__column"]?>/<?=$row["content_section2content_page__order"]?></td>
												<td><?=$row["content_section2content_page__requiredaccess"]?></td>
<?				if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
												<td align=center>
                											<a href="?content_section__id=<?=$content_section__id?>&content_page__id=<?=$row["content_page__id"]?>&content_section2content_page__id=<?=$row["content_section2content_page__id"]?>"><i class="icon-edit"></i></a>
												</td>
												<td align=center>
                											<a href="?content_section__id=<?=$content_section__id?>&content_page__id=<?=$row["content_page__id"]?>&content_section2content_page__id=<?=$row["content_section2content_page__id"]?>&action[section2page_delete]=1" OnClick="return confDelete()"><i class="icon-remove"></i></a></a>
												</td>
<?				} else { ?>
												<td></td><td></td>		
<?				} ?>
                									</tr>
<?
			}
		}
?>
                								</tbody>
                								<tfoot>
                									<tr class=TableFooter>
                										<td colspan=5><a href="?content_section__id=<?=$content_section__id?>&content_page__id=0"><?=__("core", "dołącz do kolejnej strony")?></a></td>
                									</tr>
                								</tfoot>
                							</table>
								</fieldset>
<?	} ?>


<?	if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
								<div class="btn-toolbar">
									<input type=hidden name="dane[content_section__id]" value="<?=$dane["content_section__id"]?>">
									<input type=hidden name="content_section__id" value="<?=$content_section__id?>">
<?		if ($dane["content_section__id"]) {?>
									<a class="btn btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
									<a class="btn btn-danger" id="action-delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<?		} else {?>
									<a class="btn btn-info" id="action-add"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD")?></a>
<?		}?>
								</div>
<? 	} ?>

							</div>
							<div class="span6">

<?
	if (isset($content_page__id)) {
?>
								<div class="fieldset-title">
									<div>Edycja powiązania sekcji ze stroną</div>
								</div>
								<fieldset class="no-legend">
<?
		$inputfield_options=array();
		$inputfield_options[0]="wybierz";
		foreach($CONTENT_PAGE_LONGNAME AS $k=>$v) {
			$prefix = substr($v,0,2);
			$v = preg_replace( "/(.+?)@@@/is", " / .. ", $v);
			$num = $k; if($k<10){ $num = "0".$num; } if($k<100){ $num = "0".$num; }
			$prefix = preg_replace( "/(.+?)@@@([^@]+)@*.*/", "\\1", $CONTENT_PAGE_LONGNAME[$k]);
			$v = $prefix." : ".$v;
			$inputfield_options[ $k ] = $v;
		}
?>
									<?=sm_inputfield(array(
										"type"	=> "select",
										"title"	=> "Strona",
										"help"	=> "do której strony ma zostać sekcja przypisana",
										"id"	=> "danecontent_section2page_content_page__id",
										"name"	=> "danecontent_section2page[content_page__id]",
										"value"	=> $danecontent_section2page["content_page__id"],
										"size"	=> "block-level",
										"disabled" => 0,
										"validation" => 0,
										"prepend" => 0,
										"append" => 0,
										"rows" => 1,
										"options" => $inputfield_options,
										"xss_secured" => true
									));?>
									<div class="row-float">
										<div class="span3">
<?
		$inputfield_options=array();
		$inputfield_options[0]="n/d";
		for($i=1;$i<=10;$i++) {
			$inputfield_options[$i] = $i;
		}
?>
											<?=sm_inputfield(array(
												"type"	=> "select",
												"title"	=> "Kolumna",
												"help"	=> "w której ma się sekcja pojawić",
												"id"	=> "danecontent_section2page_content_section2content_page__column",
												"name"	=> "danecontent_section2page[content_section2content_page__column]",
												"value"	=> $danecontent_section2page["content_section2content_page__column"],
												"size"	=> "block-level",
												"disabled" => 0,
												"validation" => 0,
												"prepend" => 0,
												"append" => 0,
												"rows" => 1,
												"options" => $inputfield_options,
												"xss_secured" => true
											));?>
										</div>
										<div class="span3">
<?
		$inputfield_options=array();
		$inputfield_options[0]="n/d";
		for($i=1;$i<=25;$i++) {
			$inputfield_options[$i] = $i;
		}
?>
											<?=sm_inputfield(array(
												"type"	=> "select",
												"title"	=> "Kolejność",
												"help"	=> "w której ma się sekcja pojawić",
												"id"	=> "danecontent_section2page_content_section2content_page__order",
												"name"	=> "danecontent_section2page[content_section2content_page__order]",
												"value"	=> $danecontent_section2page["content_section2content_page__order"],
												"size"	=> "block-level",
												"disabled" => 0,
												"validation" => 0,
												"prepend" => 0,
												"append" => 0,
												"rows" => 1,
												"options" => $inputfield_options,
												"xss_secured" => true
											));?>
										</div>
										<div class="span6">
<?
		$inputfield_options=array();
		$inputfield_options[0]="bez ograniczeń";
		if ($result=content_access_fetch_all()) {
			while ($row=$result->fetch(PDO::FETCH_ASSOC)) {
				if($row["content_access__type"] == "CONTENT_PAGE") {
					$inputfield_options[ $row["content_access__tag"] ] = $row["content_access__name"];
				}
			}
		}
?>
											<?=sm_inputfield(array(
												"type"	=> "select",
												"title"	=> "Wymagany poziom dostępu",
												"help"	=> "ogranicza dostęp",
												"id"	=> "danecontent_section2page_content_section2content_page__requiredaccess",
												"name"	=> "danecontent_section2page[content_section2content_page__requiredaccess]",
												"value"	=> $danecontent_section2page["content_section2content_page__requiredaccess"],
												"size"	=> "block-level",
												"disabled" => 0,
												"validation" => 0,
												"prepend" => 0,
												"append" => 0,
												"rows" => 1,
												"options" => $inputfield_options,
												"xss_secured" => true
											));?>
										</div>
									</div>
									<?=sm_inputfield(array(
										"type"	=> "textarea",
										"title"	=> "Dodatkowe parametry",
										"help"	=> "opcjonalne zmienne przekazane do skryptu",
										"id"	=> "danecontent_section2page_content_section2content_page__data",
										"name"	=> "danecontent_section2page[content_section2content_page__data]",
										"value"	=> $danecontent_section2page["content_section2content_page__data"],
										"size"	=> "block-level",
										"disabled" => 0,
										"validation" => 0,
										"prepend" => 0,
										"append" => 0,
										"rows" => 3,
										"options" => "",
										"xss_secured" => true
									));?>
<?		if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
									<div class="btn-toolbar">
										<input type=hidden name="content_page__id" value="<?=$content_page__id?>">
										<input type=hidden name="danecontent_section2page[content_section__id]" value="<?=$content_section__id?>">
<?			if ($danecontent_section2page["content_page__id"]) {?>
										<a class="btn btn-mini btn-info" id="action-section2page_edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
										<a class="btn btn-mini btn-danger" id="action-section2page_delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<?			} else {?>
										<a class="btn btn-mini btn-info" id="action-section2page_add"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD")?></a>
<?			}?>
									</div>
<?		} ?>
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
$('#action-section2page_edit').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[section2page_edit]" value=1>');
	$('#sm-form').submit();
});
$('#action-section2page_delete').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[section2page_delete]" value=1>');
	$('#sm-form').submit();
});
$('#action-section2page_add').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[section2page_add]" value=1>');
	$('#sm-form').submit();
});
</script>
					</form>
<?
}
?>

<? include "_page_footer5.php"; ?>
