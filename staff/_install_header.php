<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="pl"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="pl"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="pl"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="pl"> <!--<![endif]-->
<head>
	<title>SiteManager Install</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="/staff/css/normalize.css" type="text/css" media="screen">
	<link rel="stylesheet" href="/staff/css/sitemanager.css" type="text/css" media="screen">
	<link rel="stylesheet" href="/staff/css/bootstrap.min.css" type="text/css" media="screen">
	<link rel="stylesheet" href="/staff/css/font-awesome.css" type="text/css" media="screen">
<!--[if IE 7]>
	<link rel="stylesheet" href="/staff/css/font-awesome-ie7.css">
<![endif]-->
	<link rel="stylesheet" href="/staff/css/jquery-ui-1.9.2.custom.min.css" type="text/css" media="screen">

	<script language="JavaScript" type="text/javascript" src="/staff/js/jquery-1.8.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="/staff/js/jquery-ui-1.9.2.custom.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="/staff/js/sitemanager.js"></script>

	<script language="JavaScript" type="text/javascript" src="/staff/js/bootstrap.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="/staff/js/bootstrap-popover.js"></script>

</head>

<body>
	<div class="navbar navbar-static-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand" href="http://cms.root.pl" target="_new"><img src="/staff/img/sitemanager-logo-white.png" alt="sitemanager" border=0 style="position:absolute;margin-top:-5px"></a>
				<ul class="nav pull-right">
					<li>
						<a href="#">Instalacja</a>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="container" id="body">
		<ul class="breadcrumb">
<?
	foreach($INSTALL_STEPS AS $k=>$v) {
		if(isset($_SESSION["step"][$k]) && $_SESSION["step"][$k]) {
?>
			<li><a <?=($k==$step?"class=\"active\"":"")?> href="/install/<?=$k?>"><?=$v?></a> <span class="divider">/</span></li>
<?
		} else {
?>
			<li><?=$v?> <span class="divider">/</span></li>
<?
		}
	}
?>
		</ul>

		<div class="row-fluid">
			<div class="span2">
				...
			</div>
			<div class="span8">
