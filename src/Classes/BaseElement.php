<?php

namespace UnserializeFixer;

abstract class BaseElement {
	public $part	 = null;
	public $corrupted	 = false;
	public $ignore =null;
	public $cause =null;
	public $fix_try =0;

	public function getCause() {
		return $this->cause;
	}

	public function setCause($cause) {
		$this->cause = $cause;
	}

	public function getSerialize(){
		if($this->getIgnore()===null){
			return $this->getSerializeElement();
		}else{
			return null;
		}
	}
	public function getFixTry() {
		return $this->fix_try;
	}

	public function incrementFixTry($increment) {
		$this->fix_try +=$increment;
	}
	
	function getPart() {
		return $this->part;
	}

	function setPart($part) {
		$this->part = $part;
	}

	public function getCorrupted() {
		return $this->corrupted;
	}

	public function setCorrupted(bool $corrupted) {
		$this->corrupted = $corrupted;
	}
	
	public function getIgnore() {
		return $this->ignore;
	}

	public function setIgnore($ignore) {
		$this->ignore = $ignore;
	}

	public function repair() {
		
		$this->incrementFixTry(1);
		
		$this->repairElement();
	}
	
}
