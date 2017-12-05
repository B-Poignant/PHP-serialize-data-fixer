<?php

namespace UnserializeFixer;

class ElementArray  extends CanHoldElement {
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
