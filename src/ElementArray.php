<?php

namespace UnserializeFixer;

class ElementArray  extends CanHoldElement {
	public function getSerializeElement(){
		
		$max_nb_item = $this->getlength()*2;
		$count_elements = count($this->getElements());
		
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
		
		$part = 'a:'.$this->getlength().':{';
		foreach($this->getElements() as $index=>$element){
			if($index>=$max_nb_item){
				break;
			}
			$part .= $element->getSerialize();
		}
		$part .= '};';
		
		return $part;
	}
	
}
