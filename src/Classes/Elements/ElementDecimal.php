<?php

namespace UnserializeFixer\Elements;

use UnserializeFixer\BaseElement;

class ElementDecimal extends BaseElement
{
	public $value = null;
	
	public function getSerializeElement(): string
	{
		return 'd:' . $this->getValue() . ';';
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
