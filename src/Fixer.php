<?php

use UnserializeFixer\Interfaces;
use UnserializeFixer\Exceptions;

namespace UnserializeFixer;

class Fixer implements Interfaces\iFixer {
	private static $_config			 = null;
	private static $_serialized		 = null;
	private static $_element		 = [];

	/**
	 * writeLog to overwrite see Implements Folder
	 * @param string $message
	 * @param type $data
	 * @param string $level
	 */
	public static function writeLog($message, $data = null, $level = 'debug') {
		if (self::getConfig()->getLogEnabled())
		{
			switch ($level) {
				default:
				case 'debug' :
					$color	 = "C7FAFF";
					break;
				case 'info' :
					$color	 = "1A30F5";
					break;
				case 'warning' :
					$color	 = "FFFF80";
					break;
				case 'error' :
					$color	 = "FD1111";
					break;
			}
			echo '<p style="background-color:' . $color . ';">' . $message . '</p>';
			if ($data)
			{
				echo '<pre>';
				print_r($data);
				echo '</pre>';
			}
		}
	}

	public static function getConfig() {
		if (self::$_config === null)
		{
			$confg = new Config();
			$confg->init();
			self::setConfig($confg);
		}

		return self::$_config;
	}

	public static function setConfig(Config $config) {
		self::$_config = $config;
	}

	/**
	 * Main function
	 * @param type self::$_serialized
	 * @return type
	 */
	public static function run($serialized) {
		self::writeLog('run');

		self::$_serialized = $serialized;
		self::explode();
		
		return self::treat();
	}

	public static function explode() {
		
		$current_element=null;
		$explode_part = [];
		$parts = explode(';',self::$_serialized);
		foreach($parts as $part){
			$explode_part = array_merge($explode_part, explode('{',$part));
		}

		foreach($explode_part as $part){
			$element = ElementFactory::makeElement($part);
			if($element !==null){
				if(count(self::$_element)==0){
					self::$_element = $element;
					$current_element = self::$_element;
				}else{
					if($current_element instanceof CanHoldElement){
						if(count($current_element->elements)<=$current_element->length*2){
							$current_element->addElement($element);
						}
						
						if($element instanceof CanHoldElement){
							$current_element = $element;
						}							
					}
				}
			}
		}
	}
	
	/**
	 * Main function
	 * @return type
	 * @throws \UnserializeFixer\Exceptions\CorruptedException
	 */
	public static function treat() {
		//todo avoid infinite loop
		self::writeLog('treat');

		$data = @unserialize(self::$_serialized);
		self::writeLog('serialized', self::$_serialized);
		
		//still unvalid ?
		if ($data === false)
		{
			if(self::$_element instanceof CanHoldElement){
				$corruptedElements = self::$_element->getCorruptedElements(true);
				foreach($corruptedElements as $corruptedElement){
					$corruptedElement->repair();
					if($corruptedElement->getFixTry()==3){
						$corruptedElement->setIgnore(true);
					}
				}
				
				self::refeshSerialized();
			
				return self::treat();
			}else{
				if(self::$_element instanceof CorruptedElement || self::$_element->getCorrupted()===true){
					self::$_element->repair();
					if(self::$_element->getFixTry()==3){
						throw new Exceptions\CorruptedException();
					}
				}
				
				self::refeshSerialized();
				return self::treat();
			}
		}

		return $data;
	}

	public static function refeshSerialized(){
		self::$_serialized = '';
		
		self::$_serialized .= self::$_element->getSerialize();
		
		self::handleInvalidSubsequence();
	}
	
	public static function handleInvalidSubsequence() {
		self::$_serialized = preg_replace('~};~', '}', self::$_serialized);
		self::$_serialized = preg_replace('~{{2,}~', '{', self::$_serialized);
	}
}
