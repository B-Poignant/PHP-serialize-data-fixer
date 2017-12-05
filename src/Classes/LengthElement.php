<?php

namespace UnserializeFixer;

abstract class lengthElement extends baseElement {
	public $length	 = null;
	
	public function getlength() {
		return $this->length;
	}

	public function setLength($length) {
		$this->length = $length;
	}
}
