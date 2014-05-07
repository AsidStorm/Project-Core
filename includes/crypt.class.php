<?

class crypt{
	public function __construct($a, $b){
		$this->a = $a;
		$this->b = $b;
		$this->s = "";
		self::calc();
	}
	public function get(){
		return $this->c;
	}
	private function calc(){
		$this->s = md5($this->a[strlen($this->a)] . $this->a[0] . md5(mb_strlen($this->b, "UTF-8") % mb_strlen($this->a, "UTF-8")));
		$this->c = md5(md5($this->b) . $this->s);
	}
}