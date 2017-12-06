<?php

namespace UnserializeFixer\Elements;

class ElementNull extends \UnserializeFixer\BaseElement  {

	public function getSerializeElement(){
		return 'N;';
	}
}
