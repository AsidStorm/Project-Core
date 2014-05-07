<? /* User check */
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}

if($user->haveAccess('admin', 'group.info')){
	$group = new group($_POST['id']);
		
	if($group->isExists()){
		if(!$group->isSystem()){
			if($group->delete()){
				$_SESSION['ALERT'] = array(
					'admin', 'Группа успешно удалена', 'success'
				);
				
				LOG::send(array(
					'module' => 'admin',
					'action' => 'group.delete',
					'uid'    => $user->id,
					'id'     => $group->id,
					'desc'   => "Была удалена группа [" . $group->name . "]"
				));
				
				$array['status'] = 'success';
			}
			else{
				$array['desc'] = "MySQL Error";
			}
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