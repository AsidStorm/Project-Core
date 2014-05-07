<?
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}

if($user->haveAccess('admin', 'password.recover')){
	$u = ORM::for_table('users')
		->find_one($_POST['id']);
		
	if($u){
		if(empty($_POST['newPassword']) OR empty($_POST['newPasswordRepeat'])){
			$array['desc'] = "Вы заполнили не все поля";
		}
		else{
			if($_POST['newPassword'] != $_POST['newPasswordRepeat']){
				$array['desc'] = "Пароли не совпадают";
			}
			else{
				$c = new crypt($u->login, $_POST['newPassword']);

				$u->password        = $c->get();
				$u->recoveryTime    = 0;
				$u->recoveryRequest = 0;
				
				if($u->save()){
					$array['status'] = 'success';
					
					LOG::send(array(
						'module'     => 'admin',
						'action'     => 'user.recover',
						'uid'        => $user->id,
						'id'         => $u->id,
						'desc'       => "Пароль успешно восстановлен [" . $u->soName . " " . $u->name . "]"
					));
					
					if(filter_var($u->mail, FILTER_VALIDATE_EMAIL) === false){
						$array['desc']   = "Пароль изменён. У пользователя не валидный [Email]. Сообщите об изменении пароля лично.";
					}
					else{
						$to      = $u->email;
						$subject = "Запрос на восстановление пароля обработан";
						$message = "Ваш новый пароль: " . $_POST['newPassword'];
						$headers = 'From: notifications@serega.com' . "\r\n" .
							'Reply-To: notifications@serega.com' . "\r\n" .
							'Content-Type: text/plain; charset=UTF-8\r\n' .
							'X-Mailer: PHP/' . phpversion();

						if(mail($to, $subject, $message, $headers)){
							$array['desc'] = "Пароль успешно изменён.";
						}
						else{
							$array['desc'] = "Пароль был изменён, но произошла ошибка при отправке письма. Сообщите об изменении пароля лично.";
						}
					}
				}
				else{
					$array['desc'] = "MySQL Error";
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