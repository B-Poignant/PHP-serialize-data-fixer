<?php
use UnserializeFixer\Interfaces;
use UnserializeFixer\Exceptions;
 
namespace UnserializeFixer;

class Fixer implements Interfaces\iFixer
{
	private static $_serialize_type = ['i','b','d','s','a','O'];
	private static $_steps_left=null;
	//private static $_resolve_method = 'complete';
	//private static $_resolve_method = 'remove';
	
	/**
	 * writeLog to overwrite see Implements Folder
	 * @param string $message
	 * @param type $data
	 * @param string $level
	 */
	public static function writeLog($message, $data=null,$level='info'){
		echo $message;
		var_dump($data);
	}

	public static function run($serialized){
		self::writeLog('run');
		
		self::$_steps_left=[
			'last_item',
			'array_not_close',
			'invalid_length',
			'bracket',
		];
		
		return self::treat($serialized);
	}
	
	/**
	 * Main function
	 * @param string $serialized
	 * @return type
	 * @throws \UnserializeFixer\Exceptions\CorruptedException
	 */
	public static function treat($serialized){
		self::writeLog('treat');

		$data = @unserialize($serialized);

		self::writeLog('serialized', $serialized);
		if($data===false){
			if(reset(self::$_steps_left)=='last_item'){

				$serialized = self::handleLastItem($serialized);
				
				if (($key = array_search('last_item', self::$_steps_left)) !== false) {
					unset(self::$_steps_left[$key]);
				}

				return self::treat($serialized);
			}
			
			if(reset(self::$_steps_left)=='array_not_close'){

				$serialized = self::handleArrayNotClose($serialized);
				
				if (($key = array_search('array_not_close', self::$_steps_left)) !== false) {
					unset(self::$_steps_left[$key]);
				}
				
				return self::treat($serialized);
			}
			
			if(reset(self::$_steps_left)=='invalid_length'){
				
				$serialized = self::handleInvalidLength($serialized);
				if (($key = array_search('invalid_length', self::$_steps_left)) !== false) {
					unset(self::$_steps_left[$key]);
				}
				
				return self::treat($serialized);
			}
			
			if(reset(self::$_steps_left)=='bracket'){
				
				$serialized = self::handleBracket($serialized);
				if (($key = array_search('bracket', self::$_steps_left)) !== false) {
					unset(self::$_steps_left[$key]);
				}
				
				return self::treat($serialized);
			}
			
			throw new Exceptions\CorruptedException();
		}
		
		
		return $data;
	}	
	
