<?

session_start();

require_once("./require.php");

if(!isset($_POST['USER_LOGIN']) OR !isset($_POST['USER_PASS'])){
	$_SESSION['ALERT'] = array( 'login', 'Вы заполнили не все поля' );
	header('Location: ../login.php');
}
else{
	$c = new crypt($_POST['USER_LOGIN'], $_POST['USER_PASS']);
	
	$user = ORM::for_table('users')
		->where('login', $_POST['USER_LOGIN'])
		->find_one();
		
	if(!$user){
		$_SESSION['ALERT'] = array( 'login', 'Запрашиваемый пользователь не найден' );
		header('Location: ../login.php');
	}
	else{
		if($user->password != $c->get()){
			$_SESSION['ALERT'] = array( 'login', 'Не верный пароль' );
			header('Location: ../login.php');
		}
		else{
			if($user->delete != 0){
				$_SESSION['ALERT'] = array( 'login', 'Запрашиваемый пользователь удалён' );
				header('Location: ../login.php');
			}
			else{
				$_SESSION['HASH'] = md5(time() . md5($_POST['USER_LOGIN'] . $_POST['USER_PASS']));
				
				LOG::send(array(
					'module' => 'user',
					'action' => 'login',
					'uid'    => $user->id,
					'desc'   => "Пользователь вошёл в сеть"
				));
				
				ORM::for_table('online')
					->where_equal('userID', $user->id)
					->delete_many();
				
				$online = ORM::for_table('online')->create();
				
				$online->hash       = $_SESSION['HASH'];
				$online->userID     = $user->id;
				$online->lastACTION = 'LOGIN';
				$online->lastTIME   = time();
				
				$online->save();
				
				header('Location: ../index.php');
			}
		}
	}
}