<?php

namespace UnserializeFixer;

abstract class CanHoldElement extends lengthElement {
	public $elements	 = [];
	
	public function getElements($exclude_ignore=false) {
		if($exclude_ignore===true){
			$elements= [];
			foreach($this->elements as $element){
				if($element->ignore==null){
					$elements[] = $element;
				}
			}
			
			return $elements;
		}else{
			return $this->elements;
		}
	}

	public function setElements($elements) {
		$this->elements = $elements;
	}

	public function addElement($element) {
		$this->elements[] = $element;
	}
	
	public function getCorruptedElements($exclude_ignore=false) {
		$corruptedElements= [];
		
		foreach($this->getElements() as $element){
			if($element instanceof CanHoldElement){
				return $element->getCorruptedElements($exclude_ignore);
			}elseif($element instanceof CorruptedElement || $element->getCorrupted()===true){
				if($exclude_ignore && $element->getIgnore()===true){
					continue;
				}
				$corruptedElements[] = $element;
			}
		}
		
		return $corruptedElements;
	}
	
	public function elementsCheck(){
		$max_nb_item = $this->getlength()*2;
		$count_elements = count($this->getElements(true));
		
		if($count_elements<$max_nb_item){
			//todo : avoid duplicate index
			for($count_elements;$count_elements<$max_nb_item;$count_elements++){
				$element = new ElementString();
				$element->setValue('XX_'.rand(0,9999));
				$element->setLength(strlen($element->getValue()));
				
				$this->addElement($element);
			}
		}elseif($count_elements>$max_nb_item){
			$this->setElements(array_slice($this->getElements(),0,$max_nb_item));
		}
		
	}

}
