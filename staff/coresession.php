<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$__core_session__sid = $_REQUEST["__core_session__sid"];

if(!is_array($ERROR))
{
	if ( isset($action["delete"]) )
	{
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		core_session_delete($__core_session__sid);
	}
}

else
	$__core_session__sid = $__core_session__sid ? $__core_session__sid : "0";

if( $__core_session__sid > 0 )
{
	if (! ($dane = core_session_get( $__core_session__sid )) )
		$__core_session__sid=0;
}

include "_page_header5.php";

if (!$__core_session__sid)
{
?>
					<legend>Sesje użytkowników</legend>
					<table class="table">
						<thead>
							<tr>
								<th>Ostatni dostęp</th>
								<th>Adres IP</th>
								<th>Adres URL</th>
								<th>Użytkownik</th>
								<th>KILL !!!</th>
							</tr>
						</thead>
						<tbody>
<?
	if($result=core_session_fetch_all())
	{
		while($row=$result->fetch(PDO::FETCH_ASSOC))
		{
			$session_data = json_decode($row["core_session__data"],1);

#echo "<pre>";
#print_r( json_decode($row["core_session__data"]) );
#print_r($row);
#echo "</pre>";
?>
							<tr>
								<td><a href="?__core_session__sid=<?=$row["core_session__sid"]?>"><?=$row["core_session__lastused"]?></a></td>
								<td><?=$row["core_session__remoteaddr"]?></td>
								<td><?=$session_data["sm_core"]["page"]?></td>
								<td><?=$session_data["content_user"]["content_user__username"]?></td>
<?
			if($row["core_session__sid"]!=$core_session__sid)
			{
?>
								<td><a href="?__core_session__sid=<?=$row["core_session__sid"]?>&action[delete]=1" onClick="return confDelete()" title="<?=__("CORE","BUTTON__DELETE")?>"><i class="icon-remove"></i></a></td>
<?
			}
			else {
?>
								<td></td>
<?
			}
?>
							</tr>
<?
		}
	}
?>
						</tbody>
					</table>
<?
}
else
{
?>
					<div class="btn-toolbar">
						<div class="btn-group">
							<a class="btn btn-small btn-info" href="?"><i class="icon-list icon-white"></i>&nbsp;<?=__("core", "BUTTON__BACK_TO_LIST")?></a>
						</div>
					</div>
					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">
						<fieldset class="no-legend">
							<?=sm_inputfield(array(
								"type"	=> "text",
								"title"	=> "Ostatnia operacja",
								"help"	=> "",
								"id"	=> "",
								"name"	=> "",
								"value"	=> $dane["core_session__lastused"],
								"size"	=> "block-level",
								"disabled" => 1,
								"validation" => 0,
								"prepend" => 0,
								"append" => 0,
								"rows" => 1,
								"options" => "",
								"xss_secured" => 1
							));?>
							<?=sm_inputfield(array(
								"type"	=> "text",
								"title"	=> "Adres IP",
								"help"	=> "",
								"id"	=> "",
								"name"	=> "",
								"value"	=> $dane["core_session__remoteaddr"],
								"size"	=> "block-level",
								"disabled" => 1,
								"validation" => 0,
								"prepend" => 0,
								"append" => 0,
								"rows" => 1,
								"options" => "",
								"xss_secured" => 1
							));?>
							<?=sm_inputfield(array(
								"type"	=> "text",
								"title"	=> "Przeglądarka",
								"help"	=> "",
								"id"	=> "",
								"name"	=> "",
								"value"	=> $dane["core_session__useragent"],
								"size"	=> "block-level",
								"disabled" => 1,
								"validation" => 0,
								"prepend" => 0,
								"append" => 0,
								"rows" => 1,
								"options" => "",
								"xss_secured" => 1
							));?>
							<?=sm_inputfield(array(
								"type"	=> "textarea",
								"title"	=> "Dane sesji",
								"help"	=> "",
								"id"	=> "",
								"name"	=> "",
								"value"	=> var_export(json_decode($dane["core_session__data"],1),1),
								"size"	=> "block-level",
								"disabled" => 1,
								"validation" => 0,
								"prepend" => 0,
								"append" => 0,
								"rows" => 15,
								"options" => "",
								"xss_secured" => 0
							));?>
						</fieldset>

<?
	if (sm_core_content_user_accesscheck($access_type_id."_WRITE"))
	{
?>
						<div class="btn-toolbar">
							<input type=hidden name="__core_session__sid" value="<?=$__core_session__sid?>">
<?
		if ($__core_session__sid != $core_session__sid)
		{
?>
							<a class="btn btn-normal btn-danger" id="action-delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<?
		}
?>
						</div>
<?
	}
?>
<script>
$('#action-delete').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[delete]" value=1>');
	$('#sm-form').submit();
});
</script>
					</form>
<?
}
?>

<? include "_page_footer5.php" ?>