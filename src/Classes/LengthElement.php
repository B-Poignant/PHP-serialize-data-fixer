<?php

namespace UnserializeFixer;

abstract class LengthElement extends baseElement {
	public $length	 = null;
	
	public function getLength() {
		return $this->length;
	}

	public function setLength($length) {
		$this->length = $length;
	}
}
