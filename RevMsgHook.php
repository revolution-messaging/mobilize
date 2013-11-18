<?php

class RevMsgHook {
	protected $endSession = true;
	protected $response = null;
	protected $format = null;
	protected $method = null;
	protected $inputs = array(
		'strippedText' 	=> null,
		'msisdn'       	=> null,
		'mobileText'  	=> null,
		'keywordName' 	=> null,
		'keywordId'   	=> null,
		'shortCode'   	=> null,
		'subscriberId' => null,                  
		'metadataId'   => null,
		'oldValue'     => array(),
		'newValue'     => null
	);
	
	private function text_truncate($text, $number) {
		if (strlen($text) > $number) {
			$text = substr($text, 0, $number);
			$text = substr($text,0,strrpos($text," "));
			$etc = '';
			$text = $text.$etc;
		}
		return $text; 
	}

	public function __construct($format='xml',$method='post'){
		$this->format = $format;
		$this->method = $method;
		$this->retreiveinputs($this->method);
	}
	
	public function changeMsg($msg){
		$this->response = $msg;
	}
	
	public function changeEnd($end=true) {
		$this->endSession = $end;
	}
	
	public function getEndSession (){
		return $this->endSession;
	}
	
	public function getResponse (){
		return $this->response;
	}
	
	public function stripText(){
		$this->inputs['strippedText'] = preg_replace('/^('.$this->inputs['keywordName'].'\s+)/i','',$this->inputs['mobileText']);
	}
	
	public function retreiveInputs($method){
		if($method=='get') {
			$d=$_GET;
		} else if($method=='post') {
			file_get_contents('php://input');
			if($this->format=='xml') {
				$d = new SimpleXMLElement($d);
			} else if($this->format=='json') {
				$d = json_decode($d,true);
			}
		}
		foreach($d as $var => $val) {
			if(in_array($var,array_keys($this->inputs)))
				$this->inputs[$var] = $val;
		}
		$this->stripText();
	}
	
	public function getInputs(){
		return $this->inputs;
	}
	
	public function setInput($var,$val){
		if(in_array($var,array_keys($this->inputs))){
			$this->inputs[$var] = $val;
			return true;
		}else{
			return false;
		}
	}
	
	public function outputDynamicContent() {
		switch($this->format){
			case 'xml':
			default:
				return "<dynamiccontent><endSession>".$this->getEndSession(true)."</endSession><response>".htmlspecialchars($this->text_truncate($this->response,160))."</response></dynamiccontent>";
			case 'json':
				return json_encode(array(
					'endSession'=>$this->getEndSession(),
					'response'=>$this->text_truncate($this->response,160)
				));
		}
	}
}
?>
