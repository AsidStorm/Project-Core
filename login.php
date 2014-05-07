<?

session_start();

require_once("./includes/core.php");

if(is_array($_SESSION['ALERT'])){
	$tpl->assign('ALERT', "callInfo('" . $_SESSION['ALERT'][1] . "');");
	unset($_SESSION['ALERT']);
}
else{
	$tpl->assign('ALERT', "");
}

$tpl->display('./' . TPL_DIR . '/login.tpl');