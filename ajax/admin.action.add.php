<?
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}

if($user->haveAccess('admin', 'action.add')){
	$errorFields = array();
		
	if(empty($_POST['title'])){
		$errorFields[] = 'title';
	}
		
	if(!is_numeric($_POST['display'])){
		$errorFields[] = 'display';
	}
		
	if(empty($_POST['define'])){
		$errorFields[] = 'define';
	}
		
	if(empty($_POST['desc'])){
		$errorFields[] = 'desc';
	}
		
	if(count($errorFields) == 0){
		$module = new module(false, $user, $_POST['moduleID']);
		
		if(preg_match("/^[a-zA-Z.]+$/i", $_POST['define'])){
		
			if($module->isExists()){
			
				$actions = $module->getFullActions();
			
				$exists  = false;
			
				if(!empty($actions)){
			
					foreach($actions as $key => $value){
						if($value['action'] == $_POST['define']){
							$exists = true;
							break;
						}
					}
			
				}
			
				if(!$exists){
					ORM::get_db()->beginTransaction();
			
					$action = ORM::for_table('actions')->create();
			
					$action->title   = $_POST['title'];
					$action->action  = $_POST['define'];
					$action->display = $_POST['display'];
					$action->desc    = $_POST['desc'];
					$action->pos     = $_POST['pos'];
			
					if($action->save()){
				
						$acl = ORM::for_table('acl')->create();
					
						$acl->moduleID = $module->id;
						$acl->actionID = $action->id();
					
						if($acl->save()){
							ORM::get_db()->commit();
							$array['status'] = 'success';
							
							if(!file_exists("./" . TPL_DIR . "/modules/" . $module->module . "/" . $action->action . ".tpl")){
								if($_POST['display'] == 1){
									$f = fopen("./" . TPL_DIR . "/modules/" . $module->module . "/" . $action->action . ".tpl", "a+");
									fclose($f);
								}
							}
							
							$tpl->assign('ACTION', array(
								'id'      => $action->id(),
								'title'   => $action->title,
								'action'  => $action->action,
								'module'  => $module->module,
								'display' => $action->display,
								'desc'    => $action->desc,
								'system'  => $action->system
							));
							
							$tpl->assign('CURR_MODULE', $module->id);
							$tpl->assign('CAN_EDIT',    $user->haveAccess('admin', 'action.edit'));
							$tpl->assign('CAN_DELETE',  $user->haveAccess('admin', 'action.delete'));
							$tpl->assign('TPL_EDIT',    $user->haveAccess('admin', 'action.tpl.edit'));
							
							$array['row']       = $tpl->fetch('./parts/module.row.tpl');
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
					$array['desc'] = "Такое действие уже существует!";
				}
			}
			else{
				$array['desc'] = "Запрашиваемый модуль не найден";
			}
		}
		else{
			$array['errorFields'] = array( 'define' );
			$array['desc']        = "Поле [Определение] - может быть заполнено только английскими символами, и знаком \".\"";
		}
	}
	else{
		$array['errorFields'] = $errorFields;
		$array['desc']        = "Вы заполнили не все поля!";
	}
}
else{
	$array['desc'] = "У Вас нет прав доступа для этого действия";
}