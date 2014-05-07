<?
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}

if($user->haveAccess('admin', 'module.delete')){
	$module = new module(false, $user, $_POST['moduleID']);
		
	if($module->isExists()){
		if(in_array($module->module, array('admin'))){ // ???
			$array['desc'] = "Нельзя удалить системный модуль";
		}
		else{
			if($module->hasActions()){
				$array['desc'] = "Модуль нельзя удалить, так как он содержит действия";
			}
			else{
				$oldModule = $module->module;
				
				if($module->delete()){
					if(is_dir("./" . TPL_DIR . "/modules/" . $oldModule)){
						rmdir("./" . TPL_DIR . "/modules/" . $oldModule);
					}
					
					$array['status'] = 'success';
				}
				else{
					$array['desc'] = "MySQL Error";
				}
			}
		}
	}
	else{
		$array['desc'] = "Запрашиваемый модуль не найден";
	}
}
else{
	$array['desc'] = "У Вас нет прав доступа для этого действия";
}