	/**
	 * Check last item
	 * @param string $serialized
	 * @return string
	 */
	public static function handleLastItem($serialized){
		//https://regex101.com/r/nMA31z
		preg_match_all('/(['.implode("|",self::$_serialize_type).']):([0-9]{0,})/', $serialized, $matches, PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
				
		if($matches){
			$type = end($matches)[1][0];
			
			self::writeLog('type', $type);
			$serialized = self::handleLastItemByType($matches,$serialized,$type);
		}
		
		return $serialized;
	}
	
	/**
	 * Check every element length
	 * @param string $serialized
	 * @return string
	 */
	public static function handleInvalidLength($serialized){
		//https://regex101.com/r/nGmMno
		preg_match_all('/([b|s]):([0-9]{0,})/', $serialized, $matches, PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		
		if($matches){
			$position_offset = 0;
			foreach($matches as $match){
				$type = $match[1][0];
				$lenght = (int)$match[2][0];
				$position = (int)$match[2][1]+$position_offset;
				self::writeLog('type', $type);
				
				if($type=='s'){
					$content = substr($serialized, $position+strlen($lenght)+2,$lenght);
					$content_have_doublequote = stristr($content,'"');
					
					if(substr($serialized, $position+strlen($lenght)+2+$lenght,1)!=='"' || $content_have_doublequote){
					
						//content end earlier
						if($content_have_doublequote){
							self::writeLog('content_have_doublequote', $content_have_doublequote);
							$double_quote_position = strpos($content, '"');
							$nb_to_insert = $lenght-$double_quote_position;
							
							$serialized = substr_replace($serialized, str_repeat('X', $lenght-$double_quote_position), $position+strlen($lenght)+2+$double_quote_position, 0);
							
							$position_offset+=$nb_to_insert;
						}else{
							$valid_length = strpos($serialized,'"',$position+3)-$position-3;
							
							$serialized = substr_replace($serialized, $valid_length, $position, strlen($lenght));
							
							if(strlen($valid_length)!==strlen($lenght)){
								$position_offset+=strlen($valid_length)-strlen($lenght);
							}
						}
					}
				}elseif($type=='b'){
					if(!in_array($lenght,[0,1])){
						$serialized = substr_replace($serialized, 1, $position,strlen($lenght));
					}
				}
			}
		}

		return $serialized;
	}
	
	/**
	 * Called by handleLastItem
	 * @param array $matches
	 * @param string $serialized
	 * @param string $type
	 * @return string
	 * @throws UnserializeFixer\Exceptions\InvalidTypeException
	 */
	public static function handleLastItemByType($matches,$serialized,$type){
		switch($type){
				case 's' : 
					
					$match = end($matches);
					
					$lenght = $match[2][0];;
					$position = $match[2][1];
					if($lenght==''){
						$lenght=1;
						$serialized.= '1';
					}

					if(substr($serialized, $match[2][1]+strlen($match[2][0]),1)==''){
						$serialized.=':';
					}
					if(substr($serialized, $match[2][1]+strlen($match[2][0])+1,1)==''){
						$serialized.='"';
					}
					$nb_char_missing= $lenght - (strlen($serialized) - ($match[2][1] + strlen($lenght) + 4));
					
					self::writeLog('nb_char_missing', $nb_char_missing);
					if($nb_char_missing>0){
						$serialized.= str_repeat("X", $nb_char_missing).'"';
					}elseif($nb_char_missing<0){
						$serialized = substr_replace($serialized, abs($nb_char_missing)+2+$lenght, $position, strlen($lenght)).'";';
					}
					
					break;
				case 'i' :
				case 'b' :
				case 'd' : 
					if(substr($serialized,-1)==':'){
						$serialized.= '1';
					}
					break;
				
				case 'a' : 
				
					//array_not_close gonna fix
					break;
				
				default :
				
					throw new Exceptions\InvalidTypeException($type);
		}	
		
		return $serialized;
	}
	
	/**
	 * Check if all bracket are closed
	 * @param type $serialized
	 * @return type
	 */
	public static function handleBracket($serialized){
		
		$missing_bracket = substr_count($serialized, '{')-substr_count($serialized, '}');
		self::writeLog('missing_bracket', $missing_bracket);
		
		if($missing_bracket>0){
			$serialized.= str_repeat("}", $missing_bracket);
		}
		
		return $serialized;
	}
	
	/**
	 * Check any array
	 * @param string $serialized
	 * @return string
	 */
	public static function handleArrayNotClose($serialized){
		
		//https://regex101.com/r/GlJioI
		preg_match_all('/a:([0-9]{0,})/', $serialized, $matches, PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		
		if($matches){
			$previous_imbricated_array_lenght = 0;
			foreach(array_reverse($matches) as $match){
				$position = $match[0][1];
				$lenght =  $match[1][0];
				
				if(substr($serialized,$match[0][1]+strlen($match[0][0]),1)!==':'){
					$serialized = substr_replace($serialized, ':', $match[0][1]+strlen($match[0][0]), 0);
				}
				if(substr($serialized,$match[0][1]+strlen($match[0][0])+1,1)!=='{'){
					$serialized = substr_replace($serialized, '{', $match[0][1]+strlen($match[0][0])+1, 0);
				}
				
				$content = substr($serialized,$match[0][1]+2+strlen($lenght)+2);
				self::writeLog('content', $content);
				
				//https://regex101.com/r/6xOzG7
				preg_match_all('/['.implode("|",self::$_serialize_type).']:/', $content, $matches);

				$nb_element_missing = $lenght*2 - count(end($matches))+$previous_imbricated_array_lenght;
				
				self::writeLog('nb_element_missing', $nb_element_missing);
				
				if($nb_element_missing>0){
					for($i=0;$i<$nb_element_missing;$i++){
						$last_char = substr($serialized,-1);
						if($last_char!==';' && $last_char!=='{' && $last_char!=='}'){
							$serialized.=';';
						}
						$serialized.='s:'.strlen('X_'.$i).':"X_'.$i.'"';
					}
					
					$serialized .=';}';
				}
				
				$previous_imbricated_array_lenght+=$lenght*2;
			}
		}
		
		return $serialized;
	}
}