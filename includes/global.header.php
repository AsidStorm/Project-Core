<?

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

if(!$user->isExists()){
	$_SESSION['ALERT'] = array( 'login', 'Вы были отключены от системы' );
	unset($_SESSION['HASH']);
	header('Location: login.php');
}

if($user->isDelete()){
	$_SESSION['ALERT'] = array( 'login', 'Вы были удалены из системы' );
	unset($_SESSION['HASH']);
	header('Location: login.php');
}

if(empty($_GET['act'])){
	$_GET['act'] = 'index';
}

$page = new page();

$tpl->assign("CURR_USER", $user);