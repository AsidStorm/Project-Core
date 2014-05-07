<?
if(!$user->haveAccess(MODULE_NAME, MODULE_ACTION)){
	die("Access denied!");
}

$tpl->assign("MODULE_NAME",   MODULE_NAME);
$tpl->assign("MODULE_ACTION", MODULE_ACTION);
$tpl->assign("TPL_DIR",       TPL_DIR);
$tpl->assign("HAS_JS",        file_exists("./js/modules/" . MODULE_NAME . ".js"));

$qModules = ORM::for_table('modules')
	->where('display', true)
	->find_many();

$modules = array();
	
foreach($qModules as $module){
	if($user->haveAccess($module->module, "index")){
		$modules[$module->module] = $module->title;
	}
}

$module = new module(MODULE_NAME, $user);

$tpl->assign("SUB_MENU", $module->actions());
$tpl->assign("MODULES",  $modules);

if(is_array($_SESSION['ALERT'])){
	$tpl->assign('ALERT', "n('" . $_SESSION['ALERT'][1] . "', 'center', '" . (($_SESSION['ALERT'][2])?$_SESSION['ALERT'][2]:'error') . "');");
	unset($_SESSION['ALERT']);
}

$tpl->display('../' . TPL_DIR . '/page.tpl');