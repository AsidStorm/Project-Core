<?

class LOG{
	public static function send($array){
		$json   = array();
		
		$params = array(
			'id',
			'oldTitle',
			'oldDirector',
			'oldManager',
			'oldPhone',
			'oldDesc'
		);
		
		$log    = ORM::for_table('log')->create();
		
		foreach($params as $param){
			if(!empty($array[$param])){
				$json[$param] = $array[$param];
			}
		}
		
		
		$log->uid    = $array['uid'];
		$log->module = $array['module'];
		$log->action = $array['action'];
		$log->desc   = $array['desc'];
		$log->json   = json_encode($json);
		$log->time   = time();
		
		$log->ip     = self::getIp();
		
		$log->save();
	}
	
	private static function getIp(){
		if(!empty($_SERVER['HTTP_CLIENT_IP'])){
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		return $ip;
	}
}