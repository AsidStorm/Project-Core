<?

class module{
	protected $_actions;
	protected $_module;
	protected $_user;
	protected $_exists = false;
	protected $_fullActions;
	
	public function __construct($moduleName, $user, $moduleID = false){
		if(!$moduleName){
			$this->_module = ORM::for_table('modules')
			->where('id', $moduleID)
			->find_one();
		}
		else{
			$this->_module = ORM::for_table('modules')
				->where('module', $moduleName)
				->find_one();
		}
		
		$this->_user = $user;
		
		if($this->_module){
			$this->_exists = true;
		}
		
		self::findActions();
		
		if(!$moduleName){
			self::fullActions();
		}
	}
	
	public function actions(){
		return $this->_actions;
	}
	
	public function getFullActions(){
		return $this->_fullActions;
	}
	
	public function hasActions(){
		return !!count($this->_fullActions);
	}
	
	public function isExists(){
		return $this->_exists;
	}
	
	function __get($term){
		return $this->_module->$term;
	}
	
	public function delete(){
		return $this->_module->delete();
	}
	public function this(){
		return $this->_module;
	}
	
	private function fullActions(){
		$actions = ORM::for_table('acl')
			->select('acl.id', 'aclID')
			->select('modules.title', 'moduleTitle')
			->select('modules.module', 'moduleAction')
			->select('modules.display', 'moduleDisplay')
			->select('actions.title', 'actionTitle')
			->select('actions.action', 'actionAction')
			->select('actions.display', 'actionDisplay')
			->select('actions.id', 'actionId')
			->select('actions.desc', 'actionDesc')
			->select('actions.system', 'actionSystem')
			->join('modules', array('modules.id', '=', 'acl.moduleID'))
			->join('actions', array('actions.id', '=', 'acl.actionID'))
			->where('moduleID', $this->_module->id)
			->order_by_asc('acl.id')
			->find_many();
		foreach($actions as $action){
			$this->_fullActions[] = array(
				'id'      => $action->actionId,
				'title'   => $action->actionTitle,
				'display' => $action->actionDisplay,
				'action'  => $action->actionAction,
				'module'  => $action->moduleAction,
				'desc'    => $action->actionDesc,
				'aclID'   => $action->aclID,
				'system'  => $action->actionSystem
			);
		}
	}
	
	private function findActions(){
		$actions = ORM::for_table('acl')
			->join('actions', array('acl.actionID', '=', 'actions.id'))
			->where('moduleID', $this->_module->id)
			->where('actions.display', true)
			->order_by_asc('actions.pos')
			->find_many();
			
		foreach($actions as $action){
			if($this->_user->haveAccess($this->_module->module, $action->action)){
				$this->_actions[$action->action] = $action->title;
			}
		}
	}
}