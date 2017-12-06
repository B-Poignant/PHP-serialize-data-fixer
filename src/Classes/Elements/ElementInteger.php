<?php

namespace UnserializeFixer\Elements;

class ElementInteger extends \UnserializeFixer\BaseElement {
	public $value	 = null;
	
	function getValue() {
		return $this->value;
	}

	function setValue($value) {
		$this->value = $value;
	}

	public function getSerializeElement(){
		return 'i:'.$this->getValue().';';
	}

}
