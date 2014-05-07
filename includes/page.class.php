<?

class page{
	public function redirect($array){
		$query = "";
		foreach($array as $key => $value){
			if($key != 'module' && $key != 'action'){
				$query .= "&" . $key . "=" . $value;
			}
		}
		
		header('Location: ./' . $array['module'] . '.php?act=' . $array['action'] . $query);
		die();
	}
}