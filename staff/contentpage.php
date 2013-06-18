<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$content_page__id = $_REQUEST["content_page__id"];

if ( $_REQUEST["ajax"] ) {
	switch($action) {
		case "reorder":
			$content_page__order=array();
			foreach($_REQUEST["id"] AS $content_page__id=>$content_page__idparent) {
				$content_page__order[$content_page__idparent]++;
				content_page_reorder( $_REQUEST["contentpage"][$content_page__id], $_REQUEST["contentpage"][$content_page__idparent], $content_page__order[$content_page__idparent] );
			}

			break;
	}
	set_time_limit(3600);
	filearray_generator( "content_page" );
	xmlarray_generator( "content_page" );
	echo "ok";
	exit;
}

// validation
if( isset($action["add"]) || isset($action["edit"]) ){

	$dane=trimall($dane);
	if(!$dane["content_page__title"]){
		$ERROR[] = __("core", "Podaj tytuł podstrony");
	}
	if(!$dane["content_page__name"]){
		$ERROR[] = __("core", "Podaj nazwę wewnętrzną");
	}
	elseif(! preg_match("/^[\d\w\_\-]+$/", $dane["content_page__name"])) {
		$ERROR[] = __("core", "Nieprawidłowe znaki w nazwie wewnętrznej").": ".("wyłącznie litery, cyfry, myślnik i podkreślenie");
	}
	elseif($_tmpid = content_page_get_by_name($dane["content_page__name"])) {
		if($dane["content_page__id"] && $dane["content_page__id"]!=$_tmpid){
			$ERROR[] = __("core", "W systemie jest już strona o takiej nazwie wewnętrznej");
		}
		elseif(!$dane["content_page__id"] && $_tmpid){
			$ERROR[] = __("core", "W systemie jest już strona o takiej nazwie wewnętrznej");
		}
	}
	if(!$dane["content_page__url"]){
		$ERROR[] = __("core", "Podaj adres");
	}
	elseif(! preg_match("/^[\d\w\_\-\/\.]+$/", $dane["content_page__url"])) {
		$ERROR[] = __("core", "Nieprawidłowe znaki w adresie url").": ".("wyłącznie litery, cyfry, myślnik i podkreślenie");
	}
	elseif($_tmpid = content_page_get_by_url($dane["content_page__url"])) {
		if($dane["content_page__id"] && $dane["content_page__id"]!=$_tmpid){
			$ERROR[] = __("core", "W systemie jest już strona o takim samym adresie url");
		}
		elseif(!$dane["content_page__id"] && $_tmpid){
			$ERROR[] = __("core", "W systemie jest już strona o takim samym adresie url");
		}
	}
	if(!$dane["content_template__id"]){
		$ERROR[] = __("core", "Wybierz szablon");
	}
}

