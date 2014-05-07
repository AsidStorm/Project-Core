<?
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}

if($user->haveAccess('admin', 'user.add')){
	$groups = ORM::for_table('groups')
		->find_many();
			
	if($groups){
		$array['status'] = 'success';
			
		foreach($groups as $object){
			$array['data'][$object->id] = array(
				't' => $object->name
			);
		}
	}
	else{
		$array['desc'] = 'MySQL Error';
	}
}
else{
	$array['desc'] = "У Вас нет прав доступа для этого действия";
}