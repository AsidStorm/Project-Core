<?
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}

if($user->haveAccess('admin', 'user.edit')){
	$u = ORM::for_table('users')
		->find_one($_POST['id']);
		
	if($u){
		if(empty($_POST['soName']) OR empty($_POST['name']) OR empty($_POST['email']) OR empty($_POST['phone']) OR empty($_POST['group']) OR !is_numeric($_POST['group'])){
			$array['desc'] = "Вы не заполнили одно из ключевых полей!";
		}
		else{
			if(!preg_match('/((8|\+7)-?)?\(?\d{3,5}\)?-?\d{1}-?\d{1}-?\d{1}-?\d{1}-?\d{1}((-?\d{1})?-?\d{1})?/', $_POST['phone'])){
				$array['desc'] = "Поле [Телефон] заполнено не верно";
			}
			else{
				if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){
					$array['desc'] = "Поле [Email] заполнено не верно";
				}
				else{
					$check = ORM::for_table('users')
						->where_raw('(`login` = ? OR `email` = ? OR `phone` = ?)', array($_POST['login'], $_POST['email'], $_POST['phone']))
						->find_one();
					
					if($check){
						if($check->id == $u->id){
							$oldName   = $u->name;
							$oldSoName = $u->soName;
							$oldPhone  = $u->phone;
							$oldEmail  = $u->email;
							
							$u->name   = $_POST['name'];
							$u->soName = $_POST['soName'];
							$u->phone  = $_POST['phone'];
							$u->email  = $_POST['email'];
							
							$group = new group($_POST['group']);
							
							if($group->isExists()){
								$u->groupID = $_POST['group'];
								
								if(!empty($_POST['password'])){
									if($_POST['password'] == $_POST['repeatPassword']){
										$c = new crypt($u->login, $_POST['password']);
									
										$u->password = $c->get();
										
										if($u->save()){
											LOG::send(array(
												'module'     => 'admin',
												'action'     => 'user.edit',
												'uid'        => $user->id,
												'id'         => $u->id,
												'oldName'    => $oldName,
												'oldSoName'  => $oldSoName,
												'oldPhone'   => $oldPhone,
												'oldEmail'   => $oldEmail,
												'changePass' => true,
												'desc'       => "Был изменён пользователь [" . $u->soName . " " . $u->name . "]"
											));
											
											$_SESSION['ALERT'] = array(
												'admin', 'Пользователь успешно изменён', 'success'
											);
									
											$array['status'] = 'success';
										}
										else{
											$array['desc'] = 'MySQL Error';
										}
									}
									else{
										$array['desc'] = "Пароли не совпадают";
									}
								}
								else{
									if($u->save()){
										LOG::send(array(
											'module'     => 'admin',
											'action'     => 'user.edit',
											'uid'        => $user->id,
											'id'         => $u->id,
											'oldName'    => $oldName,
											'oldSoName'  => $oldSoName,
											'oldPhone'   => $oldPhone,
											'oldEmail'   => $oldEmail,
											'changePass' => false,
											'desc'       => "Был изменён пользователь [" . $u->soName . " " . $u->name . "]"
										));
											
										$_SESSION['ALERT'] = array(
											'admin', 'Пользователь успешно изменён', 'success'
										);
									
										$array['status'] = 'success';
									}
									else{
										$array['desc'] = 'MySQL Error';
									}
								}
							}
							else{
								$array['desc'] = "Группа назначения не найдена";
							}
						}
						else{
							$array['desc'] = "Пользователь с такими данными уже существует";
						}
					}
					else{
						$u->name   = $_POST['name'];
						$u->soName = $_POST['soName'];
						$u->phone  = $_POST['phone'];
						$u->email  = $_POST['email'];
							
						$group = new group($_POST['group']);
							
						if($group->isExists()){
							$u->groupID = $_POST['group'];
								
							if(!empty($_POST['password'])){
								$c = new crypt($u->login, $_POST['password']);
									
								$u->password = $c->get();
							}
							
							if($u->save()){
								$_SESSION['ALERT'] = array(
									'admin', 'Пользователь успешно изменён', 'success'
								);
								
								$array['status'] = 'success';
							}
							else{
								$array['desc'] = 'MySQL Error';
							}
						}
						else{
							$array['desc'] = "Группа назначения не найдена";
						}
					}
				}
			}
		}
	}
	else{
		$array['desc'] = "Запрашиваемый пользователь не найден";
	}
}
else{
	$array['desc'] = "У Вас нет прав доступа для этого действия";
}