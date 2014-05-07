<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>{$TITLE}</title>
		<link href="./css/main.css" media="all" rel="stylesheet" type="text/css" />
		<script src="./js/jquery.js" type="text/javascript"></script>
		<script src="./js/noty/jquery.noty.js" type="text/javascript"></script>
		<script src="./js/jquery.modal.js" type="text/javascript"></script>
		<script src="./js/jquery.center.js" type="text/javascript"></script>
		<script src="./js/jquery.pickmeup.js" type="text/javascript"></script>
		<script src="./js/noty/layouts/top.js" type="text/javascript"></script>
		<script src="./js/noty/layouts/center.js" type="text/javascript"></script>
		<script src="./js/noty/layouts/topRight.js" type="text/javascript"></script>
		<script src="./js/noty/themes/default.js" type="text/javascript"></script>
		<script src="./js/jquery.color.js" type="text/javascript"></script>
		<script src="./js/jquery.autocomplete.js" type="text/javascript"></script>
		<script src="./js/core.js"></script>
		{if $HAS_JS}<script src="./js/modules/{$MODULE_NAME}.js"></script>{/if}
		<!--<script src="./js/jquery.search.js" type="text/javascript"></script>-->
	</head>
	<body>
		<div id="fader" class="fader fixed"></div>
		<div id="modal-wrap" class="scroll-fix-wrapper fixed">
			<div id="modal-layer">
				<div class="modal-content">
					<div id="modal-box">
						<a class="modal-close right sky" href="javascript:;">Закрыть</a>
						<div id="modal-title">
							<span class="title"></span>
						</div>
						<div>
							<div class="modal-data content">
								
							</div>
						</div>
						<div class="modal-loader">
							<img src='./img/icons/loader.gif' style='width: 128px; height: 128px;' />
						</div>
					</div>
				</div>
				<div class="bottom-space"></div>
			</div>
		</div>
		<div class='scroll-fix-wrapper'>
			<div>
				<div class='scroll-fix'>
					<div class='page'>
						<div class='header'>
							<table>
								<tr>
									<td class='login-td' onClick='window.location.href="./profile.php"'>
										{$CURR_USER->soName} {$CURR_USER->name}
									</td>
									<td>
										
									</td>
									{foreach $MODULES as $MODULE}
									<td class='{if $MODULE@key eq $MODULE_NAME}module-active{/if}' style='position: relative;'>
										<a href='./{$MODULE@key}.php'>
											{$MODULE}
										</a>
									</td>
									{/foreach}
									<td>
										<a href='./actions/user.logout.php'>Выход</a>
									</td>
								</tr>
							</table>
						</div>
						<div style='padding: 1px;'></div>
						<div class="menu">
							<ul class="items">
								{foreach $SUB_MENU as $ITEM}
								<li class="item {if $MODULE_ACTION eq {$ITEM@key}}{assign var='havePage' value='true'}active{/if}"><a href="./{$MODULE_NAME}.php?act={$ITEM@key}{if $smarty.get.id}&id={$smarty.get.id}{/if}" class="item-a">{$ITEM}</a></li>
								{/foreach}
								{if $havePage neq "true"}
								<li class="item active"><a href="javascript:;" class="item-a">{$MODULE_ACTION_NAME}</a></li>
								{/if}
							</ul>
							<br class="clr" />
						</div>
						<div style='padding: 2.5px;'></div>
						<div class='body'>
							<div style='padding: 5px;'></div>
							<div class='content'>