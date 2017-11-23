<?php

namespace UnserializeFixer;

class Fixer
{
	private static $_serialize_type = ['i','b','d','s','a'];
	private static $_steps_done=[];
	
	
	public static function run($serialized){
		echo '<p>steps_done : '.implode(",", self::$_steps_done).'</p>';
		$data = @unserialize($serialized);

		
		var_dump('serialized',$serialized);
		if($data===false){
			
			if(!in_array('last_item',self::$_steps_done)){
				self::$_steps_done[] = 'last_item';

				$serialized = self::handleLastItem($serialized);
				
				return self::run($serialized);
			}
			
			if(!in_array('array_not_close',self::$_steps_done)){
				self::$_steps_done[] = 'array_not_close';

				$serialized = self::handleArrayNotClose($serialized);
				
				return self::run($serialized);
				
			}
			
			if(!in_array('bracket',self::$_steps_done)){
				self::$_steps_done[] = 'bracket';
				
				$serialized = self::handleBracket($serialized);
				
				return self::run($serialized);
			}
			
			var_dump($serialized);
		}
		
		
		return $data;
	}	
	
	public static function handleLastItem($serialized){
		preg_match_all('/(['.implode("|",self::$_serialize_type).']):([0-9]{0,})/', $serialized, $matches, PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
				
		if($matches){
			$type = end($matches)[1][0];
			
			var_dump('type',$type);
			$serialized = self::handleLastItemByType($matches,$serialized,$type);
		}
		
		return $serialized;
	}
	
	public static function handleLastItemByType($matches,$serialized,$type){
		switch($type){
				case 's' : 
					$nb_char_missing = end($matches)[2][0]-(strlen($serialized) - end($matches)[2][1] - strlen(end($matches)[2][0])-2);
					
					var_dump('nb_char_missing',$nb_char_missing);
					if($nb_char_missing>0){
						$serialized.= str_repeat("X", $nb_char_missing).'"';
					}
					
					break;
					
				case 'i' :
				case 'b' :
					if(substr($serialized,-1)==':'){
						$serialized.= '1';
					}
					break;
				
				case 'a' : 
				
					//array_not_close gonna fix
					break;
				default :
				
					throw new Exception($type. ' is not a possible type handleLastItemByType');
		}	
		
		return $serialized;
	}
	
	public static function handleBracket($serialized){
		
		$missing_bracket = substr_count($serialized, '{')-substr_count($serialized, '}');
		var_dump('missing_bracket',$missing_bracket);
		
		if($missing_bracket>0){
			$serialized.= str_repeat("}", $missing_bracket);
		}
		
		return $serialized;
	}
	
	public static function handleArrayNotClose($serialized){
		
		//any array here ?
		preg_match_all('/a:([0-9]{0,}):{/', $serialized, $matches, PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		
		if($matches){
			
			foreach(array_reverse($matches) as $match){
				$content = substr($serialized,$match[1][1]+strlen($match[1][0])+2);

				preg_match_all('/['.implode("|",self::$_serialize_type).']:/', $content, $matches);
				
				var_dump('content',$content);
				$nb_element_missing = (int) $match[1][0]*2 - count(end($matches));
				
				var_dump('nb_element_missing',$nb_element_missing);
				if($nb_element_missing>0){
					for($i=0;$i<$nb_element_missing;$i++){
						$last_char = substr($serialized,-1);
						if($last_char!==';' && $last_char!=='{'){
							$serialized.=';';
						}
						$serialized.='s:'.strlen('X_'.$i).':"X_'.$i.'"';
					}
					
					$serialized .=';';
				}
			}
			
		}
		
		return $serialized;
	}
}