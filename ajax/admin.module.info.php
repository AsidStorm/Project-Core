<?
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}

if($user->haveAccess("admin", "module.info")){
	$module = new module(false, $user, $_POST['moduleID']);
		
	if($module->isExists()){
		$array['module'] = array(
			'title'   => $module->title,
			'define'  => $module->module,
			'display' => $module->display
		);
			
		$array['status'] = 'success';
	}
	else{
		$array['desc'] = "Запрашиваемый модуль не найден";
	}
}
else{
	$array['desc'] = "У Вас нет прав доступа для этого действия";
}