<?

session_start();

require_once('./includes/core.php');

require_once('./includes/global.header.php');

DEFINE("MODULE_NAME", "admin");

DEFINE("MODULE_ACTION", $_GET['act']);

$tpl->assign('TITLE', 'Панель управления');

if($_GET['act'] == "index"){
	$pagination = array();
	$pagination['currPage'] = (int) (isset($_GET['page']))?$_GET['page']:1;	
	
	$logs = ORM::for_table('log')
		->select('log.*')
		->select('users.name', 'userName')
		->select('users.soName', 'userSoName')
		->select('users.id', 'userID')
		->join('users', array('users.id', '=', 'log.uid'))
		->limit( OBJ_PER_PAGE )
		->offset( OBJ_PER_PAGE * ($pagination['currPage'] - 1))
		->order_by_desc('log.time')
		->find_many();
	
	$tpl->assign("LOGS", $logs);

	$pagination['maxPage']  = ceil(ORM::for_table('log')->count() / OBJ_PER_PAGE);
	$pagination['action']   = 'admin.get.logs';
	$pagination['callback'] = 'simplyPagination';
	
	$tpl->assign('PAGINATION', $pagination);
}
else if($_GET['act'] == "users"){
	$pagination = array();
	$pagination['currPage'] = (int) (isset($_GET['page']))?$_GET['page']:1;	
	
	$users = ORM::for_table('users')
		->select('users.*')
		->select('groups.name', 'groupName')
		->join('groups', array('users.groupID', '=', 'groups.id'))
		->limit(OBJ_PER_PAGE)
		->offset( OBJ_PER_PAGE * ($pagination['currPage'] - 1))
		->find_many();
	
	$tpl->assign("USERS", $users);

	$pagination['maxPage']  = ceil(ORM::for_table('users')->count() / OBJ_PER_PAGE);
	
	$tpl->assign('CAN_DELETE', $user->haveAccess('admin', 'user.delete'));
	$tpl->assign('CAN_EDIT',   $user->haveAccess('admin', 'user.edit'));
	$tpl->assign('CAN_ADD',    $user->haveAccess('admin', 'user.add'));
	
	$tpl->assign('PAGINATION', $pagination);
}
else if($_GET['act'] == "groups"){
	$pagination = array();
	$pagination['currPage'] = (int) (isset($_GET['page']))?$_GET['page']:1;	
	
	$groups = ORM::for_table('groups')
		->select('groups.*')
		->select_expr('COUNT(`users`.id)', 'count')
		->left_outer_join('users', array('users.groupID', '=', 'groups.id'))
		->limit(OBJ_PER_PAGE)
		->offset( OBJ_PER_PAGE * ($pagination['currPage'] - 1))
		->group_by('groups.id')
		->find_many();
		
	$tpl->assign("GROUPS",     $groups);
	$tpl->assign("CAN_EDIT",   $user->haveAccess("admin", "group.settings"));
	$tpl->assign("CAN_DELETE", $user->haveAccess("admin", "group.delete"));
	$tpl->assign("CAN_ADD",    $user->haveAccess("admin", "group.add"));
	
	$pagination['maxPage']  = ceil(ORM::for_table('groups')->count() / OBJ_PER_PAGE);
	
	$tpl->assign('PAGINATION', $pagination);
}
else if($_GET['act'] == 'modules'){
	$modules = ORM::for_table('modules')
		->find_many();
	
	$tpl->assign('A_MODULES', $modules);
	
	$tpl->assign('CURR_MODULE', $_GET['id']);
	
	$module = new module(false, $user, $_GET['id']);
	
	$tpl->assign('CURR_TITLE', $module->title);
	
	if($module->isExists()){
		$tpl->assign("ACTIONS", $module->getFullActions());
	}
	
	$tpl->assign('CAN_EDIT',   $user->haveAccess('admin', 'action.edit'));
	$tpl->assign('CAN_DELETE', $user->haveAccess('admin', 'action.delete'));
	$tpl->assign('TPL_EDIT',   $user->haveAccess('admin', 'action.tpl.edit'));
}

require_once('./includes/global.footer.php');