<?

class online{
	public $_isOnline = false;
	public $_userID   = -1;
	protected $_online;
	
	public function __construct($hash){
		$this->_online = ORM::for_table('online')
			->where('hash', $hash)
			->find_one();
			
		if($this->_online){
			$this->_userID   = $this->_online->userID;
			$this->_isOnline = true;
		}
	}
	
	public function uID(){
		return $this->_userID;
	}
	
	public function offline(){
		$this->_online->delete();
	}
	
	public function check(){
		return $this->_isOnline;
	}

}