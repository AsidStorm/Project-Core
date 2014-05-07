<?
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}

if($user->haveAccess('admin', 'user.info') OR $user->haveAccess('admin', 'password.recover')){
	$u = new user($_POST['id']);
		
	if($u->isExists()){
		$array['soName'] = $u->soName;
		$array['name']   = $u->name;
		$array['login']  = $u->login;
		$array['email']  = $u->email;
		$array['phone']  = $u->phone;
		$array['group']  = $u->groupID;
		$array['id']     = $u->id;
			
		$array['status'] = 'success';
	}
	else{
		$array['desc'] = "Запрашиваемый	пользователь не найден";
	}
}
else{
	$array['desc'] = "У Вас нет прав доступа для этого действия";
}