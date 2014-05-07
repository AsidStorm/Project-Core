<?
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}

if($user->haveAccess('admin', 'group.settings')){
	$group = new group($_POST['id']);
		
	if($group->isExists()){
		$array['group']['title'] = $group->name;
		$array['group']['id']    = $group->id;
		
		$modules = ORM::for_table('modules')
			->find_many();
			
		$array['modules'] = array();
		
		$array['groupID'] = $_POST['id']; /* ? */
		
		foreach($modules as $module){

			$m = new module(false, $user, $module->id);
				
			$array['modules'][$m->module]['title']   = $m->title;
			$array['modules'][$m->module]['id']      = $m->id;
				
			$array['modules'][$m->module]['actions'] = array();
				
			if(count($m->getFullActions()) != 0){ /* Replace -> We have new function */
				
				foreach($m->getFullActions() as $action){
					$array['modules'][$m->module]['actions'][] = array(
						'title'  => $action['title'],
						'id'     => $action['id'],
						'access' => $group->haveAccess($m->id, $action['id']),
						'aclID'  => $action['aclID']
					);
				}
			
			}
			
		}
		
		$array['status'] = 'success';
	}
	else{
		$array['desc'] = "Запрашиваемая группа не существует";
	}
}
else{
	$array['desc'] = "У Вас нет прав доступа для этого действия";
}