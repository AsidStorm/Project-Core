<?
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}

if($user->haveAccess("admin", "module.edit")){
	$module = new module(false, $user, $_POST['moduleID']);
		
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
	
	if(count($errorFields) == 0){
	
		if($module->isExists()){
			$moduleCheck = new module($_POST['define'], $user, false);
			
			if($moduleCheck->id == $module->id OR !$moduleCheck->isExists()){
				if(preg_match("/^[a-zA-Z]+$/i", $_POST['define'])){
					$oldModule = $module->this()->module;
					
					$module->this()->module  = $_POST['define'];
					$module->this()->title   = $_POST['title'];
					$module->this()->display = $_POST['display'];
					
					if($module->this()->save()){
						if($_POST['define'] != $oldModule){
							if(is_dir("./" . TPL_DIR . "/modules/" . $oldModule)){
								rename("./" . TPL_DIR . "/modules/" . $oldModule, "./" . TPL_DIR . "/modules/" . $_POST['define']);
							}
						}
						
						$array['status'] = 'success';
						$array['id']     = $module->id;
						$array['title']  = $module->title;
						$array['module'] = $module->module;
					}
					else{
						$array['desc']   = "MySQL Error";
					}
				}
				else{
					$array['errorFields'] = array( 'define' );
					$array['desc']        = "Поле [Определение] - может быть заполнено только английскими символами";
				}
			}
			else{
				$array['desc'] = "Модуль с таким определением уже существует";
			}
		}
		else{
			$array['desc'] = "Запрашиваемый модуль не найден";
		}
	
	}
	else{
		$array['errorFields'] = $errorFields;
		$array['desc']        = "Вы заполнили не все поля";
	}
}
else{
	$array['desc'] = "У Вас нет прав доступа для этого действия";
}