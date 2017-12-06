<?php

namespace UnserializeFixer\Elements;

class ElementObject  extends \UnserializeFixer\CanHoldElement {
	public $name	 = null;
	
	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}
	
	public function repairElement(){
		if($this->getName()===null){
			$this->setName('stdClass');
		}
		
		if($this->getLength()===null){
			$this->setLength(ceil(count($this->getElements(true))/2));
		}
	}
	
	public function getSerializeElement(){
		$this->elementsCheck();
		
		$part = 'O:'.strlen($this->getName()).':"'.$this->getName().'":'.$this->getLength().':{';
		foreach($this->getElements() as $element){
			$part .= $element->getSerialize();
		}
		$part .= '};';
		
		return $part;
	}
}
