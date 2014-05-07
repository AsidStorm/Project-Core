<?

class group{
	protected $_group;
	protected $_acl;
	protected $_exists = false;
	protected $_access;

	public function __construct($id){
		$this->_group = ORM::for_table('groups')
			->where('id', $id)
			->find_one();
			
		if($this->_group){
			$this->_exists = true;
			self::acl();
		}
	}
	
	public function haveAccess($moduleID, $actionID){
		return ($this->_access[$moduleID][$actionID])?true:false;
	}
	
	public function __get($term){
		return $this->_group->$term;
	}
	
	private function acl(){
		$this->_acl = ORM::for_table('groups-acl')
			->join('acl', array('acl.id', '=', 'groups-acl.aclID'))
			->where('groups-acl.groupID', $this->_group->id)
			->find_many();
			
		foreach($this->_acl as $acl){
			$this->_access[$acl->moduleID][$acl->actionID] = true;
		}
	}
	
	public function delete(){
		if($this->_group->delete()){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function isSystem(){
		return $this->_group->system;
	}
	public function isExists(){
		return $this->_exists;
	}
}