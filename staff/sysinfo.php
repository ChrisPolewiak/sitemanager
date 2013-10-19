<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

include "_page_header5.php";
?>
					<div class="fieldset-title">
						<div><?=__("CORE", "SYSINFO__SECTION_MAIN_INFO")?></div>
					</div>
					<fieldset class="no-legend">
						<table class="table" width="100%">
							<thead>
								<tr>
									<th><?=__("CORE", "SYSINFO__FIELD_ELEMENT")?></th>
									<th width=150 nowrap><?=__("CORE", "SYSINFO__FIELD_VERSION")?></th>
								</tr>
							</thead>
							<tbody>
								<tr class=even>
									<td><?=__("CORE", "SYSINFO__FIELD_CORE")?></td>
									<td nowrap><?=$SOFTWARE_INFORMATION["version"]?> (<?=$SOFTWARE_INFORMATION["date"]?>)</td>
								</tr>
<?
if(is_array($SM_PLUGINS))
{
	$even=1;
	foreach($SM_PLUGINS AS $_plugin=>$plugin_data)
	{
		$even=$even?0:1;
?>
								<tr class="<?=$even?"even":"odd"?>">
									<td><?=__("CORE", "SYSINFO__PLUGIN")?>: <?=$plugin_data["name"]?> (<?=__("CORE", "SYSINFO__PLUGIN_AUTHOR")?>: <?=$plugin_data["author"]?>)</td>
									<td nowrap><?=$plugin_data["version"]?> (<?=$plugin_data["moddate"]?>)</td>
								</tr>
<?
	}
}
?>
							</tbody>
						</table>
					</fieldset>

					<div class="fieldset-title">
						<div><?=__("CORE", "SYSINFO__SECTION_LIBRARY")?></div>
					</div>
					<fieldset class="no-legend">
						<table class=table width="100%">
							<thead>
								<tr>
									<th><?=__("CORE", "SYSINFO__LIBRARY")?></th>
									<th width=200 nowrap><?=__("CORE", "SYSINFO__FIELD_VERSION")?></th>
									<th width=50 nowrap><?=__("CORE", "SYSINFO__FIELD_STATUS")?></th>
								</tr>
							</thead>
							<tbody>
								<tr class=even>
									<td><?=__("CORE", "SYSINFO__FIELD_PHP")?></td>
<?
$php_version = split("\.", PHP_VERSION);
$php_version_id = ($php_version[0] * 10000 + $php_version[1] * 100 + $php_version[2]);
if ($php_version_id >= 50300)
{
?>
									<td nowrap><?=PHP_VERSION?></td>
									<td nowrap><b style="color:green"><?=__("CORE", "SYSINFO__STATUS_OK")?></b></td>
<?
}
else {
?>
									<td nowrap colspan=2><b style="color:#c00000">
										<?=__("CORE", "SYSINFO__ERROR")?>
										<?=__("CORE", "SYSINFO__FIELD_PHP_ERROR_INSTALLED_VERSION", PHP_VERSION, "5.3.0")?>
									</b></td>
<?
}
?>
								</tr>
								<tr class=odd>
									<td><?=__("CORE", "SYSINFO__FIELD_MYSQL_SERVER")?></td>
									<td nowrap><?=mysql_get_server_info()?></td>
									<td nowrap>
<?
if( ! function_exists("mysql_query"))
	echo "<b style=\"color:#c00000\">".__("CORE", "SYSINFO__ERROR");
else
	echo "<b style=\"color:green\">".__("CORE", "SYSINFO__STATUS_OK")."</b>";
?>      
									</td>
								</tr>
								<tr class=even>
									<td><?=__("CORE", "SYSINFO__LIBRARY")?> PEAR Mail</td>
<?
if( ! @include_once "Mail.php")
{
	echo "<td colspan=2><b style=\"color:#c00000\">".__("CORE", "SYSINFO__ERROR")."</b>";
	echo " - <a href=\"http://pear.php.net/package/Mail\">http://pear.php.net/package/Mail</a><br>";
	echo " ".("instalacja").": pear install --alldeps --force Mail<br>";
	echo "</td>";
}
else
	echo "<td></td><td><b style=\"color:green\">".__("CORE", "SYSINFO__STATUS_OK")."</b></td>";
?>
									</td>
								</tr>
								<tr class=odd>
									<td><?=__("CORE", "SYSINFO__LIBRARY")?> PEAR Mail_mime</td>
<?
if( ! @include_once "Mail/mime.php")
{
	echo "<td colspan=2><b style=\"color:#c00000\">".__("CORE", "SYSINFO__ERROR")."</b>";
	echo " - <a href=\"http://pear.php.net/package/Mail_Mime\">http://pear.php.net/package/Mail_Mime</a><br>";
	echo " ".("instalacja").": pear install --alldeps --force Mail_mime<br>";
	echo "</td>";
}
else
	echo "<td></td><td><b style=\"color:green\">".__("CORE", "SYSINFO__STATUS_OK")."</b></td>";
?>
									</td>
								</tr>
								<tr class=odd>
									<td><?=__("CORE", "SYSINFO__LIBRARY")?> ImageMagick</td>
<?
if ( in_array("Imagick", get_declared_classes()) )
{
	echo "<td></td><td><b style=\"color:green\">".__("CORE", "SYSINFO__STATUS_OK")."</b></td>";
}
else
{
	echo "<td colspan=2><b style=\"color:#c00000\">".__("CORE", "SYSINFO__ERROR")."</b>";
	echo " - <a href=\"http://pl1.php.net/manual/en/imagick.installation.php\">http://pl1.php.net/manual/en/imagick.installation.php</a><br>";
	echo "</td>";
}
?>
								</tr>
								<tr class=odd>
									<td><?=__("CORE", "SYSINFO__LIBRARY")?> DOMDocument</td>
<?
if ( in_array("DOMDocument", get_declared_classes()) )
{
	echo "<td></td><td><b style=\"color:green\">".__("CORE", "SYSINFO__STATUS_OK")."</b></td>";
}
else
{
	echo "<td colspan=2><b style=\"color:#c00000\">".__("CORE", "SYSINFO__ERROR")."</b>";
	echo " - <a href=\"http://pl1.php.net/manual/en/class.domdocument.php\">http://pl1.php.net/manual/en/class.domdocument.php</a><br>";
	echo "</td>";
}
?>
								</tr>
								<tr class=odd>
									<td><?=__("CORE", "SYSINFO__LIBRARY")?> SimpleXML</td>
<?
if ( in_array("SimpleXMLElement", get_declared_classes()) )
{
	echo "<td></td><td><b style=\"color:green\">".__("CORE", "SYSINFO__STATUS_OK")."</b></td>";
}
else
{
	echo "<td colspan=2><b style=\"color:#c00000\">".__("CORE", "SYSINFO__ERROR")."</b>";
	echo " - <a href=\"http://pl1.php.net/manual/en/book.simplexml.php\">http://pl1.php.net/manual/en/book.simplexml.php</a><br>";
	echo "</td>";
}
?>
								</tr>

							</tbody>
						</table>
					</fieldset>

<? include "_page_footer5.php"; ?>