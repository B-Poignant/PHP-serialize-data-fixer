<?php

namespace UnserializeFixer\Elements;

use UnserializeFixer\LengthElement;

class ElementString extends LengthElement
{
	public $value = null;
	
	public function getSerializeElement(): string
	{
		return 's:' . $this->getlength() . ':"' . $this->getValue() . '";';
	}
	
	public function getValue(): string
	{
		return $this->value;
	}
	
	public function setValue(string $value): void
	{
		$this->value = $value;
	}
	
	public function repairElement(): void
	{
		preg_match('/s:([0-9]{1,}):"(.{1,})"|s:([0-9]{1,}):"(.{1,})|s:([0-9]{1,})/', $this->getPart(), $matches);
		
		if (isset($matches[5])) {
			$length = $matches[5];
		} elseif (isset($matches[3], $matches[4])) {
			$length = $matches[3];
			$value = $matches[4];
		} elseif (isset($matches[1], $matches[2])) {
			$length = $matches[1];
			$value = $matches[2];
		}
		
		if (!isset($length)) {
			$length = '1';
		}
		if (!isset($value)) {
			$value = str_repeat('X', $length);
		}
		
		if ($length != strlen($value)) {
			$length = strlen($value);
		}
		
		$this->setLength($length);
		$this->setValue($value);
	}
}
