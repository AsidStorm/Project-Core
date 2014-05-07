<?
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}

if($user->haveAccess('admin', 'user.add')){
	$errorFields = array();
		
	if(empty($_POST['soName'])){
		$errorFields[] = 'soName';
	}
	
	if(empty($_POST['name'])){
		$errorFields[] = 'name';
	}
		
	if(empty($_POST['login'])){
		$errorFields[] = 'login';
	}
		
	if(empty($_POST['email'])){
		$errorFields[] = 'email';
	}
		
	if(empty($_POST['phone'])){
		$errorFields[] = 'phone';
	}
		
	if(!is_numeric($_POST['group'])){
		$errorFields[] = 'group';
	}
	
	if(empty($_POST['password'])){
		$errorFields[] = 'password';
	}
		
	if(count($errorFields) == 0){
		if(!preg_match('/((8|\+7)-?)?\(?\d{3,5}\)?-?\d{1}-?\d{1}-?\d{1}-?\d{1}-?\d{1}((-?\d{1})?-?\d{1})?/', $_POST['phone'])){
			$array['errorFields'] = array( 'phone' );
			$array['desc']        = "Поле [Телефон] заполнено не верно";
		}
		else{
			if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){
				$array['errorFields'] = array( 'email' );
				$array['desc']        = "Поле [Email] заполнено не верно";
			}
			else{
				$check = ORM::for_table('users')
					->where_raw('(`login` = ? OR `email` = ? OR `phone` = ?)', array($_POST['login'], $_POST['email'], $_POST['phone']))
					->find_one();
						
				if($check){
					if($check->login == $_POST['login']){
						$array['errorFields'] = array( 'login' );
						$array['desc']        = "Пользователь с таким логином уже существует";
					}
					else if($check->email == $_POST['email']){
						$array['errorFields'] = array( 'email' );
						$array['desc']        = "Пользователь с таким email'ом уже существует";
					}
					else if($check->phone == $_POST['phone']){
						$array['errorFields'] = array( 'phone' );
						$array['desc']        = "Пользователь с таким телефоном уже существует";
					}
				}
				else{
					$group = new group($_POST['group']);
					
					if($group->isExists()){
						$newUser = ORM::for_table('users')->create();
						
						$c = new crypt($_POST['login'], $_POST['password']);
						
						$newUser->login    = $_POST['login'];
						$newUser->email    = $_POST['email'];
						$newUser->name     = $_POST['name'];
						$newUser->soName   = $_POST['soName'];
						$newUser->phone    = $_POST['phone'];
						$newUser->password = $c->get();
						$newUser->groupID  = $group->id;
						
						if($newUser->save()){
							LOG::send(array(
								'module' => 'admin',
								'action' => 'user.add',
								'uid'    => $user->id,
								'id'     => $newUser->id(),
								'desc'   => "Был создан пользователь [" . $newUser->soName . " " . $newUser->name . "]"
							));
							
							$newUser->groupName = $group->name;
							
							$tpl->assign('CAN_DELETE', $user->haveAccess('admin', 'user.delete'));
							$tpl->assign('CAN_EDIT',   $user->haveAccess('admin', 'user.edit'));
							$tpl->assign("USER",       $newUser);
								
							$array['status'] = "success";
							$array['row']    = $tpl->fetch("./parts/user.row.tpl");
						}
						else{
							$array['desc'] = "MySQL Error";
						}
					}
					else{
						$array['errorFields'] = array( 'group' );
						$array['desc']        = "Указанная группа не найдена";
					}
				}
			}
		}
	}
	else{
		$array['errorFields'] = $errorFields;
		$array['desc']        = "Вы заполнили не все поля";
	}
}
else{
	$array['desc'] = "У Вас нет прав доступа для этого действия";
}