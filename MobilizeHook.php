<?php

function ttruncat($text,$numb) {
	if (strlen($text) > $numb) {
		$text = substr($text, 0, $numb);
		$text = substr($text,0,strrpos($text," "));
		$etc = null;
		$text = $text.$etc;
	}
	return $text; 
}

class MobilizeHook {
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
	
	
	public function __construct($format='xml',$method='post'){
		$this->format = $format;
		$this->method = $method;
		$this->retreiveinputs($this->method);
	}
	
	public function changemsg($msg){
		$this->response = $msg;
	}
	
	public function changeend($end=true) {
		$this->endSession = $end;
	}
	
	public function getEndSession (){
		return $this->endSession;
	}
	
	public function getResponse (){
		return $this->response;
	}
	
	public function stripText(){
		$this->inputs['strippedText'] = preg_replace('/^('.$this->inputs['keywordName'].'\s+)/i',null,$this->inputs['mobileText']);
	}
	
	public function retreiveinputs(){
		if($this->method=='get'){
			$d=$_GET;
		}elseif($this->method=='post'){
			file_get_contents('php://input');
			if($this->format=='xml'){
				$d = new SimpleXMLElement($d);
			}elseif($this->format=='json'){
				$d = json_decode($d,true);
			}
		}
		foreach($d as $var => $val){
			if(in_array($var,array_keys($this->inputs)))
				$this->inputs[$var] = $val;
			}
		$this->stripText();
	}
	
	public function getinputs(){
		return $this->inputs;
	}
	
	public function setInput($var,$val){
		if(in_array($var,array_keys($this->inputs)) && (gettype($val) == gettype($this->inputs[$var]))){
			$this->inputs[$var] = $val;
			return true;
		}else{
			return false;
		}
	}
	
	public function outputDC() {
		switch($this->format){
		case 'xml':
			return "<dynamiccontent><endSession>".$this->getEndSession(true)."</endSession><response>".htmlspecialchars(ttruncat($this->response,160))."</response></dynamiccontent>";
		case 'json':
			return json_encode(array(
				'endSession'=>$this->getEndSession(),
				'response'=>ttruncat($this->response,160)
			));
		}
	}
}
?>