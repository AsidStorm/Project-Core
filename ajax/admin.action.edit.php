<?
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}

if($user->haveAccess('admin', 'action.edit')){
	$module = new module(false, $user, $_POST['moduleID']);
		
	if($module->isExists()){
		$acl = ORM::for_table('acl')
			->find_one($_POST['aclID']);
				
		if($acl){
				
			$action = ORM::for_table('actions')
				->find_one($acl->actionID);
				
			if($action){
					
				$errorFields = array();
					
				if(empty($_POST['title'])){
					$errorFields[] = 'title';
				}
					
				if(!is_numeric($_POST['display'])){
					$errorFields[] = 'display';
				}
				
				if(empty($_POST['desc'])){
					$errorFields[] = 'desc';
				}
					
				if(empty($_POST['define'])){
					$errorFields[] = 'define';
				}
					
				if(count($errorFields) == 0){
					
					if(preg_match("/^[a-zA-Z.]+$/i", $_POST['define'])){
						
						$actions = $module->getFullActions();
				
						$exists  = false;
				
						if(!empty($actions)){
				
							foreach($actions as $key => $value){
								if($value['action'] == $_POST['define'] AND $value['id'] != $action->id){
									$exists = true;
									break;
								}
							}
							
						}
							
						if(!$exists){
							$oldAction  = $action->action;
							
							$action->title   = $_POST['title'];
							$action->action  = $_POST['define'];
							$action->display = $_POST['display'];
							$action->desc    = $_POST['desc'];
							$action->pos     = $_POST['pos'];

							if($action->save()){
								if($oldAction != $_POST['define']){
									if(file_exists("./" . TPL_DIR . "/modules/" . $module->module . "/" . $oldAction . ".tpl")){
										rename("./" . TPL_DIR . "/modules/" . $module->module . "/" . $oldAction . ".tpl", "./" . TPL_DIR . "/modules/" . $module->module . "/" . $action->action . ".tpl");
									}
								}
								
								$array['status']   = 'success';
								$array['id']       = $action->id;
								$array['title']    = $action->title;
								$array['action']   = $action->action;
								$array['display']  = $action->display;
								$array['desc']     = $action->desc;
								$array['module']   = $module->module;
								$array['moduleID'] = $module->id;
								$array['TPL_EDIT'] = ( $user->haveAccess('admin', 'action.tpl.edit') ? 1 : 0 );
							}
							else{
								$array['desc'] = "MySQL Error";
							}
						
						}
						else{
							$array['desc'] = "Действие с таким определением уже существует";
						}
					}
					else{
						$array['errorFields'] = array( 'define' );
						$array['desc']        = "Поле [Определение] - может быть заполнено только английскими символами, и знаком \".\"";
					}
				}
				else{
					$array['errorFields'] = $errorFields;
					$array['desc']        = "Вы заполнили не все поля";
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