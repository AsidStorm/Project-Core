<?
require_once("config.php");

require_once("idiorm.php");

require_once("smarty/smarty.class.php");

ORM::configure('mysql:host=' . BASE . ';dbname=' . DB);
ORM::configure('username', USER);
ORM::configure('password', PASS);

ORM::configure('driver_options', array(
	PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
));

ORM::configure('error_mode', PDO::ERRMODE_SILENT);

$tpl = new Smarty;

$tpl->debugging      = false;
$tpl->caching        = false;
$tpl->cache_lifetime = 120;
$tpl->template_dir   = './tpl/';
$tpl->compile_dir    = './cache/';

require_once("crypt.class.php");
require_once("log.class.php");
require_once("online.class.php");
require_once("group.class.php");
require_once("user.class.php");
require_once("module.class.php");

require_once("page.class.php");