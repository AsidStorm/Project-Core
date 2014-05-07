<?
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}

if($user->haveAccess('admin', 'index')){
	$pagination = array();
	$pagination['currPage'] = $_POST['setPage'];
	
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
			
	$pagination['maxPage']  = ceil(ORM::for_table('log')->count() / OBJ_PER_PAGE);
	$pagination['action']   = 'admin.get.logs';
	$pagination['callback'] = 'simplyPagination';
	
	$tpl->assign('PAGINATION', $pagination);
	
	$array['status']     = 'success';
	$array['data']       = array();
	$array['pagination'] = $tpl->fetch('./' . TPL_DIR . '/ajax_pagination.tpl');
	$array['onPage']     = count($logs);
	
	foreach($logs as $log){
		$tpl->assign('LOG', $log);
		
		$array['data'][] = $tpl->fetch('./' . TPL_DIR .'/parts/log.row.tpl');
	}
}
else{
	$array['desc'] = "У Вас нет прав доступа для этого действия";
}