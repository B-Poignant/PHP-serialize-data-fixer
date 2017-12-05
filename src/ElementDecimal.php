<?php

namespace UnserializeFixer;

class ElementDecimal extends BaseElement  {
	public $value	 = null;
	
	function getValue() {
		return $this->value;
	}

	function setValue($value) {
		$this->value = $value;
	}
	
	public function getSerializeElement(){
		return 'd:'.$this->getValue().';';
	}
	
}
