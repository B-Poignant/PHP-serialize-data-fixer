<?php

namespace UnserializeFixer\Elements;

use UnserializeFixer\BaseElement;

class ElementNull extends BaseElement
{
	
	public function getSerializeElement(): string
	{
		return 'N;';
	}
}
