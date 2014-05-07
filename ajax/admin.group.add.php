<?
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}

if($user->haveAccess('admin', 'group.add')){
	$errorFields = array();
	if(empty($_POST['title'])){
		$errorFields[] = 'title';
	}
		
	if(empty($_POST['desc'])){
		$errorFields[] = 'desc';
	}
		
	if(count($errorFields) == 0){
		$group = ORM::for_table('groups')
			->where('name', $_POST['title'])
			->find_one();
			
		if(!$group){
			$newGroup = ORM::for_table('groups')->create();
			
			$newGroup->name = $_POST['title'];
			$newGroup->desc = $_POST['desc'];
			
			if($newGroup->save()){
				$array['status'] = 'success';
				
				$tpl->assign('GROUP', array(
					'id'    => $newGroup->id(),
					'count' => 0,
					'name'  => $newGroup->name,
					'desc'  => $newGroup->desc
				));
				
				LOG::send(array(
					'module' => 'admin',
					'action' => 'group.add',
					'uid'    => $user->id,
					'id'     => $newGroup->id(),
					'desc'   => "Была создана группа [" . $newGroup->name . "]"
				));
				
				$array['row']   = $tpl->fetch('./parts/group.row.tpl');
			}
			else{
				$array['desc'] = 'MySQL Error';
			}
		}
		else{
			$array['desc'] = 'Группа с похожим названием уэе существует';
		}
	}
	else{
		$array['errorFields'] = $errorFields;
		$array['desc']        = 'Вы заполнили не все поля!';
	}
}
else{
	$array['desc'] = "У Вас нет прав доступа для этого действия";
}