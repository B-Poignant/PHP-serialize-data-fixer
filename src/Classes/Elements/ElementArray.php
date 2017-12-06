<?php

namespace UnserializeFixer\Elements;

class ElementArray  extends \UnserializeFixer\CanHoldElement {
	public function getSerializeElement(){
		
		$this->elementsCheck();
		
		$part = 'a:'.$this->getlength().':{';
		foreach($this->getElements(true) as $index=>$element){
			$part .= $element->getSerialize();
		}
		$part .= '};';
		
		return $part;
	}
	
}
