<?

class user{
	protected $_user;
	protected $_exists = false;
	protected $_delete = false;
	protected $_acl    = array();
	
	public function __construct($userID){
		$this->_user = ORM::for_table('users')
			->find_one($userID);
			
		if($this->_user){
			$this->_exists = true;
			if($this->_user->delete != 0){
				$this->_delete = true;
			}
			else{
				self::acl();
			}
		}
	}
	
	public function isDelete(){
		return $this->_delete;
	}
	public function isExists(){
		return $this->_exists;
	}
	
	public function haveAccess($module, $action){
		return $this->_acl[$module][$action];
	}
	
	private function acl(){
		$acl = ORM::for_table('groups-acl')
			->join('acl', array('acl.id', '=', 'groups-acl.aclID'))
			->join('modules', array('acl.moduleID', '=', 'modules.id'))
			->join('actions', array('acl.actionID', '=', 'actions.id'))
			->where('groupID', $this->groupID)
			->find_many();

		foreach($acl as $ac){
			$this->_acl[$ac->module][$ac->action] = true;
		}
	}
	
	public function __get($term){
		return $this->_user->$term;
	}
}