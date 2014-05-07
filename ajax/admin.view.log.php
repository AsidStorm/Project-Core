<?
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}

if($user->haveAccess('admin', 'view.log')){
	$log = ORM::for_table('log')
		->find_one($_POST['id']);
	
	if($log){
		$array['status']         = 'success';
		$array['data']['id']     = $log->id;
		$array['data']['desc']   = $log->desc;
		$array['data']['json']   = print_r($log->json, true);
		$array['data']['module'] = $log->module;
		$array['data']['action'] = $log->action;
		$array['data']['userID'] = $log->uid;
		$array['data']['time']   = date("d.m.Y H:i:s", $log->time);
		$array['data']['ip']     = $log->ip;
	}
	else{
		$array['desc'] = "Запрашиваемый лог не найден";
	}
}
else{
	$array['desc'] = "У Вас нет прав доступа для этого действия";
}