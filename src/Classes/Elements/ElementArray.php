<?php

namespace UnserializeFixer\Elements;

use UnserializeFixer\CanHoldElement;

class ElementArray extends CanHoldElement
{
	public function getSerializeElement(): string
	{
		
		$this->elementsCheck();
		
		$part = 'a:' . $this->getlength() . ':{';
		foreach ($this->getElements(true) as $index => $element) {
			$part .= $element->getSerialize();
		}
		$part .= '};';
		
		return $part;
	}
	
}
