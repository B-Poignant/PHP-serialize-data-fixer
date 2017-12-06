<?php

namespace UnserializeFixer\Elements;

class ElementDecimal extends \UnserializeFixer\BaseElement  {
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
