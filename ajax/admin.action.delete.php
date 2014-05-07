<?
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}
	
if($user->haveAccess('admin', 'action.delete')){
	$module = new module(false, $user, $_POST['moduleID']);
		
	if($module->isExists()){
		$acl = ORM::for_table('acl')
			->find_one($_POST['aclID']);
				
		if($acl){
				
			$action = ORM::for_table('actions')
				->find_one($acl->actionID);
					
			$tmpID = $acl->actionID;
				
			if($action){
				if(!$action->system){
					if($_POST['moduleID'] == $acl->moduleID && $_POST['actionID'] == $action->id){
						$oldAction = $action->action;
						
						$ga = ORM::for_table('groups-acl')
							->where('aclID', $acl->id);
							
						ORM::get_db()->beginTransaction();
						
						if($acl->delete()){
							if($action->delete()){
								if($ga->delete_many()){
									ORM::get_db()->commit();
									
									if(file_exists("./" . TPL_DIR . "/modules/" . $module->module . "/" . $oldAction . ".tpl")){
										copy("./" . TPL_DIR . "/modules/" . $module->module . "/" . $oldAction . ".tpl", "./" . TPL_DIR . "/backup/" . $module->module . "__" . $oldAction . "__" . time() . ".tpl");
										
										unlink("./" . TPL_DIR . "/modules/" . $module->module . "/" . $oldAction . ".tpl");
									}
									
									$array['status'] = "success";
									$array['id']     = $tmpID;
								}
								else{
									ORM::get_db()->rollBack();
									$array['desc'] = "MySQL Error";
								}
							}
							else{
								ORM::get_db()->rollBack();
								$array['desc'] = "MySQL Error";
							}
						}
						else{
							ORM::get_db()->rollBack();
							$array['desc'] = "MySQL Error";
						}
					}
					else{
						$array['desc'] = "Logic Error";
					}
				}
				else{
					$array['desc'] = "Нельзя удалить системное действие";
				}
			}
			else{
				$array['desc'] = "Действие не найдено";
			}
		}
		else{
			$array['desc'] = "MySQL Error";
		}
	}
	else{
		$array['desc'] = "Запрашиваемый модуль не найден";
	}
}
else{
	$array['desc'] = "У Вас нет прав доступа для этого действия";
}