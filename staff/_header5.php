<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="pl"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="pl"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="pl"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="pl"> <!--<![endif]-->
<head>
	<title><?=$SOFTWARE_INFORMATION["application"]?> <?=$SOFTWARE_INFORMATION["version"]?> -- <?=$SITE_TITLE?> / <?=strip_tags( $menu[$menu_id]["name"] )?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="/admin/css/normalize.css" type="text/css" media="screen">
	<link rel="stylesheet" href="/admin/css/sitemanager.css" type="text/css" media="screen">
	<link rel="stylesheet" href="/admin/css/bootstrap.min.css" type="text/css" media="screen">
	<link rel="stylesheet" href="/admin/css/jquery-ui-1.9.2.custom.min.css" type="text/css" media="screen">
<?/*
	<link rel="stylesheet" href="/admin/css/jquery.dataTables.css" type="text/css" media="screen">
*/?>
	<link rel="stylesheet" href="/admin/css/jquery.dataTables_bootstrap.css" type="text/css" media="screen">
<? if ( isset($_REQUEST["sm_theme"]) && $_REQUEST["sm_theme"]) { ?>
	<link rel="stylesheet" href="/admin/css/theme-<?=$_REQUEST["sm_theme"]?>.css" type="text/css" media="screen">
<? } ?>

	<script language="JavaScript" type="text/javascript" src="/admin/js/jquery-1.8.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="/admin/js/jquery-ui-1.9.2.custom.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="/admin/js/sitemanager.js"></script>

	<script language="JavaScript" type="text/javascript" src="/admin/js/jquery.dataTables.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="/admin/js/jquery.dataTables_bootstrap.js"></script>
	<script language="JavaScript" type="text/javascript" src="/admin/js/bootstrap.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="/admin/js/bootstrap-popover.js"></script>
	<script language="JavaScript" type="text/javascript" src="/admin/js/jquery.cookie.js"></script>
	<script language="JavaScript" type="text/javascript" src="/admin/js/jquery.mjs.nestedSortable.js"></script>
	
</head>