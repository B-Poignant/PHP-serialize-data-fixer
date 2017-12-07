<?php

namespace UnserializeFixer\Elements;

use UnserializeFixer\BaseElement;

class ElementBoolean extends BaseElement
{
	public $value = null;
	
	public function getSerializeElement(): string
	{
		return 'b:' . $this->getValue() . ';';
	}
	
	function getValue(): string
	{
		return $this->value;
	}
	
	function setValue(string $value): void
	{
		$this->value = $value;
	}
}
