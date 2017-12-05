<?php

namespace UnserializeFixer;

abstract class CanHoldElement extends lengthElement {
	public $elements	 = [];
	
	function getElements() {
		return $this->elements;
	}

	function setElements($elements) {
		$this->elements = $elements;
	}

	function addElement($element) {
		$this->elements[] = $element;
	}
	
	function getCorruptedElements($exclude_ingore=false) {
		$corruptedElements= [];
		
		foreach($this->getElements() as $element){
			if($element instanceof CanHoldElement){
				return $element->getCorruptedElements($exclude_ingore);
			}elseif($element instanceof CorruptedElement || $element->getCorrupted()===true){
				if($exclude_ingore && $element->getIgnore()===true){
					continue;
				}
				$corruptedElements[] = $element;
			}
		}
		
		return $corruptedElements;
	}

}
