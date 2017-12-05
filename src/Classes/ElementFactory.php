<?php

namespace UnserializeFixer;

class ElementFactory  {
	public static function makeElement($part){
		
		preg_match('/([ibdsaO]):([0-9.]{1,})/', $part, $matches);
		
		if(isset($matches[1])){
			$type = $matches[1];
			switch($type){
				case 'a' :
					$element =  new ElementArray();
					$element->setLength($matches[2]);

					break;
				case 'O' :
					$length_name = $matches[2];
					
					preg_match('/O:[0-9.]{0,}:"(.{'.$length_name.','.$length_name.'})":([0-9]{0,}):/', $part, $matches_object);
					
					$element =  new ElementObject();
					
					if(count($matches_object)==0){
						$element->setCause('no_length');
						$element->setCorrupted(true);
						
						break;
					}elseif(count($matches_object)==1){
						$element->setCause('invalid_length');
						$element->setCorrupted(true);
						
						break;
					}else{
						
						$element->setName($matches_object[1]);
						$element->setLength($matches_object[2]);

						break;
					}
					
				case 's' :
					$length = $matches[2];
					
					preg_match('/s:[0-9]{0,}:"(.{'.$length.','.$length.'})"/', $part, $matches_string);
					
					$element =  new ElementString();
					if(count($matches_string)==0){
						$element->setCorrupted(true);
						
						break;
					}else{
						
						$element->setLength($length);
						$element->setValue($matches_string[1]);

						break;
					}
				case 'b' :
					$element =  new ElementBoolean();
					$element->setValue($matches[2]);

					break;
				case 'i' :
					$element =  new ElementInteger();
					$element->setValue($matches[2]);

					break;
				case 'd' :
					$element =  new ElementDecimal();
					$element->setValue($matches[2]);

					break;
			}
		}else{
			if($part=='N'){
				$element =  new ElementNull();
			}else{
				$element =  new CorruptedElement();
				$element->setCorrupted(true);
			}
		}
		
		$element->setPart($part);
		
		return $element;
	}
}
