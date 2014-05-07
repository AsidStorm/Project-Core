<? /* Edit */
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}

if($user->haveAccess('admin', 'group.settings.apply')){
	$group = new group($_POST['groupID']);
		
	if($group->isExists()){
		$aclArray = array();
	
		foreach($_POST as $key => $value){
			$e = explode("_", $key);
			if($e[0] == 'acl'){
				if($value == "true"){
					$aclArray[$e[1]] = $value;
				}
			}
		}
		
		$success = true;
		
		$firstID = -1;
			
		foreach($aclArray as $key => $param){
			$ga = ORM::for_table('groups-acl')->create();
				
			$ga->groupID = $group->id;
			$ga->aclID   = $key;
				
			if(!$ga->save()){
				$success = false;
				break;
			}
			else{
				if($firstID == -1){
					$firstID = $ga->id();
				}
			}
		}
			
		if($success){
			ORM::for_table('groups-acl') /* Delete old records */
				->where('groupID', $group->id)
				->where_lt('id', $firstID)
				->delete_many();
			
			LOG::send(array(
				'module' => 'admin',
				'action' => 'group.settings.apply',
				'uid'    => $user->id,
				'id'     => $group->id,
				'desc'   => "Была изменена группа [" . $group->name . "]"
			));
			
			$array['status'] = 'success';
		}
		else{
			if($firstID != -1){
				ORM::for_table('groups-acl')
					->where('groupID', $group->id)
					->where_gte('id', $firstID)
					->delete_many();
			}

			$array['desc']   = "MySQL Error";
		}
	}
	else{
		$array['desc'] = "Запрашиваемая группа не найдена";
	}
}
else{
	$array['desc'] = "У Вас нет прав доступа для этого действия";
}