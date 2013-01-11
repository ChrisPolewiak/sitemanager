								<div class="fieldset-title" id="ContentFileAttach">
									<div><?=__("core", "Załączniki")?></div><i class="icon-minus"></i>
								</div>
								<fieldset class="no-legend">
<?
	if($_GET[$__table."__id"])
		$backto = urlencode($_SERVER["REQUEST_URI"]);
	else
		$backto = urlencode($_SERVER["REQUEST_URI"]."?".$__table."__id=".$__tableid);
	
	if ( isset($_GET["contentnews__id"]) ) {
		$contentnews__id = $_GET["contentnews__id"];
	}
	if ( isset($_GET['id_contenttext']) ) {
		$contenttext__id = $_GET["contenttext__id"];
	}
?>
									<a href="contentfileassoc.php?content_file__tableid=<?=$__tableid?>&content_file__table=<?=$__table?>&backto=<?=$backto?>"><?=__("core", "Załączniki do obiektu")?></a>
								</fieldset>