// actions
if(!$ERROR) {
	if ( isset($action["add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);

		$dane["content_page__order"] = content_page_get_last_order( $dane["content_page__idparent"] ) + 1;
		if( $content_page__id = content_page_add($dane)) {
			$MESSAGE = "Dodano rekord do bazy";
			$dane["content_page__id"] = $content_page__id;
			content_page_change($dane);
		}
		$action["generator"]=1;
	}
	elseif ( isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);

		if( $content_page__id = content_page_change($dane)) {
			$MESSAGE = "Zmodyfikowano rekord w bazie";
		}
		$action["generator"]=1;
	}
	elseif ( isset($action["delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);

		$content_page__id = content_page_delete($dane["content_page__id"]);
		unset($dane);
		$action["generator"]=1;
	}
	elseif ( isset($action["refresh"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_page__id = content_page_refresh();
		$action["generator"]=1;
	}

	if( isset($action["generator"])) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);

		set_time_limit(60);
		filearray_generator( "content_page" );
		xmlarray_generator( "content_page" );
		header("Location: ".$ENGINE."/".$page."?content_page__id=$content_page__id&amp;MESSAGE=$MESSAGE");
		exit;
	}
}
else {
	$content_page__id = $content_page__id ? $content_page__id : $content_page["content_page__id"];
}

include "_page_header5.php";

?>
					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">
<?

if (!$content_page__id && $content_page__id!="0") {

?>

						<div class="btn-toolbar">
							<div class="btn-group">
								<a class="btn btn-small btn-info" href="?content_page__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "dodaj nową podstronę")?></a>
							</div>
						</div>

						<fieldset>
							<legend><?=__("core", "Widok drzewa")?></legend>

<?
if( $result = content_template_fetch_all()) {
	while($row=$result->fetch(PDO::FETCH_ASSOC)){
		$content_page_template_name[ $row["content_template__id"] ] = $row["content_template__name"];
	}
}


function sm_contentpage_treeview() {
	global $XML_CONTENT_PAGE;
?>
<div class="sortable-header">
	<div class="col-sortable">&nbsp;</div>
	<div class="col-items">
		<div class="col-name"><span>Nazwa strony</span></div>
		<div class="col-url"><span>Adres strony</span></div>
		<div class="col-tpl"><span>Szablon</span></div>
		<div class="col-menu"><span>menu</span></div>
		<div class="col-enabled"><span>aktyw.</span></div>
		<div class="col-actions"><span>edycja</span></div>
	</div>
</div>
<div class="clear:all"></div>
<ol class="sortable">
<?
	$contentpage_id_counter = 0;
	foreach($XML_CONTENT_PAGE AS $item) {
		sm_content_page_treeview_recurency( $item );
	}
?>
</ol>
<?
}

function sm_content_page_treeview_recurency( $item ) {
	global $content_page_template_name, $contentpage_id_counter;
/*
echo "<pre>";
print_r($item);
echo "</pre>";
*/
	$contentpage_id_counter++;
?>
	<input type="hidden" id="elementid_<?=$contentpage_id_counter?>" name="contentpage[<?=$contentpage_id_counter?>]" value="<?=$item["id"]?>">
	<li id="id_<?=$contentpage_id_counter?>">
		<div class="item">
			<input type="hidden" id="contentpage[]" value="<?=(string) $item["id"]?>">
			<div class="disclose"><span class="btn btn-mini btn-info"></span></div>
			<div style="display:inline-block">
			</div>
			<div style="display:inline-block;float:right">
				<div class="col-name" title="<?=preg_replace("/(\")/", "&quot;", (string) $item["title"])?>"><?=(string) $item["title"]?> (<?=$contentpage_id_counter?>)</div>
				<div class="col-url" title="<?=preg_replace("/(\")/", "&quot;", (string) $item["url"])?>">/<?=(string) $item["url"]?></div>
				<div class="col-tpl" title="<?=preg_replace("/(\")/", "&quot;", $content_page_template_name[ (string) $item["content_template__id"] ])?>">/<?=$content_page_template_name[ (string) $item["content_template__id"] ]?></div>
				<div class="col-menu"><i class="<?=(int) $item["menu_visible"] ? "icon-ok-sign icon-green" : "icon-remove-sign icon-red" ?>"></i></div>
				<div class="col-enabled"><i class="<?=(int) $item["enabled"] ? "icon-ok-sign icon-green" : "icon-remove-sign icon-red" ?>"></i></div>
				<div class="col-actions"><a href="?content_page__id=<?=(string) $item["id"]?>"><i class="icon-edit icon-black"></i></a></div>
			</div>
		</div>
<?
	if($item->item){
?>
			<ol>
<?
		foreach($item->item AS $item_parent) {
			sm_content_page_treeview_recurency( $item_parent );
		}
?>
			</ol>
<?
	}
?>
	</li>
<?
}
?>

<? sm_contentpage_treeview(); ?>

							<div class="btn-toolbar">
								<a class="btn btn-normal btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
								<a class="btn btn-normal btn-info" id="action-refresh"><i class="icon-refresh icon-white"></i>&nbsp;<?=__("core", "BUTTON__REFRESH")?></a>
							</div>
						</fieldset>
					</form>	


<script>
$(document).ready(function(){
	$('.sortable').nestedSortable({
		forcePlaceholderSize: true,
		handle: 'div',
		helper:	'clone',
		items: 'li',
		opacity: .6,
		placeholder: 'placeholder',
		revert: 250,
		tabSize: 25,
		tolerance: 'pointer',
		toleranceElement: '> div',
		maxLevels: 0,
		isTree: true,
		expandOnHover: 700,
		startCollapsed: false
	});
	
	$('.disclose').on('click', function() {
		$(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
	})
	
	$("#action-edit").click(function(e){
		e.preventDefault();
		serialized = $('ol.sortable').nestedSortable('serialize');
		data = $('#sm-form').serialize();
		$.ajax({
			url: "?ajax=1&action=reorder",
			type: "POST",
			data: serialized+'&'+data,
			success: function(html){
				if (html != 'ok')
					alert(html);
			}
		});
	});

	$('#action-refresh').click(function() {
		$('#sm-form').append('<input type="hidden" name="action[refresh]" value=1>');
		$('#sm-form').submit();
	});

});
</script>
<?
}
else {
	$dane = content_page_get($content_page__id);
?>
						<div class="btn-toolbar">
							<div class="btn-group">
								<a class="btn btn-small btn-info" href="?"><i class="icon-list icon-white"></i>&nbsp;<?=__("core", "BUTTON__BACK_TO_LIST")?></a>
								<a class="btn btn-small btn-info" href="?content_page__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "dodaj nową podstronę")?></a>
							</div>
						</div>

<script>
$('#tabs').ready(function() {
	$('#tabs a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
});
</script>

						<ul class="nav nav-tabs" id="tabs">
							<li class="active"><a href="#tabs-1"><?=__("core", "Dane podstrony")?></a></li>
							<li><a href="#tabs-2"><?=__("core", "Uprawnienia")?></a></li>
							<li><a href="#tabs-3"><?=__("core", "Parametry")?></a></li>
							<li><a href="#tabs-4"><?=__("core", "Sortowanie")?></a></li>
						</ul>

						<div class="tab-content">
							<div id="tabs-1" class="tab-pane active">

								<fieldset class="no-legend">
									<?=sm_inputfield(array(
										"type"	=> "text",
										"title"	=> "Tytuł",
										"help"	=> "wyświetlany na stronie",
										"id"	=> "dane_content_page__title",
										"name"	=> "dane[content_page__title]",
										"value"	=> $dane["content_page__title"],
										"size"	=> "block-level",
										"disabled" => 0,
										"validation" => 0,
										"prepend" => 0,
										"append" => 0,
										"rows" => 1,
										"options" => "",
										"xss_secured" => true
									));?>
									<div class="row-fluid">
										<div class="span6">
											<?=sm_inputfield(array(
												"type"	=> "text",
												"title"	=> "Nazwa wewnętrzna",
												"help"	=> "",
												"id"	=> "dane_content_page__name",
												"name"	=> "dane[content_page__name]",
												"value"	=> $dane["content_page__name"],
												"size"	=> "block-level",
												"disabled" => 0,
												"validation" => 0,
												"prepend" => 0,
												"append" => 0,
												"rows" => 1,
												"options" => "",
												"xss_secured" => true
											));?>
										</div>
										<div class="span6">
											<?=sm_inputfield(array(
												"type"	=> "text",
												"title"	=> "Adres strony",
												"help"	=> "pod jakim adresem będzie strona widziana",
												"id"	=> "dane_content_page__url",
												"name"	=> "dane[content_page__url]",
												"value"	=> $dane["content_page__url"],
												"size"	=> "large",
												"disabled" => 0,
												"validation" => 0,
												"prepend" => "/",
												"append" => 0,
												"rows" => 1,
												"options" => "",
												"xss_secured" => true
											));?>
										</div>
									</div>
									<div class="row-fluid">
										<div class="span2">
<?
	$inputfield_options=array();
	$inputfield_options[""]="dowolny";
	foreach($SM_TRANSLATION_LANGUAGES AS $k=>$v) {
		$inputfield_options[ $k ]=$v;
	}
?>
											<?=sm_inputfield(array(
												"type"	=> "select",
												"title"	=> "Język",
												"help"	=> "",
												"id"	=> "dane_content_page__lang",
												"name"	=> "dane[content_page__lang]",
												"value"	=> $dane["content_page__lang"],
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
										<div class="span10">
<?
	$inputfield_options=array();
	if ($result=content_template_fetch_all()) {
		while ($row=$result->fetch(PDO::FETCH_ASSOC)) {
			$inputfield_options[ $row["content_template__id"] ] = $row["content_template__name"];
		}
	}
?>
											<?=sm_inputfield(array(
												"type"	=> "select",
												"title"	=> "Szablon",
												"help"	=> "wybrany szablon podstrony - <a href=\"content_template.php?content_template__id=0\">".__("core", "dodaj szablon")."</a>",
												"id"	=> "dane_content_template__id",
												"name"	=> "dane[content_template__id]",
												"value"	=> $dane["content_template__id"],
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


									<div class="row-fluid">
										<div class="span6">
											<?=sm_inputfield(array(
												"type"	=> "textarea",
												"title"	=> "Opis strony",
												"help"	=> "pojawi się w nagłówkach strony",
												"id"	=> "dane_content_page__description",
												"name"	=> "dane[content_page__description]",
												"value"	=> $dane["content_page__description"],
												"size"	=> "block-level",
												"disabled" => 0,
												"validation" => 0,
												"prepend" => 0,
												"append" => 0,
												"rows" => 1,
												"options" => "",
												"xss_secured" => true
											));?>
										</div>
										<div class="span6">
											<?=sm_inputfield(array(
												"type"	=> "textarea",
												"title"	=> "Słowa kluczowe",
												"help"	=> "pojawi się w nagłówkach strony",
												"id"	=> "dane_content_page__keywords",
												"name"	=> "dane[content_page__keywords]",
												"value"	=> $dane["content_page__keywords"],
												"size"	=> "block-level",
												"disabled" => 0,
												"validation" => 0,
												"prepend" => 0,
												"append" => 0,
												"rows" => 1,
												"options" => "",
												"xss_secured" => true
											));?>
										</div>
									</div>


<?
	$inputfield_options=array();
	$inputfield_options[0]="START";
	if(is_array($CONTENT_PAGE_LONGNAME)){
		foreach($CONTENT_PAGE_LONGNAME AS $k=>$v) {
			$v = preg_replace( "/(@@@)/is", " / ", $v);

			if($content_page__id != $k) {

				$_error=false;
				$path = unserialize($CONTENT_PAGE[ $k ]["path"]);
				foreach($path AS $pk=>$pv) {
					if( $content_page__id == $pv["content_page__id"]) {
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
									<?=sm_inputfield(array(
										"type"	=> "select",
										"title"	=> "Kategoria nadrzędna",
										"help"	=> "",
										"id"	=> "dane_content_page__idparent",
										"name"	=> "dane[content_page__idparent]",
										"value"	=> $dane["content_page__idparent"],
										"size"	=> "block-level",
										"disabled" => 0,
										"validation" => 0,
										"prepend" => 0,
										"append" => 0,
										"rows" => 1,
										"options" => $inputfield_options,
										"xss_secured" => true
									));?>
								</fieldset>

							</div>
							<div id="tabs-2" class="tab-pane">
								<fieldset class="no-legend">
									<div class="row-fluid">
										<div class="span6">
<?
	$inputfield_options=array();
	$inputfield_options[]="brak";
	if ($result=content_access_fetch_all()) {
		while ($row=$result->fetch(PDO::FETCH_ASSOC)) {
			$inputfield_options[ $row["content_access__id"] ] = $row["content_access__name"];
		}
	}
?>
											<?=sm_inputfield(array(
												"type"	=> "select",
												"title"	=> "Wymagany poziom dostępu",
												"help"	=> "jeśli nie wybierzesz - dostęp będzie dla wszystkich",
												"id"	=> "dane_content_page__requiredaccess",
												"name"	=> "dane[content_page__requiredaccess]",
												"value"	=> $dane["content_page__requiredaccess"],
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
											<?=sm_inputfield(array(
												"type"	=> "textarea",
												"title"	=> "Dostęp z adresów IP",
												"help"	=> "Lista adresów ip z których możliwy jest dostęp",
												"id"	=> "dane_content_page__hostallow",
												"name"	=> "dane[content_page__hostallow]",
												"value"	=> $dane["content_page__hostallow"],
												"size"	=> "block-level",
												"disabled" => 0,
												"validation" => 0,
												"prepend" => 0,
												"append" => 0,
												"rows" => 3,
												"options" => "",
												"xss_secured" => true
											));?>
										</div>
									</div>
								</fieldset>

							</div>
							<div id="tabs-3" class="tab-pane">
								<fieldset class="no-legend">

									<div class="row-fluid">
										<div class="span4">
											<?=sm_inputfield(array(
												"type"	=> "checkbox",
												"title"	=> "Widoczna w menu",
												"help"	=> "Czy strona będzie widoczna w menu podstron",
												"id"	=> "dane_content_page__menu_visible",
												"name"	=> "dane[content_page__menu_visible]",
												"value"	=> $dane["content_page__menu_visible"],
												"size"	=> "block-level",
												"disabled" => 0,
												"validation" => 0,
												"prepend" => 0,
												"append" => 0,
												"rows" => 1,
												"options" => "",
												"xss_secured" => true
											));?>
										</div>
										<div class="span4">
											<?=sm_inputfield(array(
												"type"	=> "checkbox",
												"title"	=> "Widoczna w sitemap.xml",
												"help"	=> "Czy strona będzie widoczna w mapie serwisu. Pobierana m.in. przez Google",
												"id"	=> "dane_content_page__sitemap_visible",
												"name"	=> "dane[content_page__sitemap_visible]",
												"value"	=> $dane["content_page__sitemap_visible"],
												"size"	=> "block-level",
												"disabled" => 0,
												"validation" => 0,
												"prepend" => 0,
												"append" => 0,
												"rows" => 1,
												"options" => "",
												"xss_secured" => true
											));?>
										</div>
										<div class="span4">
											<?=sm_inputfield(array(
												"type"	=> "checkbox",
												"title"	=> "Strona aktywna",
												"help"	=> "Można zablokować całkowity dostęp do strony",
												"id"	=> "dane_content_page__enabled",
												"name"	=> "dane[content_page__enabled]",
												"value"	=> $dane["content_page__enabled"],
												"size"	=> "block-level",
												"disabled" => 0,
												"validation" => 0,
												"prepend" => 0,
												"append" => 0,
												"rows" => 1,
												"options" => "",
												"xss_secured" => true
											));?>
										</div>
									</div>

									<div class="row-fluid">
										<div class="span8">
											<?=sm_inputfield(array(
												"type"	=> "text",
												"title"	=> "Przekierowanie na URL",
												"help"	=> "Automatycznie przekieruj na podany adres url (dodaj przedrostek: http://, https://, mailto:, ftp://)",
												"id"	=> "dane_content_page__redirect_url",
												"name"	=> "dane[content_page__redirect_url]",
												"value"	=> $dane["content_page__redirect_url"],
												"size"	=> "block-level",
												"disabled" => 0,
												"validation" => 0,
												"prepend" => 0,
												"append" => 0,
												"rows" => 1,
												"options" => "",
												"xss_secured" => true
											));?>
										</div>
<?
	$inputfield_options=array();
	$inputfield_options[]="--- brak ---";
	if(is_array($CONTENT_PAGE_LONGNAME)){
		foreach($CONTENT_PAGE_LONGNAME AS $k=>$v) {
			$v = preg_replace( "/(@@@)/is", " / ", $v);

			if($content_page__id != $k) {
				$inputfield_options[ $k ] = $v;
			}
		}
	}
?>
										<div class="span4">
											<?=sm_inputfield(array(
												"type"	=> "select",
												"title"	=> "Przekierowanie na podstronę",
												"help"	=> "Automatycznie przekieruj na inną podstronę",
												"id"	=> "dane_content_page__redirect_page",
												"name"	=> "dane[content_page__redirect_page]",
												"value"	=> $dane["content_page__redirect_page"],
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

									<div class="row-fluid">
										<div class="span6">
											<?=sm_inputfield(array(
												"type"	=> "textarea",
												"title"	=> "Dodatkowe parametry",
												"help"	=> "dodatkowa konfiguracja strony",
												"id"	=> "dane_content_page__params",
												"name"	=> "dane[content_page__params]",
												"value"	=> $dane["content_page__params"],
												"size"	=> "block-level",
												"disabled" => 0,
												"validation" => 0,
												"prepend" => 0,
												"append" => 0,
												"rows" => 6,
												"options" => "",
												"xss_secured" => true
											));?>
										</div>
										<div class="span6">
											<h6><?=__("core", "Dopuszczalne znaczniki:")?></h6>
											<p><?=__("core", "Pola dzielone znakiem '|', np.:")?><br>
											<i><?=__("core", "parametr1=wartość1|parametr2=wartość2")?></i><br>
											<i>sitemap_disabled=(1/0)</i> - <?=__("core", "blokada prezentacji pozycji na mapie serwisu")?><br>
											<i>menu_disabled=(1/0)</i> - <?=__("core", "blokada prezentacji pozycji w menu")?><br>
											<i>redirect_url=(url)</i> - <?=__("core", "przekierowanie na podany adres zewnętrzny")?><br>
											<i>redirect_page=(page)</i> - <?=__("core", "przekierowanie na podaną podstronę serwisu")?><br>
											</p>
										</div>
									</div>
								</fieldset>

							</div>
							<div id="tabs-4" class="tab-pane">

								<div class="row-fluid">
									<div class="span6">
										<fieldset class="no-legend">
<?
	$inputfield_options=array();	
	if(is_array($CONTENT_PAGE_LONGNAME)){
		foreach($CONTENT_PAGE_LONGNAME AS $k=>$v) {
			$v = preg_replace( "/(.+?)@@@/is", " / .. ", $v);
			$v = preg_replace( "/(@@@)/is", " / ", $v);
			$inputfield_options[ $k ] = $v;
		}
	}
?>
											<?=sm_inputfield(array(
												"type"	=> "select-multi",
												"title"	=> "",
												"help"	=> "",
												"id"	=> "content_page__id",
												"name"	=> "content_page__id",
												"value"	=> $content_page__id,
												"size"	=> "block-level",
												"disabled" => 0,
												"validation" => 0,
												"prepend" => 0,
												"append" => 0,
												"rows" => 25,
												"options" => $inputfield_options,
												"xss_secured" => true
											));?>
<script>
$('#content_page__id').click(function(){
	window.location = '?content_page__id='+this.value;
});
</script>
										</fieldset>
									</div>
									<div class="span6">

<? 	if($content_page__id) { ?>
										<div class="fieldset-title">
											<div>Zmiana kolejności</div>
										</div>
										<fieldset class="no-legend">
<?		if ($result=content_page_show_parent($dane["content_page__idparent"])) { ?>
											<ul id="content_page-sort" class="sitemanager-sortable nav nav-pills nav-stacked">
<?		foreach($result AS $row){ ?>
												<li id="content_page-sort-<?=$row["content_page__id"]?>" class="sortitem">
													<a href="#"><?=$row["content_page__name"]?>
														<input type="hidden" name="content_page_sort[]" value="<?=$row["content_page__id"]?>">
													</a>
												</li>
<?			} ?>
											</ul>
<?		} ?>
<script type="text/javascript">
$(document).ready(
	function() {
		$("#content_page-sort").sortable({
			accept: 'sortitem',
			placeholder: "ui-state-highlight",
			axis: 'y',
			cursor: 's-resize',
			opacity: 0.6,
			start: function ( event, ui ) {
				height = $('#'+ui.item.attr('id')).height();
				$("#content_page-sort .ui-state-highlight").css({'height': height+'px'});
			},
			update: function (sorted) {
				var order = $('#sm-form').serialize();
				$.ajax({
					url: "<?=$page?>?ajax=content_page-sort", type: "POST", data: order,
					success: function(html){
						if (html != 'ok')
							alert(html);
					}
				});
			}
		});
	}
);
</script>
										</fieldset>
<?
	}
?>
									</div>
								</div>
							</div>
						</div>

<?	if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
						<div class="btn-toolbar">
							<input type=hidden name="dane[content_page__id]" value="<?=$content_page__id?>">
							<input type=hidden name="content_page__id" value="<?=$content_page__id?>">
<?		if ($content_page__id) { ?>
							<a class="btn btn-normal btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
							<a class="btn btn-normal btn-danger" id="action-delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<?		} else { ?>
							<a class="btn btn-normal btn-info" id="action-add"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD")?></a>
<?		} ?>
						</div>
<?	} ?>
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
<?
}
?>
<? include "_page_footer5.php"; ?>
