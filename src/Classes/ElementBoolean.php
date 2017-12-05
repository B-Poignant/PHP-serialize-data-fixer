<?php

namespace UnserializeFixer;

class ElementBoolean extends BaseElement  {
	public $value	 = null;
	
	function getValue() {
		return $this->value;
	}

	function setValue($value) {
		$this->value = $value;
	}

	public function getSerializeElement(){
		return 'b:'.$this->getValue().';';
	}
}
