<?
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}

if($user->haveAccess('admin', 'action.info')){
	$module = new module(false, $user, $_POST['moduleID']);
		
	if($module->isExists()){
		$acl = ORM::for_table('acl')
			->select('acl.id', 'aclID')
			->select('modules.module', 'moduleAction')
			->select('actions.title', 'actionTitle')
			->select('actions.action', 'actionAction')
			->select('actions.display', 'actionDisplay')
			->select('actions.pos', 'actionPos')
			->select('actions.id', 'actionId')
			->select('actions.desc', 'actionDesc')
			->join('modules', array('modules.id', '=', 'acl.moduleID'))
			->join('actions', array('actions.id', '=', 'acl.actionID'))
			->where('actionID', $_POST['actionID'])
			->where('moduleID', $module->id)
			->find_one();
			
		if($acl){
			$array['aclID']  = $acl->aclID;
			$array['action'] = $acl->actionAction;
			$array['module'] = $acl->moduleAction;
			$array['title']  = $acl->actionTitle;
			$array['desc']   = $acl->actionDesc;
			
			$array['display']  = $acl->actionDisplay;
			$array['actionID'] = $acl->actionId;
			$array['pos']      = $acl->actionPos;
			$array['moduleID'] = $module->id;
			
			$array['status'] = 'success';
		}
		else{
			$array['desc'] = "MySQL Error";
		}
	}
	else{
		$array['desc'] = "Запрашиваемый модуль не найден";
	}
}
else{
	$array['desc'] = "У Вас нет прав доступа для этого действия";
}