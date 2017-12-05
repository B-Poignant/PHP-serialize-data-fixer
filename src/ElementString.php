<?php

namespace UnserializeFixer;

class ElementString  extends lengthElement {
	public $value	 = null;
	
	public function getValue() {
		return $this->value;
	}

	public function setValue($value) {
		$this->value = $value;
	}
	
	public function getSerializeElement(){
		return 's:'.$this->getlength().':"'.$this->getValue().'";';
	}
	
	public function repairElement(){
		preg_match('/s:([0-9]):"(.{1,})"|s:([0-9]):"(.{1,})|s:([0-9])/', $this->getPart(), $matches);
		
		if(isset($matches[5])){
			$length = $matches[5];
		}elseif(isset($matches[3],$matches[4])){
			$length = $matches[3];
			$value = $matches[4];
		}elseif(isset($matches[1],$matches[2])){
			$length = $matches[1];
			$value = $matches[2];
		}

		if(!isset($length)){
			$length = '1';
		}
		if(!isset($value)){
			$value = str_repeat('X', $length);
		}

		if($length!=strlen($value)){
			$length = strlen($value);
		}

		$this->setLength($length);
		$this->setValue($value);
		
		return true;
	}
}
