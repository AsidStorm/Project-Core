<?
set_time_limit(0);
session_start();

require_once('./includes/core.php');

require_once('./includes/global.header.php');

define('_AJAX_MODULE', true);

$array['status'] = 'failed';

foreach($_POST as $key => $value){ /* Remove spaces in fields */
	if(!is_array($value)){
		$_POST[$key] = trim($value);
	}
}

if(file_exists(ACTION_DIR . $_POST['act'] . ".php")){
	require_once(ACTION_DIR . $_POST['act'] . ".php");
}
else{
	$array['desc'] = 'Action not found. File [' . ACTION_DIR . $_POST['act'] . '.php' . '] not found!';
}

print json_encode($array);

die();