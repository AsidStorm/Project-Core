<? /* User check */
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}

if($user->haveAccess('admin', 'group.info')){
	$group = new group($_POST['id']);
		
	if($group->isExists()){
		if(!$group->isSystem()){
			$array['status'] = 'success';
			$array['gName']  = $group->name;
		}
		else{
			$array['desc'] = "Нельзя удалять системную группу";
		}
	}
	else{
		$array['desc'] = "Запрашиваемая группа не найдена";
	}
}
else{
	$array['desc'] = "У Вас нет прав доступа для этого действия";
}