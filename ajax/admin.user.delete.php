<?
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}

if($user->haveAccess('admin', 'user.delete')){
	$u = ORM::for_table('users')
		->find_one($_POST['id']);
		
	if($u){
		$u->delete = 1;
		
		if($u->save()){
			LOG::send(array(
				'module' => 'admin',
				'action' => 'user.delete',
				'uid'    => $user->id,
				'id'     => $u->id,
				'desc'   => "Был удалён пользователь [" . $u->soName . " " . $u->name . "]"
			));
			
			$array['status'] = 'success';
		}
		else{
			$array['desc'] = "MySQL Error";
		}
	}
	else{
		$array['desc'] = "Запрашиваемый пользователь не найден";
	}
}
else{
	$array['desc'] = "У Вас нет прав доступа для этого действия";
}