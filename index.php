<?

session_start();

require_once('./includes/core.php');

require_once('./includes/global.header.php');

DEFINE("MODULE_NAME", "index");

DEFINE("MODULE_ACTION", $_GET['act']);

$tpl->assign('TITLE', 'Главная страница');
$tpl->assign('PAGE_TITLE', 'Главная страница');

if($_GET['act'] == 'index'){

}

require_once('./includes/global.footer.php');