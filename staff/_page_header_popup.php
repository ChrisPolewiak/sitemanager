<?
include "_header5.php";
?>
<body>

<style>
.navbar .brand img {	
	height: 20px !important;
	padding:0 15px;
}
</style>

<script>
function confDelete() {
	str = 'Czy jesteś pewien, że chcesz usunąć ten rekord?';
	return confirm(str);
}

window.resizeTo(1200,800);
</script>


	<div class="navbar navbar-static-top">
		<div class="navbar-inner">
			<a href="#" class="brand"><img src="/staff/img/sitemanager-logo-white.png" alt="sitemanager" border=0> Przeglądarka zasobów</a>
			<ul class="nav">
				<li id="btn-action-viewtype-thumbs" <?=($sm_viewtype=="thumbs"?"class=\"active\"":"")?>><a id="action-viewtype-thumbs" href="#"><i class="icon-th"></i>&nbsp;Widok miniatur</a></li>
				<li id="btn-action-viewtype-table" <?=($sm_viewtype=="table"?"class=\"active\"":"")?>><a id="action-viewtype-table" href="#"><i class="icon-th-list"></i>&nbsp;Widok tabeli</a></li>
				<li id="btn-action-viewtype-form" <?=($sm_viewtype=="form"?"class=\"active\"":"")?>><a id="action-viewtype-form" href="#"><i class="icon-plus"></i>&nbsp;Dodanie plików</a></li>
			</ul>
			<ul class="nav pull-right">
				<li><a href="javascript:if(opener) window.close(); else self.parent.tb_remove();"><i class="icon-black icon-remove"></i>&nbsp;close</a></li>
			</ul>
		</div>
	</div>
