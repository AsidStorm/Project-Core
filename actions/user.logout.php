<?

session_start();

require_once("./require.php");
require_once("./../includes/online.class.php");
require_once("./../includes/user.class.php");

if(empty($_SESSION['HASH'])){
	header('Location: login.php');
}
else{
	$online = new online($_SESSION['HASH']);
	
	if(!$online->check()){
	
		$_SESSION['ALERT'] = array( 'login', 'Вы были отключены от системы' );
		unset($_SESSION['HASH']);
		header('Location: login.php');
		
	}
}

$user = new user($online->uID());

LOG::send(array(
	'module' => 'user',
	'action' => 'logout',
	'uid'    => $user->id,
	'desc'   => "Пользователь вышел из сети"
));

$online->offline();

session_destroy();
header("Location: ../login.php");