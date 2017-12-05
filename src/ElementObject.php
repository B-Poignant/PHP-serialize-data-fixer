<?php

namespace UnserializeFixer;

class ElementObject  extends CanHoldElement {
	public $name	 = null;
	
	function getName() {
		return $this->name;
	}

	function setName($name) {
		$this->name = $name;
	}
	
	public function getSerialize(){
		$part = 'O:'.strlen($this->getName()).':"'.$this->getName().'":'.$this->getlength().':{';
		foreach($this->getElements() as $element){
			$part .= $element->getSerialize();
		}
		$part .= '};';
		
		return $part;
	}
}
