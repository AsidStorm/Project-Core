<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Авторизация</title>
		<link href="./css/login.css" media="all" rel="stylesheet" type="text/css" />
		<script src="./js/jquery.js" type="text/javascript"></script>
		<script src="./js/noty/jquery.noty.js" type="text/javascript"></script>
		<script src="./js/noty/layouts/top.js" type="text/javascript"></script>
		<script src="./js/noty/themes/default.js" type="text/javascript"></script>
		<script src="./js/modules/login.js" type="text/javascript"></script>
	</head>
	<body>
		<div class="form">
			<div class="avi"></div>
			<form action='./actions/user.login.php' method='POST'>
				<input type="text" value="Логин" name="USER_LOGIN" id="USER_LOGIN" />
				<input type="password" value="Пароль" name="USER_PASS" id="USER_PASS" />
				<input type="submit" value="Войти" />
			</form>
		</div>
		<div class="recovery">Забыли пароль? <a href="#">Нажмите сюда</a></div>
		<script type="text/javascript">
			{$ALERT}
		</script>
	</body>
</html>