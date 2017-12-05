<?php

namespace UnserializeFixer;

class CorruptedElement  extends BaseElement{
		//todo corrupted clean
	public function repairElement() {
		
		/*if(!isset($this->part[1]) || $this->part[1] !== ':'){
			
			echo 'DEBUG BENJAMIN <hr />'.__FILE__.' : '.__LINE__.' : <pre>';var_dump($this);exit;
			$this->part[1]=':';
			
			$this->supposed_type = $this->part[0];
		}*/
		
	}
	
	public function getSerializeElement() {
		
		return null;
	}
}
