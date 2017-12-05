<?php

namespace UnserializeFixer;

class ElementNull extends BaseElement  {

	public function getSerializeElement(){
		return 'N;';
	}
}
