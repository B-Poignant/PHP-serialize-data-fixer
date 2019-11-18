<?php

namespace UnserializeFixer;

abstract class LengthElement extends BaseElement
{
	public $length = null;
	
	public function getLength(): ?int
	{
		return $this->length;
	}
	
	public function setLength($length): void
	{
		$this->length = $length;
	}
}

