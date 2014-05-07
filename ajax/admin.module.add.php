<?
if(!defined('_AJAX_MODULE')){
	die("Access denied");
}
	
if($user->haveAccess('admin', 'module.add')){
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
		$module = new module($_POST['define'], $user);
		if($module->isExists()){
			$array['errorFields'] = array( 'define' );
			$array['desc']        = "Такой модуль уже существует";
		}
		else{
			if(!preg_match("/^[a-zA-Z]+$/i", $_POST['define'])){
				$array['errorFields'] = array( 'define' );
				$array['desc']        = 'Поле [Определение] должно состоять только из английских букв';
			}
			else{
				$newModule = ORM::for_table('modules')->create();
		
				$newModule->title   = $_POST['title'];
				$newModule->display = $_POST['display'];
				$newModule->module  = $_POST['define'];
		
				if($newModule->save()){
					if(!is_dir("./" . TPL_DIR . "/modules/" . $newModule->module)){
						mkdir("./" . TPL_DIR . "/modules/" . $newModule->module);
					}
					
					$array['status'] = "success";
					$array['object'] = array(
						'id'    => $newModule->id(),
						'title' => $newModule->title
					);
				}
				else{
					$array['desc']   = "MySQL Error";
				}
			}
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